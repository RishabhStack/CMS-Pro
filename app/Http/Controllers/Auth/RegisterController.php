<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:companies,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
        ]);

        try {
            DB::beginTransaction();

            $company = Company::create([
                'name' => $request->company_name,
                'email' => $request->company_email,
                'status' => 'active',
            ]);

            $user = User::create([
                'company_id' => $company->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'created_by' => null,
            ]);

            $ownerRole = Role::create([
                'company_id' => $company->id,
                'name' => 'Owner',
                'slug' => 'owner-' . $company->id,
                'description' => 'Company owner with full access',
                'is_system' => true,
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            $adminRole = Role::create([
                'company_id' => $company->id,
                'name' => 'Admin',
                'slug' => 'admin-' . $company->id,
                'description' => 'Administrator with management access',
                'is_system' => true,
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            $employeeRole = Role::create([
                'company_id' => $company->id,
                'name' => 'Employee',
                'slug' => 'employee-' . $company->id,
                'description' => 'Regular employee',
                'is_system' => true,
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            $user->roles()->attach($ownerRole->id);

            $employee = Employee::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'employee_code' => 'OWN-001',
                'joining_date' => now(),
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            DB::commit();

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Company registered successfully! Welcome to HRMS.',
                'redirect' => route('dashboard')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
