<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public $timestamps = false;
    public function attribute() {
        return $this->hasOne('App\Attribute','id','attribute_id')->orderBy('serial_number', 'ASC');
    }
}
