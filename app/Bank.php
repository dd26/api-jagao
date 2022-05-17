<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //fillable
    protected $fillable = [
        'exterior_bank_id',
        'account_number',
        'account_type',
        'full_name',
        'route_number',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
