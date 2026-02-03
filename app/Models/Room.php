<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $guarded = false;


    public function room_status()
    {
        return $this->hasOne(RoomStatus::class, 'id', 'room_status_id');
    }

    public function room_type()
    {
        return $this->hasOne(RoomType::class, 'id', 'room_type_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
