<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;

class Expense extends Model
{
    use SoftDeletes, Auditable, HasCompanyTrait;

    protected $fillable = [
        'company_id',
        'employee_id',
        'category_id',
        'expense_date',
        'amount',
        'description',
        'receipt_path',
        'receipt_original_name',
        'status',
        'approved_by',
        'approved_at',
        'paid_at',
        'rejection_reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
