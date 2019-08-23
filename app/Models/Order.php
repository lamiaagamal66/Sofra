<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders'; 
    public $timestamps = true;
    protected $fillable = array('note', 'address', 'payment_type_id', 'cost', 'delivery_cost', 'total_cost', 'client_id', 'restaurant_id', 'status', 'commission','net');

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function payment_type()
    {
        return $this->belongsTo('App\Models\PaymentType');
    }

}