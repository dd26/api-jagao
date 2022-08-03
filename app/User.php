<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // relacion con role_id
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    // relacion con user_id specialist
    public function specialist()
    {
        return $this->hasOne(Specialist::class, 'user_id', 'id');
    }

    // relacion con user_id customer
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }
}
