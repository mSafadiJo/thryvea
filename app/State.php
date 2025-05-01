<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function cities(){
        return $this->hasMany('App\City');
    }
    public function counties(){
        return $this->hasMany('App\County');
    }

    public function zipCodesList(){
        return $this->belongsTo('App\ZipCodesList');
    }

    public function leadsCustomer(){
        return $this->hasMany('App\LeadsCustomer');
    }
    
    public function campaigns(){
        return $this->belongsToMany('App\Campaign', 'state_campaigns');
    }
}
