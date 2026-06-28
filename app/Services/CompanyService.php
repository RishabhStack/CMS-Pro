<?php

namespace App\Services;

use App\Repositories\CompanyRepository;

class CompanyService extends BaseService
{
    protected function repository(): string
    {
        return CompanyRepository::class;
    }
}
