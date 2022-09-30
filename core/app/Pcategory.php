<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pcategory extends Model
{
    protected $fillable = ['name','image','language_id','status','slug','is_home'];

    public function products() {
        return $this->hasMany('App\Product','category_id','id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Pcategory');
    }

    public function language() {
        return $this->belongsTo('App\Language');
    }
}
