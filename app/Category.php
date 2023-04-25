<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return asset("storage/categories/{$this->id}/{$this->id}.jpeg");
    }

    public function subcategories()
    {
        return $this->hasMany('App\SubCategory');
    }

}
