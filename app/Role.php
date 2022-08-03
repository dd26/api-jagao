<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    // table
    protected $table = 'roles';


    // relacion con los permisos
    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'id', 'role_id');
    }

}
