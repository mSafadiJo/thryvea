<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmResponsePing extends Model
{
    use HasFactory;

    protected $fillable = ['campaigns_leads_users_id', 'response', 'url', 'inputs', 'method', 'lead_id', 'ping_id', 'campaign_id', 'httpheader', 'time'];
}
