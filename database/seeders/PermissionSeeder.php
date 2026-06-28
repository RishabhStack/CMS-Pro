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
