<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Shop extends Authenticatable implements JWTSubject
{
    protected $fillable = ['logo','cover','name_ar','name_en','phone','email','password','fcm_token','famous','status','min_order_cost'];

    protected $appends = ['name'];
    public function getNameAttribute()
    {
        if ($locale = \app()->getLocale() == "ar") {
            return $this->name_ar ;
        } else {
            return $this->name_en;
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
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

    public function products() {
        return $this->hasMany('App\Product', 'store_id');
    }

}
