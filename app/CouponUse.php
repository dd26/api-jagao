<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponUse extends Model
{
    protected $table = 'coupon_uses';
    protected $fillable = ['coupon_id', 'user_id'];
    public function coupon()
    {
        return $this->belongsTo('App\Coupon');
    }
}
