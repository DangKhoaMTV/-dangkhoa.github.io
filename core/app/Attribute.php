<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $timestamps = false;

    public function product_attributes() {
        return $this->hasMany('App\ProductAttribute')->orderBy('serial_number', 'ASC');
    }

}
