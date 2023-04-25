<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleChangeRequest extends Model
{
    protected $table = 'role_change_requests';
    protected $fillable = [
        'user_id',
        'categories',
        'resume',
        'identity_document'
    ];

}
