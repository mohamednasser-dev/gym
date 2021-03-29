<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['user_id', 'text', 'rate', 'type' , 'admin_approval','order_id'];
}
