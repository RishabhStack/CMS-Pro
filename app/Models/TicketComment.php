<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class TicketComment extends Model
{
    use Auditable;
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'is_internal',
        'created_by',
    ];

    protected $casts = [
        'is_internal' => 'bool',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
