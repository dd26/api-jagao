<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterRequestService extends Model
{
    // fillable
    protected $fillable = [
        'user_id',
        'address_id',
        'cvv',
        'card_id',
        'right_now',
        'category_id',
        'category_name',
        'observations',
        'state',
        'discount',
        'discount_amount',
        'date_request'
    ];

    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    public function detailRequestService()
    {
        return $this->hasMany('App\DetailRequestService');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
