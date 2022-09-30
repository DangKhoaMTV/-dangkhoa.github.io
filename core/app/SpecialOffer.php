<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends Model
{
    protected $fillable = ['title','assoc_id', 'content', 'image', 'btn_url', 'btn_text', 'feature', 'language_id','serial_number'];
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo('App\Language');
    }
}
