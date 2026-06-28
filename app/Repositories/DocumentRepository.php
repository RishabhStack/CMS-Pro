<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository extends BaseRepository
{
    protected function model(): string
    {
        return Document::class;
    }
}
