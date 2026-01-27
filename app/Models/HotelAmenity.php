<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelAmenity extends Model
{
    protected $table = 'hotel_amenities';
    protected $guarded = false;

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'hotel_id');
    }
}
