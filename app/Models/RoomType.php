<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = 'room_types';
    protected $guarded = false;

    public function hotel()
    {
        return $this->hasOne(Hotel::class, 'id', 'hotel_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(RoomAmenity::class,
        'room_amenity_arrays', 'room_type_id', 'room_amenity_id');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
