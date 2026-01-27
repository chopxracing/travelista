<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    protected $guarded = false;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }
}
