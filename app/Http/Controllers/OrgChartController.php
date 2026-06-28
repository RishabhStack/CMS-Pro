<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class OrgChartController extends BaseController
{
    public function index()
    {
        return $this->view('org-chart.index');
    }

    public function data()
    {
        $companyId = $this->getCompanyId();

        $departments = Department::byCompany($companyId)
            ->with(['employees' => function ($q) {
                $q->active()->with(['user', 'designation', 'reportingTo.user']);
            }])
            ->get();

        $tree = $departments->map(function ($dept) {
            $employees = $dept->employees;
            $managers = $employees->filter(fn($e) => $e->reporting_to_id === null);
            $subordinates = $employees->filter(fn($e) => $e->reporting_to_id !== null);

            $hierarchy = $managers->map(function ($mgr) use ($subordinates) {
                return $this->buildNode($mgr, $subordinates);
            });

            return [
                'id' => 'dept-' . $dept->id,
                'name' => $dept->name,
                'type' => 'department',
                'children' => $hierarchy,
            ];
        });

        return response()->json($tree);
    }

    private function buildNode($employee, $allEmployees)
    {
        $children = $allEmployees->filter(fn($e) => $e->reporting_to_id === $employee->id);

        return [
            'id' => 'emp-' . $employee->id,
            'employee_id' => $employee->id,
            'name' => $employee->full_name,
            'email' => $employee->email,
            'designation' => $employee->designation?->name ?? 'N/A',
            'department' => $employee->department?->name ?? 'N/A',
            'joining_date' => $employee->joining_date?->format('d M Y') ?? 'N/A',
            'is_manager' => $children->isNotEmpty(),
            'children' => $children->map(fn($child) => $this->buildNode($child, $allEmployees))->values(),
        ];
    }
}
