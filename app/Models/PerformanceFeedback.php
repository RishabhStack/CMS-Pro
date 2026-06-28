<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PerformanceFeedback extends Model
{
    use Auditable;
    protected $table = 'performance_feedbacks';

    protected $fillable = [
        'review_id',
        'reviewer_id',
        'rating',
        'comment',
        'is_anonymous',
        'submitted_at',
    ];

    protected $casts = [
        'is_anonymous' => 'bool',
        'submitted_at' => 'datetime',
    ];

    public function review()
    {
        return $this->belongsTo(PerformanceReview::class, 'review_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
