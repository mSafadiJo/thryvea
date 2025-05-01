<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignType extends Model
{
    protected $fillable = [
        'campaign_types_name', 'campaign_types_logo', 'campaign_types_description', 'buyers_status'
    ];
}
