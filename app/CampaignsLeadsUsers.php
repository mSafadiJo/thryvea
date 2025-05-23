<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignsLeadsUsers extends Model
{
    protected $table = 'campaigns_leads_users'; // Optional if using default naming
    protected $primaryKey = 'campaigns_leads_users_id';
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'user_id', 'campaign_id', 'lead_id', 'lead_id_token_md', 'is_returned', 'campaigns_leads_users_bid', 'is_recorded',
        'call_center', 'agent_name', 'buyer_lead_note'
    ];
}
