<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    // fillable
    protected $fillable = [
        'code',
        'type',
        'value',
        'status',
        'expiry_date',
        'limit',
    ];
}
