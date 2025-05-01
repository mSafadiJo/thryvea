<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadTrafficSources extends Model
{

    public function Platform() {
        return $this->belongsTo('App\MarketingPlatform');
    }

    protected $fillable = [
        'name', 'marketing_platform_id'
    ];
}
