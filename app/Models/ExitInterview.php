<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ExitInterview extends Model
{
    use Auditable;
    protected $fillable = [
        'resignation_id',
        'interview_date',
        'interviewed_by',
        'overall_experience',
        'reason_for_leaving',
        'feedback_on_company',
        'would_recommend',
        'suggestions',
        'created_by',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'would_recommend' => 'bool',
    ];

    public function resignation()
    {
        return $this->belongsTo(Resignation::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewed_by');
    }
}
