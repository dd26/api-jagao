<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //fillable
    protected $fillable = [
        'name',
        'number',
        'expiration_date',
        'cvv',
        'postal_code',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
