<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use Filterable;
    protected $table = 'tours';
    protected $guarded = false;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function tour_operator()
    {
        return $this->belongsTo(TourOperator::class, 'tour_operator_id');
    }

    public function tour_type()
    {
        return $this->belongsTo(TourType::class, 'tour_type_id');
    }
}
