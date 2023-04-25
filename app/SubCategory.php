<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'subcategories';

    protected $fillable = [
        'name', 'category_id', 'description', 'has_document'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return asset("storage/subcategories/{$this->id}/{$this->id}.jpeg");
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
