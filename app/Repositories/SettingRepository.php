<?php

namespace App\Repositories;

use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends BaseRepository
{
    protected function model(): string
    {
        return CompanySetting::class;
    }

    public function getAllByCompany(int $companyId, array $relations = []): Collection
    {
        return $this->model->byCompany($companyId)->get();
    }

    public function setSetting(int $companyId, string $key, mixed $value): CompanySetting
    {
        return $this->model->updateOrCreate(
            ['company_id' => $companyId, 'key' => $key],
            ['value' => $value]
        );
    }
}
