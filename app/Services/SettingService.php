<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Cache;

class SettingService extends BaseService
{
    protected function repository(): string
    {
        return SettingRepository::class;
    }

    public function getSettings(int $companyId): array
    {
        return Cache::remember("company_settings_{$companyId}", 3600, function () use ($companyId) {
            return $this->repository->getAllByCompany($companyId)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public function updateSetting(int $companyId, string $key, mixed $value): void
    {
        $this->repository->setSetting($companyId, $key, $value);
        $this->clearCache($companyId);
    }

    public function clearCache(int $companyId): void
    {
        Cache::forget("company_settings_{$companyId}");
    }
}
