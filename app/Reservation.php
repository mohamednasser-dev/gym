<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['booking_id','expire_date','price','user_id','deleted','status','payment','type'];

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Booking() {
        return $this->belongsTo('App\Hole_booking', 'booking_id');
    }
    public function Booking_coach() {
        return $this->belongsTo('App\Coach_booking', 'booking_id');
    }
}
