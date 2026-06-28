<?php

namespace App\Services;

use App\Repositories\AnnouncementRepository;

class AnnouncementService extends BaseService
{
    protected function repository(): string
    {
        return AnnouncementRepository::class;
    }
}
