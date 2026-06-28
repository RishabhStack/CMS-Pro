<?php

namespace App\Services;

use App\Repositories\HolidayRepository;

class HolidayService extends BaseService
{
    protected function repository(): string
    {
        return HolidayRepository::class;
    }

    public function getYearHolidays(int $companyId, int $year)
    {
        return $this->repository->getYearHolidays($companyId, $year);
    }
}
