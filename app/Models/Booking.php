<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $guarded = false;


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function booking_tourists()
    {
        return $this->hasMany(BookingTourists::class, 'booking_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
