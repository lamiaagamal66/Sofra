<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model 
{

    protected $table = 'restaurants';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'mobile', 'password', 'region_id', 'minimum_cost', 'delivery_cost', 'whatsapp', 'image', 'api_token', 'is_active', 'status', 'pin_code');

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }


    public function getTotalCommissionsAttribute($value)
    {
        $commissions = $this->orders()->where('status','delivered')->sum('commission');

        return $commissions;
    }

    public function getTotalPaymentsAttribute($value)
    {
        $payments = $this->payments()->sum('amount');

        return $payments;
    }

}