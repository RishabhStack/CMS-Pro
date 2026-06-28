<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class TravelItinerary extends Model
{
    use Auditable;
    protected $fillable = [
        'travel_request_id',
        'date',
        'time',
        'activity',
        'location',
        'details',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function request()
    {
        return $this->belongsTo(TravelRequest::class, 'travel_request_id');
    }
}
