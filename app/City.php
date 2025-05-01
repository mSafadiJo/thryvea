<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function state(){
        return $this->belongsTo('App\State');
    }

    public function zipCodesList(){
        return $this->belongsTo('App\ZipCodesList');
    }

    public function zip_cods(){
        return $this->hasMany('App\Zip_code');
    }

    public function leadsCustomer(){
        return $this->hasMany('App\LeadsCustomer');
    }

    public function campaigns(){
        return $this->belongsToMany('App\Campaign', 'city__campaigns');
    }
}
