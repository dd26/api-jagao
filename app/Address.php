<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'city_id',
        'address',
        'postal_code'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
