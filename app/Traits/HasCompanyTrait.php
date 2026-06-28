<?php

namespace App\Traits;

use App\Models\Company;

trait HasCompanyTrait
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
