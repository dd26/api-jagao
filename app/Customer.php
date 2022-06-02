<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'userName', 'birthDate', 'identification', 'address', 'city',
        'country', "user_id", "discountCoupon", "phone", "zip_code"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
