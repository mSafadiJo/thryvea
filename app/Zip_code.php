<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zip_code extends Model
{
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function zipCodeList()
    {
        return $this->belongsTo(ZipCodesList::class, 'zip_code', 'zip_code_list_id');
    }

    public function users(){
        return $this->hasMany('App\User');
    }

    protected $fillable = [
        'city_id', 'zip_code', 'zip_code_description', 'street_name', 'state_id'
    ];
}
