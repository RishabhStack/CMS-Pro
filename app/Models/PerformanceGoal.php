<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PerformanceGoal extends Model
{
    use Auditable;
    protected $fillable = [
        'review_id',
        'title',
        'description',
        'category',
        'target_value',
        'achieved_value',
        'weight',
        'self_rating',
        'manager_rating',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function review()
    {
        return $this->belongsTo(PerformanceReview::class, 'review_id');
    }
}
