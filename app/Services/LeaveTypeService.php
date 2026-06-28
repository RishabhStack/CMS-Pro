<?php

namespace App\Services;

use App\Repositories\LeaveTypeRepository;

class LeaveTypeService extends BaseService
{
    protected function repository(): string
    {
        return LeaveTypeRepository::class;
    }
}
