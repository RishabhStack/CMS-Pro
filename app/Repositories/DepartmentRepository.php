<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository extends BaseRepository
{
    protected function model(): string
    {
        return Department::class;
    }
}
