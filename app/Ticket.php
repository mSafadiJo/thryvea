<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'campaigns_leads_users_id', 'reason_lead_returned_id',
        'ticket_message', 'ticket_status', 'ticket_type', 'reject_text',
        'campaign_id', 'campaigns_leads_users_type_bid'
    ];
}
