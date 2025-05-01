<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZipCodesList extends Model
{

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
    public function state(){
        return $this->belongsTo('App\State');
    }

    public function county(){
        return $this->belongsTo('App\County');
    }
    public function address(){
        return $this->hasMany('App\Zip_code');
    }

    public function leadsCustomer(){
        return $this->hasMany('App\LeadsCustomer');
    }

    protected $fillable = [
        'zip_code_list', 'zip_code_list_TimeZone', 'state_id', 'county_id', 'city_id'
    ];
}
