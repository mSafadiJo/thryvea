<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcode_Campaign extends Model
{
    public function campaign(){
        return $this->belongsTo('App\Campaign');
    }

    protected $fillable = [
        'campaign_id', 'zipcode_campaigns', 'zipcode_campaigns_active'
    ];
}
