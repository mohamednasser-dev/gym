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
        'story',
    ];

    public function Rates() {
        return $this->hasMany('App\Rate', 'order_id')->where('type','hall')->where('admin_approval',1);
    }

    protected $appends = ['name','about'];
    public function getNameAttribute()
    {
        if ($locale = \app()->getLocale() == "ar") {
            return $this->name_ar ;
        } else {
            return $this->name_en;
        }
    }
    public function getAboutAttribute()
    {
        if ($locale = \app()->getLocale() == "ar") {
            return $this->about_hole ;
        } else {
            return $this->about_hole_en;
        }
    }
}
