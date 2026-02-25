<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTourists extends Model
{
    protected $table = 'booking_tourists';
    protected $guarded = false;

    public function tourist()
    {
        return $this->belongsTo(Tourist::class, 'tourist_id');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
