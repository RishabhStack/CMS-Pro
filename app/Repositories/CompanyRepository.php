<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository extends BaseRepository
{
    protected function model(): string
    {
        return Company::class;
    }
}
