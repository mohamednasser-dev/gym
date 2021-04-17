<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coach_media extends Model
{
    protected $fillable = ['image', 'type','coach_id'];
}
