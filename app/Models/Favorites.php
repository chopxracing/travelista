<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = 'favorites';
    protected $guarded = false;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
