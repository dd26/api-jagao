<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'subcategories';

    protected $fillable = [
        'name', 'category_id', 'description', 'has_document'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
