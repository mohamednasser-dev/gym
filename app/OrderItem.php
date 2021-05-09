<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'option_id', 'count', 'price_before_offer', 'final_price', 'delivered_at', 'refunded_at',
        'status'    // 1 => in progress
        // 2 => delivery service
        // 3 => delivered
        // 4 => canceled from admin
        // 5 => refund request
        // 6 => refund accepted
        // 7 => refund refused
        // 8 => refunded received
        // 9 => canceled from admin
    ];

    protected $dates = ['delivered_at', 'refunded_at'];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function product_data() {
        return $this->belongsTo('App\Product', 'product_id')->select('id', 'title_' . session('api_lang') . ' as title')->with('mainImage');
    }

    public function product_with_select()
    {
        return $this->belongsTo('App\Product', 'product_id')->select('title_ar as product_name', 'type', 'final_price', 'price_before_offer', 'id', 'offer', 'offer_percentage');
    }

    public function product_with_select_ar()
    {
        return $this->belongsTo('App\Product', 'product_id')->select('title_ar as product_name', 'type', 'final_price', 'price_before_offer', 'id', 'offer', 'offer_percentage');
    }

    public function product_with_select_en()
    {
        return $this->belongsTo('App\Product', 'product_id')->select('title_en as product_name', 'type', 'final_price', 'price_before_offer', 'id', 'offer', 'offer_percentage');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function order_data()
    {
        return $this->belongsTo('App\Order', 'order_id')->select('id', 'order_number as sub_order_number', 'payment_method', 'main_id', 'user_id')->with(['main_order_data', 'user_data']);
    }

    public function multiOption()
    {
        return $this->belongsTo('App\ProductMultiOption', 'option_id');
    }

    public function size() {
        return $this->hasOne('App\SizeDetail', 'order_id');
    }

    public function refund() {
        return $this->hasOne('App\Retrieve', 'item_id');
    }
}
