<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'image',
        'fcm_token',
        'status',
        'deleted',
        'available',
        'famous',
        'verified',
        'about_coach',
        'time_from',
        'time_to',
    ];
}
