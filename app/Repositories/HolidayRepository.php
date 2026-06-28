<?php

namespace App\Repositories;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayRepository extends BaseRepository
{
    protected function model(): string
    {
        return Holiday::class;
    }

    public function getYearHolidays(int $companyId, int $year): Collection
    {
        return $this->model->byCompany($companyId)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();
    }
}
