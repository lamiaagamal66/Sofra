<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model 
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'mobile', 'image', 'region_id', 'password', 'api_token', 'pin_code');

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

}