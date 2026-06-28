<?php

namespace App\Repositories;

use App\Models\LeaveType;

class LeaveTypeRepository extends BaseRepository
{
    protected function model(): string
    {
        return LeaveType::class;
    }
}
