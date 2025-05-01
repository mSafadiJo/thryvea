<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectUsers extends Model
{
    protected $fillable = [
        'user_first_name', 'user_last_name', 'username', 'email', 'user_owner', 'user_visibility',
        'zip_code_id', 'user_business_name', 'user_phone_number', 'user_mobile_number',
        'user_type', 'sdr_claimer', 'sales_claimer', 'service'
    ];
}
