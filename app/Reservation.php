<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name','age','length','weight','type_id','goal_id','other','booking_id','expire_date','price','user_id','deleted','status','payment'];

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Booking() {
        return $this->belongsTo('App\Hole_booking', 'booking_id');
    }
    public function Type() {
        return $this->belongsTo('App\Reservation_type', 'type_id');
    }

    public function Goal() {
        return $this->belongsTo('App\Reservation_goal', 'goal_id');
    }
}
