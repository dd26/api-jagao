<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'userName', 'birthDate', 'identification', 'address', 'city', 'country', "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}