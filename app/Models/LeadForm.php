<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadForm extends Model
{
    use HasFactory;
    public $table = "lead_form";

    protected $fillable = [
        'id','lead_fname','lead_lname','lead_email','lead_phone_number','lead_zipcode','offer','preferred_contact_method','api_version','form_id','campaign_id',
        'is_test','gcl_id','adgroup_id','creative_id','created_at','updated_at','traffic_source','city','county','state','address','trusted_form','leadId'
    ];
}
