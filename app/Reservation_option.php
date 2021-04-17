<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation_option extends Model
{
    protected $fillable = ['type_id','goal_id','reservation_id'];

    public function Type() {
        return $this->belongsTo('App\Reservation_type', 'type_id');
    }

    public function Goal() {
        return $this->belongsTo('App\Reservation_goal', 'goal_id');
    }

    public function Reservation() {
        return $this->belongsTo('App\Reservation', 'reservation_id');
    }

}
