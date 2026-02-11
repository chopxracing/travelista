<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $guarded = false;

    public function payment()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
