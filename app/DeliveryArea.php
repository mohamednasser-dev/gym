<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $fillable = ['area_id', 'delivery_cost', 'store_id', 'arrival_from', 'arrival_to'];

    public function area() {
        return $this->belongsTo('App\Area', 'area_id');
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }
}