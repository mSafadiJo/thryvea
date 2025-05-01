<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinAsaPro extends Model
{
    protected $fillable = [
        'full_name', 'business_name', 'phone_number','email', 'note',
        'google_ts', 'google_c','google_k', 'google_g', 'source', 'ip_address',
        'resource', 'city', 'zip_code', 'services'
    ];
}
