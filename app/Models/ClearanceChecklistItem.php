<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ClearanceChecklistItem extends Model
{
    use Auditable;
    protected $fillable = [
        'resignation_id',
        'department',
        'item',
        'assigned_to',
        'is_cleared',
        'cleared_by',
        'cleared_at',
        'notes',
    ];

    protected $casts = [
        'is_cleared' => 'bool',
        'cleared_at' => 'datetime',
    ];

    public function resignation()
    {
        return $this->belongsTo(Resignation::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function clearer()
    {
        return $this->belongsTo(User::class, 'cleared_by');
    }
}
