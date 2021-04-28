<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Coach extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'name_en',
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
        'rate',
        'sort',
        'age',
        'exp',
        'user_id',
        'phone',
        'about_coach_en',
        'story'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function Rates() {
        return $this->hasMany('App\Rate', 'order_id')->where('type','coach')->where('admin_approval',1);
    }
}
