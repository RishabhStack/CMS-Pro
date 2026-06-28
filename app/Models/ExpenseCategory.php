<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\HasCompanyTrait;

class ExpenseCategory extends Model
{
    use HasCompanyTrait, Auditable;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'max_amount',
        'status',
    ];

    protected $casts = [
        'max_amount' => 'decimal:2',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
