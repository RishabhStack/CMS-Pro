<?php

namespace App\Services;

use App\Models\Leave;
use App\Repositories\LeaveRepository;
use Illuminate\Support\Facades\DB;

class LeaveService extends BaseService
{
    protected function repository(): string
    {
        return LeaveRepository::class;
    }

    public function applyLeave(array $data): Leave
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function approveLeave(int $leaveId, int $approvedBy): Leave
    {
        return DB::transaction(function () use ($leaveId, $approvedBy) {
            $leave = $this->repository->findOrFail($leaveId);
            $this->repository->update($leave, [
                'status' => 'approved',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);
            return $leave->fresh();
        });
    }

    public function rejectLeave(int $leaveId, int $approvedBy, ?string $reason = null): Leave
    {
        return DB::transaction(function () use ($leaveId, $approvedBy, $reason) {
            $leave = $this->repository->findOrFail($leaveId);
            $this->repository->update($leave, [
                'status' => 'rejected',
                'approved_by' => $approvedBy,
                'rejection_reason' => $reason,
            ]);
            return $leave->fresh();
        });
    }

    public function getPendingLeaves(int $companyId)
    {
        return $this->repository->getPendingLeaves($companyId);
    }
}
