<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'parent_id'
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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function isParent()
    {
        return ($this->childrens->count() > 0);
    }

    public function isChild()
    {
        return $this->parent_id !== null;
    }
}
