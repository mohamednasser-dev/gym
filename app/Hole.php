<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hole extends Authenticatable
{
    protected $fillable = [
        'name',
        'name_en',
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
        'famous',
        'started_price',
        'about_hole',
        'about_hole_en',
        'rate',
        'sort',
    ];

    public function Rates() {
        return $this->hasMany('App\Rate', 'order_id')->where('type','hall')->where('admin_approval',1);
    }
}
