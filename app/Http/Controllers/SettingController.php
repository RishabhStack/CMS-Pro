<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Employee;
use App\Services\SettingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends BaseController
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    public function index()
    {
        try {
            $companyId = $this->getCompanyId();
            $company = Company::findOrFail($companyId);
            $settings = $this->settingService->getSettings($companyId);

            $settings['company_name'] = $company->name;
            $settings['company_email'] = $company->email;
            $settings['company_phone'] = $company->phone ?? '';
            $settings['company_address'] = $company->address ?? '';
            $settings['company_logo'] = $company->logo ?? '';

            return $this->view('settings.index', compact('settings'));
        } catch (\Exception $e) {
            return $this->error('Failed to load settings.', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'timezone' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:10',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:10',
            'work_hours_per_day' => 'nullable|numeric|min:1|max:24',
            'work_days_per_week' => 'nullable|integer|min:1|max:7',
            'grace_period' => 'nullable|integer|min:0|max:120',
            'late_threshold' => 'nullable|integer|min:0|max:240',
            'half_day_threshold' => 'nullable|numeric|min:0|max:12',
            'leave_approval_workflow' => 'nullable|string|in:single,multi',
            'max_consecutive_leave_days' => 'nullable|integer|min:1|max:365',
            'rows_per_page' => 'nullable|integer|in:10,25,50,100',
            'theme_color' => 'nullable|string|max:50',
        ]);

        try {
            $companyId = $this->getCompanyId();
            $company = Company::findOrFail($companyId);

            $company->update([
                'name' => $request->company_name ?? $company->name,
                'email' => $request->company_email ?? $company->email,
                'phone' => $request->company_phone ?? $company->phone,
                'address' => $request->company_address ?? $company->address,
            ]);

            if ($request->hasFile('company_logo')) {
                $path = $request->file('company_logo')->store('logos', 'public');
                $company->update(['logo' => $path]);
            }

            $settings = $request->except('_token', '_method', 'company_name', 'company_email', 'company_phone', 'company_address', 'company_logo');

            foreach ($settings as $key => $value) {
                $this->settingService->updateSetting($companyId, $key, $value ?? '');
            }

            Cache::forget("company_{$companyId}_settings");

            return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Failed to update settings.');
        }
    }

    public function theme()
    {
        try {
            $companyId = $this->getCompanyId();
            $company = Company::findOrFail($companyId);

            $company->update([
                'dark_mode' => !$company->dark_mode,
            ]);

            Cache::forget("company_{$companyId}_settings");

            return $this->success('Theme toggled successfully.', [
                'dark_mode' => $company->fresh()->dark_mode,
            ]);
        } catch (\Exception $e) {
            return $this->error('Failed to toggle theme.', $e->getMessage());
        }
    }

    public function deleteAccount($id)
    {
        try {
            $user = User::findOrFail($id);

            if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
                return $this->error('Only owners and admins can delete accounts.', null, 403);
            }

            if (auth()->id() === (int) $id) {
                return $this->error('You cannot delete your own account.', null, 403);
            }

            DB::transaction(function () use ($user) {
                $employee = $user->employee;

                if ($employee) {
                    $employee->attendance()->forceDelete();
                    $employee->leaves()->forceDelete();
                    $employee->documents()->forceDelete();
                    $employee->payrolls()->forceDelete();
                    $employee->salaries()->forceDelete();
                    $employee->forceDelete();
                }

                $user->roles()->detach();
                $user->forceDelete();
            });

            return $this->success('Account and all associated data deleted permanently.');
        } catch (ModelNotFoundException $e) {
            return $this->error('User not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete account.', $e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            $companyId = $this->getCompanyId();
            $this->settingService->clearCache($companyId);
            Cache::forget("dashboard_stats_{$companyId}");

            return $this->success('Cache cleared successfully.');
        } catch (\Exception $e) {
            return $this->error('Failed to clear cache.', $e->getMessage());
        }
    }
}
