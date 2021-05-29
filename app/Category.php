<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['image', 'title_en', 'title_ar','deleted'];

    public function products() {
        return $this->hasMany('App\Product', 'category_id');
    }

    public function options() {
        return $this->belongsToMany('App\Option', 'options_categories', 'category_id', 'option_id');
    }

    public function optionsWithValues() {
        return $this->options()->with('values');
    }
}
