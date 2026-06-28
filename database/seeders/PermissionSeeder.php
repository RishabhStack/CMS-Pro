<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'Company' => [
                'view_companies',
                'create_companies',
                'edit_companies',
                'delete_companies',
            ],
            'Employees' => [
                'view_employees',
                'create_employees',
                'edit_employees',
                'delete_employees',
                'import_employees',
                'export_employees',
            ],
            'Departments' => [
                'view_departments',
                'create_departments',
                'edit_departments',
                'delete_departments',
            ],
            'Designations' => [
                'view_designations',
                'create_designations',
                'edit_designations',
                'delete_designations',
            ],
            'Roles' => [
                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',
            ],
            'Attendance' => [
                'view_attendance',
                'create_attendance',
                'edit_attendance',
                'delete_attendance',
                'approve_attendance',
            ],
            'Leaves' => [
                'view_leaves',
                'apply_leaves',
                'approve_leaves',
                'reject_leaves',
                'delete_leaves',
            ],
            'Leave Types' => [
                'view_leave_types',
                'create_leave_types',
                'edit_leave_types',
                'delete_leave_types',
            ],
            'Holidays' => [
                'view_holidays',
                'create_holidays',
                'edit_holidays',
                'delete_holidays',
            ],
            'Payroll' => [
                'view_payroll',
                'create_payroll',
                'edit_payroll',
                'delete_payroll',
                'process_payroll',
            ],
            'Salary Components' => [
                'view_salary_components',
                'create_salary_components',
                'edit_salary_components',
                'delete_salary_components',
            ],
            'Documents' => [
                'view_documents',
                'upload_documents',
                'delete_documents',
                'download_documents',
            ],
            'Announcements' => [
                'view_announcements',
                'create_announcements',
                'edit_announcements',
                'delete_announcements',
            ],
            'Settings' => [
                'view_settings',
                'edit_settings',
            ],
            'Reports' => [
                'view_reports',
                'export_reports',
            ],
            'Performance Reviews' => [
                'view_performance_reviews',
                'create_performance_reviews',
                'edit_performance_reviews',
                'delete_performance_reviews',
                'approve_performance_reviews',
            ],
            'Expenses' => [
                'view_expenses',
                'create_expenses',
                'edit_expenses',
                'delete_expenses',
                'approve_expenses',
                'pay_expenses',
            ],
            'Expense Categories' => [
                'view_expense_categories',
                'create_expense_categories',
                'edit_expense_categories',
                'delete_expense_categories',
            ],
            'Assets' => [
                'view_assets',
                'create_assets',
                'edit_assets',
                'delete_assets',
                'assign_assets',
            ],
            'Shifts' => [
                'view_shifts',
                'create_shifts',
                'edit_shifts',
                'delete_shifts',
                'assign_shifts',
            ],
            'Shift Swaps' => [
                'view_shift_swaps',
                'create_shift_swaps',
                'approve_shift_swaps',
            ],
            'Timesheets' => [
                'view_timesheets',
                'create_timesheets',
                'edit_timesheets',
                'delete_timesheets',
                'approve_timesheets',
            ],
            'Projects' => [
                'view_projects',
                'create_projects',
                'edit_projects',
                'delete_projects',
            ],
            'Travel' => [
                'view_travel_requests',
                'create_travel_requests',
                'edit_travel_requests',
                'delete_travel_requests',
                'approve_travel_requests',
            ],
            'Exit Management' => [
                'view_exit_management',
                'create_resignations',
                'approve_resignations',
                'conduct_exit_interviews',
            ],
            'Helpdesk' => [
                'view_tickets',
                'create_tickets',
                'edit_tickets',
                'delete_tickets',
                'assign_tickets',
                'resolve_tickets',
            ],
        ];

        $createdById = 1;

        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::create([
                    'name' => ucwords(str_replace('_', ' ', $perm)),
                    'slug' => $perm,
                    'group' => $group,
                    'description' => 'Allows user to ' . str_replace('_', ' ', $perm),
                    'created_by' => null,
                ]);
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
