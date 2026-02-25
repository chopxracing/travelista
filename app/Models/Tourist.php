<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    protected $table = 'tourists';
    protected $guarded = false;


    public function country()
    {
        return $this->belongsTo(Country::class, 'passport_country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
