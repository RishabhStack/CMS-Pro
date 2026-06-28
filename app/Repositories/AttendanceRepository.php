<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository extends BaseRepository
{
    protected function model(): string
    {
        return Attendance::class;
    }

    public function getTodayAttendance(int $employeeId, string $date): ?Attendance
    {
        return $this->model->where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();
    }
}
