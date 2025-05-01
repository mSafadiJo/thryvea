<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitorLead extends Model
{
    protected $fillable = [
        'user_sessions', 'lead_serverDomain', 'lead_ipaddress', 'lead_FullUrl',
        'lead_aboutUserBrowser', 'lead_browser_name', 'lead_website', 'google_ts', 'google_c', 'google_g',
        'google_k', 'token', 'visitor_id', 'is_lead', 'is_exit_popup', 'is_second_service'
    ];
}
