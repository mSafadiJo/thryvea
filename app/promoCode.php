<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class promoCode extends Model
{
    protected $fillable = [
        'promo_code', 'promo_code_from_date', 'promo_code_to_date', 'promo_code_value'
    ];
}
