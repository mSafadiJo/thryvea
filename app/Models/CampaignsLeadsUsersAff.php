<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignsLeadsUsersAff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'lead_id', 'lead_id_token_md', 'is_returned', 'campaigns_leads_users_bid', 'is_recorded',
        'call_center', 'agent_name', 'buyer_lead_note', 'vendor_id_aff'
    ];
}
