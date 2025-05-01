<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmResponse extends Model
{
    protected $fillable = ['campaigns_leads_users_id', 'response', 'url', 'inputs', 'method', 'lead_id', 'ping_id', 'campaign_id', 'httpheader', 'time'];
}
