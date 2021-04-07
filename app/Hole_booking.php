<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hole_booking extends Model
{
    protected $fillable = ['name_ar','name_en','title_ar', 'title_en','price','discount','is_discount','discount_price','deleted','hole_id','months_num'];

    public function Details() {
        return $this->hasMany('App\Hole_booking_detail', 'booking_id','id')->select('id','name','booking_id');
    }
}
