<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecuperatePassword extends Model
{
    protected $fillable = [
        'user_id', 'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
