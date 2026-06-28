<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentService extends BaseService
{
    protected function repository(): string
    {
        return DepartmentRepository::class;
    }
}
