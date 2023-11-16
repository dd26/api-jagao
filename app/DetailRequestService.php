<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailRequestService extends Model
{
    // fillable
    protected $fillable = [
        'master_request_service_id',
        'service_id',
        'service_name',
        'service_description',
        'service_price',
        'comision_app',
        'comision_espcialist',
        'comision_is_porcentage',
        'quantity'
    ];

}
