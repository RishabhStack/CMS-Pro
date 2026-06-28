<?php

namespace App\Services;

use App\Repositories\SalaryComponentRepository;

class SalaryComponentService extends BaseService
{
    protected function repository(): string
    {
        return SalaryComponentRepository::class;
    }
}
