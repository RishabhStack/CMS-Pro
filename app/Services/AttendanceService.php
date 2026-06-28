<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Repositories\AttendanceRepository;
use Illuminate\Support\Facades\DB;

class AttendanceService extends BaseService
{
    protected function repository(): string
    {
        return AttendanceRepository::class;
    }

    public function clockIn(int $employeeId, string $date, string $time): Attendance
    {
        return DB::transaction(function () use ($employeeId, $date, $time) {
            $employee = Employee::findOrFail($employeeId);
            return $this->repository->create([
                'employee_id' => $employeeId,
                'company_id' => $employee->company_id,
                'date' => $date,
                'clock_in' => $time,
                'status' => 'present',
            ]);
        });
    }

    public function clockOut(int $employeeId, string $date, string $time): Attendance
    {
        return DB::transaction(function () use ($employeeId, $date, $time) {
            $attendance = $this->repository->getTodayAttendance($employeeId, $date);
            $this->repository->update($attendance, ['clock_out' => $time]);
            return $attendance->fresh();
        });
    }

    public function markBreak(int $employeeId, string $date, string $time): Attendance
    {
        return DB::transaction(function () use ($employeeId, $date, $time) {
            $attendance = $this->repository->getTodayAttendance($employeeId, $date);
            $this->repository->update($attendance, ['break_start' => $time]);
            return $attendance->fresh();
        });
    }

    public function endBreak(int $employeeId, string $date, string $time): Attendance
    {
        return DB::transaction(function () use ($employeeId, $date, $time) {
            $attendance = $this->repository->getTodayAttendance($employeeId, $date);
            $this->repository->update($attendance, ['break_end' => $time]);
            return $attendance->fresh();
        });
    }

    public function getTodayAttendance(int $employeeId, string $date): ?Attendance
    {
        return $this->repository->getTodayAttendance($employeeId, $date);
    }
}
