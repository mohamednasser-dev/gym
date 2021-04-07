<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation_type extends Model
{
    protected $fillable = ['title_ar', 'title_en','deleted'];
}
