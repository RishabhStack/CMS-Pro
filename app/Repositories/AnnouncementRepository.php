<?php

namespace App\Repositories;

use App\Models\Announcement;

class AnnouncementRepository extends BaseRepository
{
    protected function model(): string
    {
        return Announcement::class;
    }
}
