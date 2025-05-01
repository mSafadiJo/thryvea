<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingPlatform extends Model
{

    public function LeadTraffic() {
        return $this->hasMany('App\LeadTrafficSources');
    }

    protected $fillable = [
        'name', 'lead_source'
    ];
}
