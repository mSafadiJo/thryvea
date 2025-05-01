<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLeads extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'address', 'email', 'phone_number', 'service_id',
        'state_id', 'county_id', 'city_id', 'zipcode_id',
        'lead_serverDomain', 'lead_timeInBrowseData', 'lead_ipaddress', 'lead_FullUrl', 'lead_aboutUserBrowser',
        'lead_browser_name', 'lead_website',
        'tcpa_compliant', 'tcpa_consent_text', 'trusted_form', 'universal_leadid',
        'google_ts','google_c','google_g','google_k', 'google_gclid', 'token', 'visitor_id',
        'is_duplicate_lead', 'status', 'is_verified_phone'
    ];
}
