<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public function state(){
        return $this->belongsTo('App\State');
    }

    public function zipCodesList(){
        return $this->belongsTo('App\ZipCodesList');
    }

    public function leadsCustomer(){
        return $this->hasMany('App\LeadsCustomer');
    }
    public function campaigns(){
        return $this->belongsToMany('App\Campaign', 'county__campaigns');
    }
}
