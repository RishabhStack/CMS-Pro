<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HolidayController extends BaseController
{
    public function __construct(
        protected HolidayService $holidayService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Holiday::class);
            return $this->view('holidays.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $this->authorize('update', $holiday);
            return $this->view('holidays.create', compact('holiday'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Holiday not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Holiday::class);
            $years = range(date('Y') - 5, date('Y') + 2);
            return $this->view('holidays.index', compact('years'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Holiday::class);
            $companyId = $this->getCompanyId();

            $query = Holiday::byCompany($companyId);

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            } else {
                $query->where('year', now()->year);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            return $this->datatableResponse($query, $request);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function store(HolidayRequest $request)
    {
        try {
            $this->authorize('create', Holiday::class);

            $holiday = $this->holidayService->store([
                'company_id' => $this->getCompanyId(),
                'year' => \Carbon\Carbon::parse($request->date)->year,
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            return $this->created('Holiday created successfully.', $holiday);
        } catch (\Exception $e) {
            return $this->error('Failed to create holiday.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $holiday = Holiday::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $holiday);

            return $this->success('Holiday retrieved successfully.', $holiday);
        } catch (ModelNotFoundException $e) {
            return $this->error('Holiday not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve holiday.', $e->getMessage());
        }
    }

    public function update(HolidayRequest $request, $id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $this->authorize('update', $holiday);

            $this->holidayService->update($holiday, [
                'year' => \Carbon\Carbon::parse($request->date)->year,
                ...$request->validated(),
            ]);

            return $this->updated('Holiday updated successfully.', $holiday->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Holiday not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update holiday.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $this->authorize('delete', $holiday);

            $this->holidayService->destroy($holiday);

            return $this->deleted('Holiday deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Holiday not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete holiday.', $e->getMessage());
        }
    }
}
