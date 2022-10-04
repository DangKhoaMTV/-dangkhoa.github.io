<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  public $timestamps = false;

  public function scategory()
  {
    return $this->belongsTo('App\Scategory');
  }

  public function portfolios()
  {
    return $this->hasMany('App\Portfolio');
  }

  public function service_attributes() {
      return $this->hasMany('App\ServiceAttribute')->orderBy('serial_number', 'ASC');
  }

  public function service_images() {
      return $this->hasMany('App\ServiceImage');
  }

  public function language()
  {
    return $this->belongsTo('App\Language');
  }
}
