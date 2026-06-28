<?php

namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

class LeaveRepository extends BaseRepository
{
    protected function model(): string
    {
        return Leave::class;
    }

    public function getEmployeeLeaves(int $employeeId, ?string $status = null): Collection
    {
        $query = $this->model->where('employee_id', $employeeId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getPendingLeaves(int $companyId): Collection
    {
        return $this->model->byCompany($companyId)
            ->where('status', 'pending')
            ->with('employee')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
