<?php

namespace App\Services;

use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService extends BaseService
{
    protected function repository(): string
    {
        return DocumentRepository::class;
    }

    public function uploadDocument(array $data, UploadedFile $file): Document
    {
        return DB::transaction(function () use ($data, $file) {
            $path = $file->store('documents', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
            return $this->repository->create($data);
        });
    }
}
