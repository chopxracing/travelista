<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';
    protected $guarded = false;
    use Filterable;


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function amenities()
    {
        return $this->belongsToMany(
            HotelAmenity::class,
            'hotel_amenity_arrays',
            'hotel_id',
            'hotel_amenity_id'
        );
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function room_type()
    {
        return $this->hasMany(RoomType::class, 'hotel_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'hotel_id');
    }
}
