<?php

namespace App\Repositories;

use App\Models\Designation;

class DesignationRepository extends BaseRepository
{
    protected function model(): string
    {
        return Designation::class;
    }
}
