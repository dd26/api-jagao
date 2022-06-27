<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // fillable
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'viewed',
        'master_request_service_id'
    ];

    public function master_request_service()
    {
        return $this->belongsTo('App\MasterRequestService');
    }
}
