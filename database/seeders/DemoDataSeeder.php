<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeStatus;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\SalaryComponent;
use App\Models\EmployeeSalary;
use App\Models\Payroll;
use App\Models\Document;
use App\Models\Announcement;
use App\Models\CompanySetting;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ====================================================================
        // 1. COMPANY
        // ====================================================================
        $company = Company::create([
            'name' => 'Milind Corporation',
            'email' => 'info@milinddaraniya.com',
            'phone' => '+1-555-0100',
            'address' => '123 Business Avenue, Suite 100',
            'city' => 'San Francisco',
            'state' => 'California',
            'country' => 'United States',
            'postal_code' => '94105',
            'timezone' => 'America/New_York',
            'currency' => 'USD',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'status' => 'active',
        ]);

        // ====================================================================
        // 2. COMPANY SETTINGS
        // ====================================================================
        $settings = [
            ['company_logo', null, 'text'],
            ['company_website', 'https://milinddaraniya.com', 'text'],
            ['date_format', 'Y-m-d', 'text'],
            ['time_format', 'H:i:s', 'text'],
            ['timezone', 'America/New_York', 'text'],
            ['currency', 'USD', 'text'],
            ['currency_symbol', '$', 'text'],
            ['fiscal_year_start', '01-01', 'text'],
            ['fiscal_year_end', '12-31', 'text'],
            ['attendance_tolerance_minutes', '15', 'number'],
            ['overtime_rate', '1.5', 'number'],
            ['payroll_processing_day', '1', 'number'],
            ['leave_approval_required', 'true', 'boolean'],
            ['dark_mode', 'false', 'boolean'],
            ['notify_leave_requests', 'true', 'boolean'],
            ['notify_attendance_reminders', 'true', 'boolean'],
        ];
        foreach ($settings as $s) {
            CompanySetting::create([
                'company_id' => $company->id,
                'key' => $s[0],
                'value' => $s[1],
                'type' => $s[2],
            ]);
        }

        // ====================================================================
        // 3. EMPLOYEE STATUSES
        // ====================================================================
        $statusData = [
            ['Active', '#10b981'],
            ['Probation', '#f59e0b'],
            ['Notice Period', '#ef4444'],
            ['Terminated', '#6b7280'],
            ['Resigned', '#ec4899'],
        ];
        $statuses = [];
        foreach ($statusData as $i => $s) {
            $st = EmployeeStatus::create([
                'company_id' => $company->id,
                'name' => $s[0],
                'slug' => Str::slug($s[0]) . '-' . $company->id,
                'color' => $s[1],
                'status' => 'active',
            ]);
            $statuses[$s[0]] = $st;
        }

        // ====================================================================
        // 4. DEPARTMENTS
        // ====================================================================
        $deptNames = [
            'Engineering', 'Marketing', 'Sales', 'Human Resources',
            'Finance', 'Operations', 'Design', 'Legal'
        ];
        $depts = [];
        foreach ($deptNames as $name) {
            $d = Department::create([
                'company_id' => $company->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . $company->id,
                'status' => 'active',
            ]);
            $depts[$name] = $d;
        }

        // ====================================================================
        // 5. DESIGNATIONS (department-wise)
        // ====================================================================
        $designationData = [
            'Engineering' => ['Junior Software Engineer', 'Software Engineer', 'Senior Engineer', 'Tech Lead', 'Engineering Manager', 'VP Engineering'],
            'Marketing' => ['Marketing Intern', 'Marketing Specialist', 'Content Writer', 'SEO Analyst', 'Marketing Manager', 'Brand Manager'],
            'Sales' => ['Sales Intern', 'Sales Representative', 'Account Executive', 'Senior Account Executive', 'Sales Manager', 'Regional Director'],
            'Human Resources' => ['HR Intern', 'HR Coordinator', 'HR Generalist', 'HR Manager', 'HR Director'],
            'Finance' => ['Finance Intern', 'Accountant', 'Financial Analyst', 'Finance Manager', 'CFO'],
            'Operations' => ['Operations Intern', 'Operations Analyst', 'Operations Manager', 'COO'],
            'Design' => ['Design Intern', 'Graphic Designer', 'UI/UX Designer', 'Design Lead', 'Creative Director'],
            'Legal' => ['Paralegal', 'Corporate Counsel', 'General Counsel'],
        ];
        $designations = [];
        foreach ($designationData as $deptName => $desigNames) {
            foreach ($desigNames as $desigName) {
                $d = Designation::create([
                    'company_id' => $company->id,
                    'department_id' => $depts[$deptName]->id,
                    'name' => $desigName,
                    'slug' => Str::slug($desigName) . '-' . $company->id,
                    'status' => 'active',
                ]);
                $designations[$desigName] = $d;
            }
        }

        // ====================================================================
        // 6. SALARY COMPONENTS (must be before employees)
        // ====================================================================
        $salaryComponentData = [
            ['Basic Salary', 'earning', 'fixed', 0],
            ['House Rent Allowance', 'earning', 'fixed', 0],
            ['Conveyance Allowance', 'earning', 'fixed', 0],
            ['Medical Allowance', 'earning', 'fixed', 0],
            ['Special Allowance', 'earning', 'fixed', 0],
            ['Provident Fund', 'deduction', 'percentage', 12],
            ['Professional Tax', 'deduction', 'fixed', 200],
            ['Income Tax', 'deduction', 'percentage', 10],
        ];
        foreach ($salaryComponentData as $sc) {
            SalaryComponent::create([
                'company_id' => $company->id,
                'name' => $sc[0],
                'slug' => Str::slug($sc[0]) . '-' . $company->id,
                'type' => $sc[1],
                'value_type' => $sc[2],
                'default_value' => $sc[3],
                'status' => 'active',
            ]);
        }

        // ====================================================================
        // 7. ROLES
        // ====================================================================
        $ownerRole = Role::create([
            'company_id' => $company->id,
            'name' => 'Owner',
            'slug' => 'owner-' . $company->id,
            'description' => 'Full system access',
            'is_system' => true,
            'status' => 'active',
        ]);
        $adminRole = Role::create([
            'company_id' => $company->id,
            'name' => 'Admin',
            'slug' => 'admin-' . $company->id,
            'description' => 'Administrative access',
            'is_system' => true,
            'status' => 'active',
        ]);
        $employeeRole = Role::create([
            'company_id' => $company->id,
            'name' => 'Employee',
            'slug' => 'employee-' . $company->id,
            'description' => 'Employee access',
            'is_system' => true,
            'status' => 'active',
        ]);

        // Assign all permissions to Owner
        $allPerms = Permission::all();
        $ownerRole->permissions()->attach($allPerms->pluck('id')->toArray());

        // Admin gets all groups except Company and Roles (Owner-only)
        $adminGroupPerms = Permission::whereIn('group', [
            'Employees', 'Departments', 'Designations', 'Attendance', 'Leaves',
            'Leave Types', 'Holidays', 'Payroll', 'Documents', 'Announcements',
            'Salary Components', 'Settings', 'Reports',
        ])->pluck('id')->toArray();
        $adminRole->permissions()->attach($adminGroupPerms);

        // Employee gets view + self-service permissions only
        $empSlugs = [
            'view_employees', 'view_attendance', 'view_leaves', 'apply_leaves',
            'view_holidays', 'view_documents', 'upload_documents', 'download_documents',
            'view_announcements', 'view_leave_types', 'view_salary_components', 'view_payroll',
        ];
        $empPerms = Permission::whereIn('slug', $empSlugs)->pluck('id')->toArray();
        $employeeRole->permissions()->attach($empPerms);

        // ====================================================================
        // 7. USERS & EMPLOYEES
        // ====================================================================
        $now = Carbon::now();

        $people = [
            // Owner
            ['John', 'Smith', 'owner@example.com', 'Owner', 'Finance', 'CFO', 'permanent', '2020-01-15', 'Active', 'EMP-0001', 180000],
            // Admin
            ['Sarah', 'Johnson', 'admin@example.com', 'Admin', 'Engineering', 'Engineering Manager', 'permanent', '2020-03-01', 'Active', 'EMP-0002', 120000],
            // Employees
            ['Mike', 'Wilson', 'employee@example.com', 'Employee', 'Sales', 'Sales Representative', 'permanent', '2021-06-15', 'Active', 'EMP-0003', 55000],
            ['Priya', 'Sharma', 'priya@example.com', 'Employee', 'Engineering', 'Senior Engineer', 'permanent', '2021-08-01', 'Active', 'EMP-0004', 95000],
            ['Raj', 'Patel', 'raj@example.com', 'Employee', 'Engineering', 'Software Engineer', 'permanent', '2022-01-10', 'Active', 'EMP-0005', 75000],
            ['Emily', 'Davis', 'emily@example.com', 'Employee', 'Marketing', 'Marketing Manager', 'permanent', '2021-03-15', 'Active', 'EMP-0006', 85000],
            ['David', 'Brown', 'david@example.com', 'Employee', 'Marketing', 'Content Writer', 'permanent', '2022-06-20', 'Active', 'EMP-0007', 50000],
            ['Lisa', 'Anderson', 'lisa@example.com', 'Employee', 'Human Resources', 'HR Manager', 'permanent', '2020-11-01', 'Active', 'EMP-0008', 80000],
            ['James', 'Taylor', 'james@example.com', 'Employee', 'Human Resources', 'HR Coordinator', 'permanent', '2023-02-15', 'Probation', 'EMP-0009', 45000],
            ['Anita', 'Gupta', 'anita@example.com', 'Employee', 'Sales', 'Account Executive', 'permanent', '2022-04-01', 'Active', 'EMP-0010', 65000],
            ['Robert', 'Martinez', 'robert@example.com', 'Employee', 'Sales', 'Sales Manager', 'permanent', '2021-09-15', 'Active', 'EMP-0011', 90000],
            ['Neha', 'Verma', 'neha@example.com', 'Employee', 'Design', 'UI/UX Designer', 'permanent', '2022-08-01', 'Active', 'EMP-0012', 70000],
            ['Alex', 'Kim', 'alex@example.com', 'Employee', 'Design', 'Graphic Designer', 'permanent', '2023-01-15', 'Active', 'EMP-0013', 48000],
            ['Maria', 'Garcia', 'maria@example.com', 'Employee', 'Finance', 'Financial Analyst', 'permanent', '2021-11-01', 'Active', 'EMP-0014', 72000],
            ['Vikram', 'Singh', 'vikram@example.com', 'Employee', 'Operations', 'Operations Manager', 'permanent', '2022-03-01', 'Active', 'EMP-0015', 82000],
            ['Sophie', 'Chen', 'sophie@example.com', 'Employee', 'Engineering', 'Tech Lead', 'permanent', '2020-07-15', 'Active', 'EMP-0016', 110000],
            ['Rahul', 'Joshi', 'rahul@example.com', 'Employee', 'Engineering', 'Junior Software Engineer', 'permanent', '2023-06-01', 'Probation', 'EMP-0017', 40000],
            ['Laura', 'White', 'laura@example.com', 'Employee', 'Legal', 'Corporate Counsel', 'permanent', '2022-09-01', 'Active', 'EMP-0018', 100000],
            ['Kevin', 'O\'Brien', 'kevin@example.com', 'Employee', 'Sales', 'Regional Director', 'permanent', '2020-05-01', 'Active', 'EMP-0019', 130000],
            ['Deepa', 'Nair', 'deepa@example.com', 'Employee', 'Marketing', 'Brand Manager', 'permanent', '2022-11-15', 'Active', 'EMP-0020', 78000],
        ];

        $createdEmployees = [];
        $roleMap = ['Owner' => $ownerRole, 'Admin' => $adminRole, 'Employee' => $employeeRole];

        foreach ($people as $i => $p) {
            $user = User::create([
                'company_id' => $company->id,
                'first_name' => $p[0],
                'last_name' => $p[1],
                'email' => $p[2],
                'password' => Hash::make('password'),
                'phone' => '+1-555-' . str_pad(mt_rand(100, 999), 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'active',
                'language' => 'en',
            ]);
            $user->roles()->attach($roleMap[$p[3]]->id);

            $dept = $depts[$p[4]];
            $desig = $designations[$p[5]];
            $statusName = $p[8];
            $statusModel = $statuses[$statusName];
            $joiningDate = $p[7];

            $emp = Employee::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'employee_code' => $p[9],
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'status_id' => $statusModel->id,
                'joining_date' => $joiningDate,
                'confirmation_date' => $statusName === 'Active' ? Carbon::parse($joiningDate)->addMonths(6) : null,
                'employment_type' => $p[6],
                'work_shift' => 'General',
                'work_location' => 'San Francisco Office',
                'emergency_contact_name' => 'Jane Doe',
                'emergency_contact_phone' => '+1-555-999-9999',
                'emergency_contact_relation' => 'Spouse',
                'notes' => null,
                'status' => $statusName === 'Active' || $statusName === 'Probation' ? 'active' : 'inactive',
            ]);

            $createdEmployees[] = $emp;

            // Create employee salaries for each earning component
            $baseSalary = $p[10];
            $earnings = [
                'Basic Salary' => round($baseSalary * 0.45),
                'House Rent Allowance' => round($baseSalary * 0.20),
                'Conveyance Allowance' => round($baseSalary * 0.05),
                'Medical Allowance' => round($baseSalary * 0.03),
                'Special Allowance' => round($baseSalary * 0.27),
            ];
            $earningComponents = SalaryComponent::where('company_id', $company->id)->where('type', 'earning')->get()->keyBy('name');
            foreach ($earnings as $compName => $amount) {
                if (isset($earningComponents[$compName])) {
                    EmployeeSalary::create([
                        'company_id' => $company->id,
                        'employee_id' => $emp->id,
                        'salary_component_id' => $earningComponents[$compName]->id,
                        'amount' => $amount,
                        'effective_date' => $emp->joining_date,
                        'status' => 'active',
                    ]);
                }
            }
            $deductionComponents = SalaryComponent::where('company_id', $company->id)->where('type', 'deduction')->get()->keyBy('name');
            $deductions = [
                'Provident Fund' => round($baseSalary * 0.12),
                'Professional Tax' => 200,
                'Income Tax' => round($baseSalary * 0.10),
            ];
            foreach ($deductions as $compName => $amount) {
                if (isset($deductionComponents[$compName])) {
                    EmployeeSalary::create([
                        'company_id' => $company->id,
                        'employee_id' => $emp->id,
                        'salary_component_id' => $deductionComponents[$compName]->id,
                        'amount' => $amount,
                        'effective_date' => $emp->joining_date,
                        'status' => 'active',
                    ]);
                }
            }
        }

        // ====================================================================
        // 8. LEAVE TYPES
        // ====================================================================
        $leaveTypeData = [
            ['Annual Leave', 20, true, 10, '#10b981'],
            ['Sick Leave', 12, false, 0, '#f59e0b'],
            ['Personal Leave', 5, false, 0, '#3b82f6'],
            ['Maternity Leave', 90, false, 0, '#ec4899'],
            ['Paternity Leave', 10, false, 0, '#8b5cf6'],
            ['Bereavement Leave', 3, false, 0, '#6b7280'],
        ];
        $leaveTypes = [];
        foreach ($leaveTypeData as $lt) {
            $ltModel = LeaveType::create([
                'company_id' => $company->id,
                'name' => $lt[0],
                'slug' => Str::slug($lt[0]) . '-' . $company->id,
                'days_per_year' => $lt[1],
                'carry_forward' => $lt[2],
                'max_carry_forward' => $lt[3],
                'color' => $lt[4],
                'status' => 'active',
            ]);
            $leaveTypes[$lt[0]] = $ltModel;
        }

        // ====================================================================
        // 9. HOLIDAYS (current year + next year)
        // ====================================================================
        $holidayDates = [
            ['New Year', '01-01', 'public'],
            ['Martin Luther King Jr. Day', '01-20', 'public'],
            ['Presidents Day', '02-17', 'public'],
            ['Good Friday', '04-18', 'public'],
            ['Memorial Day', '05-26', 'public'],
            ['Juneteenth', '06-19', 'public'],
            ['Independence Day', '07-04', 'public'],
            ['Labor Day', '09-01', 'public'],
            ['Thanksgiving Day', '11-27', 'public'],
            ['Christmas Day', '12-25', 'public'],
        ];
        foreach ([$now->year, $now->year + 1] as $year) {
            foreach ($holidayDates as $h) {
                $date = Carbon::createFromFormat('Y-m-d', $year . '-' . $h[1]);
                Holiday::create([
                    'company_id' => $company->id,
                    'name' => $h[0],
                    'date' => $date,
                    'year' => $year,
                    'type' => $h[2],
                    'status' => 'active',
                ]);
            }
        }

        // ====================================================================
        // 11. ATTENDANCE (last 30 working days for each employee)
        // ====================================================================
        $activeEmployees = Employee::where('company_id', $company->id)
            ->whereIn('status', ['active'])->get();

        $startDate = $now->copy()->subDays(60)->startOfDay();
        $endDate = $now->copy()->subDay()->startOfDay();

        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            if ($date->isWeekend()) {
                $date->addDay();
                continue;
            }
            foreach ($activeEmployees as $emp) {
                // 85% chance they were present
                $present = mt_rand(1, 100) <= 85;
                if ($present) {
                    $clockIn = $date->copy()->setHour(mt_rand(8, 9))->setMinute(mt_rand(0, 30));
                    $clockOut = $date->copy()->setHour(mt_rand(17, 18))->setMinute(mt_rand(0, 30));
                    $breakStart = $date->copy()->setHour(12)->setMinute(mt_rand(0, 30));
                    $breakEnd = $date->copy()->setHour(13)->setMinute(mt_rand(0, 30));
                    $totalBreak = $breakStart->diffInMinutes($breakEnd);
                    $totalHours = $clockIn->diffInMinutes($clockOut) / 60;
                    $totalHours = round($totalHours - ($totalBreak / 60), 2);
                    $overtime = max(0, round($totalHours - 8, 2));

                    Attendance::create([
                        'company_id' => $company->id,
                        'employee_id' => $emp->id,
                        'date' => $date->copy(),
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                        'break_start' => $breakStart,
                        'break_end' => $breakEnd,
                        'total_break_minutes' => $totalBreak,
                        'total_hours' => $totalHours,
                        'overtime_hours' => $overtime,
                        'status' => 'present',
                        'ip_address' => '192.168.1.' . mt_rand(1, 255),
                        'location' => 'San Francisco Office',
                    ]);
                } else {
                    Attendance::create([
                        'company_id' => $company->id,
                        'employee_id' => $emp->id,
                        'date' => $date->copy(),
                        'status' => 'absent',
                    ]);
                }
            }
            $date->addDay();
        }

        // ====================================================================
        // 12. LEAVE RECORDS
        // ====================================================================
        $leaveReasons = [
            'Not feeling well',
            'Family function',
            'Personal work',
            'Doctor appointment',
            'Traveling out of town',
            'Family emergency',
        ];

        // Create some approved leaves in the past
        foreach ($activeEmployees as $emp) {
            $numLeaves = mt_rand(1, 3);
            for ($i = 0; $i < $numLeaves; $i++) {
                $lt = $leaveTypes[array_rand($leaveTypes)];
                $start = $now->copy()->subDays(mt_rand(15, 60));
                $totalDays = mt_rand(1, 3);
                $end = $start->copy()->addDays($totalDays - 1);
                $approvedBy = $activeEmployees->random()->user_id;

                Leave::create([
                    'company_id' => $company->id,
                    'employee_id' => $emp->id,
                    'leave_type_id' => $lt->id,
                    'start_date' => $start,
                    'end_date' => $end,
                    'total_days' => $totalDays,
                    'reason' => $leaveReasons[array_rand($leaveReasons)],
                    'status' => 'approved',
                    'approved_by' => $approvedBy,
                    'approved_at' => $start->copy()->subDays(mt_rand(1, 5)),
                ]);
            }
        }

        // Create some pending leaves
        foreach ($activeEmployees->take(5) as $emp) {
            $lt = $leaveTypes[array_rand($leaveTypes)];
            $start = $now->copy()->addDays(mt_rand(5, 20));
            Leave::create([
                'company_id' => $company->id,
                'employee_id' => $emp->id,
                'leave_type_id' => $lt->id,
                'start_date' => $start,
                'end_date' => $start->copy()->addDays(mt_rand(0, 2)),
                'total_days' => mt_rand(1, 2),
                'reason' => $leaveReasons[array_rand($leaveReasons)],
                'status' => 'pending',
            ]);
        }

        // ====================================================================
        // 13. PAYROLL (last 3 months for each active employee)
        // ====================================================================
        foreach ($activeEmployees as $emp) {
            $employeeSalaries = EmployeeSalary::where('employee_id', $emp->id)
                ->whereHas('salaryComponent', fn($q) => $q->where('type', 'earning'))
                ->get();
            $totalEarnings = $employeeSalaries->sum('amount');

            $employeeDeductions = EmployeeSalary::where('employee_id', $emp->id)
                ->whereHas('salaryComponent', fn($q) => $q->where('type', 'deduction'))
                ->get();
            $totalDeductions = $employeeDeductions->sum('amount');

            $netSalary = $totalEarnings - $totalDeductions;

            for ($m = 1; $m <= 3; $m++) {
                $payrollMonth = $now->copy()->subMonths($m);
                $payrollYear = $payrollMonth->year;
                $payrollMonthNum = $payrollMonth->month;

                $workingDays = 22;
                $presentDays = mt_rand(19, 22);
                $absentDays = $workingDays - $presentDays;
                $leaveDays = mt_rand(0, 2);
                $halfDays = mt_rand(0, 2);

                Payroll::create([
                    'company_id' => $company->id,
                    'employee_id' => $emp->id,
                    'payroll_period' => $payrollMonth->format('F Y'),
                    'month' => $payrollMonthNum,
                    'year' => $payrollYear,
                    'basic_salary' => $totalEarnings,
                    'total_earnings' => $totalEarnings,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'total_days' => $workingDays,
                    'working_days' => $workingDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'leave_days' => $leaveDays,
                    'half_days' => $halfDays,
                    'status' => 'paid',
                    'paid_at' => $payrollMonth->copy()->day(1),
                    'payment_method' => 'bank_transfer',
                    'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                ]);
            }
        }

        // ====================================================================
        // 14. DOCUMENTS
        // ====================================================================
        $docTypes = ['offer_letter', 'appraisal', 'id_proof', 'resume', 'certification', 'nda'];
        $docNames = [
            'offer_letter' => 'Offer Letter',
            'appraisal' => 'Performance Appraisal',
            'id_proof' => 'Government ID Proof',
            'resume' => 'Resume / CV',
            'certification' => 'Professional Certification',
            'nda' => 'Non-Disclosure Agreement',
        ];
        foreach ($activeEmployees->take(10) as $emp) {
            $numDocs = mt_rand(2, 4);
            $usedTypes = [];
            for ($i = 0; $i < $numDocs; $i++) {
                $type = $docTypes[array_rand($docTypes)];
                if (in_array($type, $usedTypes)) continue;
                $usedTypes[] = $type;
                Document::create([
                    'company_id' => $company->id,
                    'employee_id' => $emp->id,
                    'name' => $docNames[$type],
                    'type' => $type,
                    'file_path' => 'documents/' . $type . '_' . $emp->employee_code . '.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => mt_rand(100, 5000),
                    'description' => $docNames[$type] . ' for ' . $emp->user->first_name . ' ' . $emp->user->last_name,
                    'expiry_date' => $type === 'id_proof' ? $now->copy()->addYears(mt_rand(1, 5)) : null,
                    'status' => 'active',
                ]);
            }
        }

        // ====================================================================
        // 15. ANNOUNCEMENTS
        // ====================================================================
        $announcements = [
            [
                'title' => 'Welcome to Milind Corporation!',
                'content' => 'We are excited to have you on board. Please take some time to explore the HRMS portal and familiarize yourself with our policies. If you have any questions, reach out to the HR team.',
                'type' => 'general',
                'priority' => 'high',
                'published_at' => $now->copy()->subMonths(2),
            ],
            [
                'title' => 'Annual Company Picnic',
                'content' => 'Mark your calendars! Our annual company picnic will be held on July 15th at Riverside Park. Families are welcome! We will have games, food, and fun activities for everyone.',
                'type' => 'event',
                'priority' => 'normal',
                'published_at' => $now->copy()->subMonth(),
            ],
            [
                'title' => 'New Leave Policy Update',
                'content' => 'Effective next month, the carry forward limit for annual leave has been increased from 10 to 15 days. Please review the updated policy in the documents section.',
                'type' => 'policy',
                'priority' => 'high',
                'published_at' => $now->copy()->subDays(15),
            ],
            [
                'title' => 'Quarterly Town Hall Meeting',
                'content' => 'Join us for the quarterly town hall meeting on March 5th at 3 PM EST. CEO will share company updates and growth plans. Zoom link will be shared before the meeting.',
                'type' => 'event',
                'priority' => 'normal',
                'published_at' => $now->copy()->subDays(10),
            ],
            [
                'title' => 'Office Closed for Maintenance',
                'content' => 'The office will remain closed on March 20th for annual maintenance. All employees are requested to work from home on that day.',
                'type' => 'general',
                'priority' => 'normal',
                'published_at' => $now->copy()->subDays(5),
            ],
            [
                'title' => 'New Hires This Month',
                'content' => 'Please welcome our new team members joining this month! Check the employee directory to learn more about them and reach out to say hello.',
                'type' => 'general',
                'priority' => 'low',
                'published_at' => $now->copy()->subDays(2),
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::create([
                'company_id' => $company->id,
                'title' => $a['title'],
                'content' => $a['content'],
                'type' => $a['type'],
                'priority' => $a['priority'],
                'published_at' => $a['published_at'],
                'status' => 'published',
            ]);
        }

        $this->command->info('Boom! Demo data seeded successfully! 🎉');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('  Owner:    owner@example.com / password');
        $this->command->info('  Admin:    admin@example.com / password');
        $this->command->info('  Employee: employee@example.com / password');
        $this->command->info('  Others:   {firstname}@example.com / password (e.g. priya@example.com)');
        $this->command->info('');
        $this->command->info('20 employees created across 8 departments with attendance, leaves, payroll & documents.');
    }
}
