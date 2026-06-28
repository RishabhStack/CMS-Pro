<?php

namespace App\Repositories;

use App\Models\SalaryComponent;

class SalaryComponentRepository extends BaseRepository
{
    protected function model(): string
    {
        return SalaryComponent::class;
    }
}
