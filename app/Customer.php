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

    // relacion con city_id para obtener el nombre de la ciudad
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

}
