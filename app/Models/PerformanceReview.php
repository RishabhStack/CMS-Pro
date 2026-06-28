<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class PerformanceReview extends Model
{
    use SoftDeletes, Auditable, HasFactory, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'employee_id',
        'reviewer_id',
        'review_period',
        'start_date',
        'end_date',
        'due_date',
        'overall_rating',
        'status',
        'employee_notes',
        'reviewer_notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'due_date' => 'date',
        'overall_rating' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function goals()
    {
        return $this->hasMany(PerformanceGoal::class, 'review_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(PerformanceFeedback::class, 'review_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
