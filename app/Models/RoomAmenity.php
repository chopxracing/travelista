<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAmenity extends Model
{
    protected $table = 'room_amenities';
    protected $guarded = false;


    public function room_types()
    {
        return $this->belongsToMany(RoomType::class,
        'room_amenity_arrays', 'room_type_id', 'room_amenity_id');
    }
}
