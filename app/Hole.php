<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hole extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'phone_verified_at',
        'password',
        'gender',
        'logo',
        'remember_token',
        'status',
        'deleted',
        'cover',
        'about_hole',
    ];
}
