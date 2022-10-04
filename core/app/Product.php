<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'language_id',
        'stock',
        'sku',
        'category_id',
        'tags',
        'feature_image',
        'summary',
        'description',
        'current_price',
        'previous_price',
        'rating',
        'status',
        'is_home',
        'meta_keywords',
        'meta_description',
        'type',
        'download_link',
        'download_file'
    ];

    public function category() {
        return $this->hasOne('App\Pcategory','id','category_id');
    }

    public function product_images() {
        return $this->hasMany('App\ProductImage');
    }

    public function product_attributes() {
        return $this->hasMany('App\ProductAttribute')->orderBy('serial_number', 'ASC');
    }

    public function language() {
        return $this->belongsTo('App\Language');
    }
}
