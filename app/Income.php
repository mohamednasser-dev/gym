<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['reservation_id', 'type','price','user_id','booking_id','payment'];

}
