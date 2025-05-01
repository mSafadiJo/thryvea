<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyersClaim extends Model
{
    protected $fillable = [
        'buyer_id', 'admin_id', 'admin_name', 'claim_type', 'is_claim', 'confirmed_by_name', 'confirmed_by_id', 'confirmed_at', 'type'
    ];

}
