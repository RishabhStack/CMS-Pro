<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeRepository extends BaseRepository
{
    protected function model(): string
    {
        return Employee::class;
    }

    public function generateEmployeeCode(int $companyId): string
    {
        $prefix = 'EMP-' . str_pad($companyId, 3, '0', STR_PAD_LEFT) . '-';
        $lastEmployee = $this->model->where('company_id', $companyId)
            ->where('employee_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEmployee) {
            $lastNumber = (int) Str::after($lastEmployee->employee_code, $prefix);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
