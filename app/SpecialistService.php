<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialistService extends Model
{
    // fillable
    protected $fillable = [
        'category_id',
        'category_name',
        'subcategory_id',
        'subcategory_name',
        'user_id',
        'price',
        'has_document',
    ];
}
