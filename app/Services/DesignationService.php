<?php

namespace App\Services;

use App\Repositories\DesignationRepository;

class DesignationService extends BaseService
{
    protected function repository(): string
    {
        return DesignationRepository::class;
    }
}
