<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function users(){
        return $this->hasMany('App\User');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }

    protected $fillable = [
        'payment_full_name', 'payment_visa_number', 'payment_visa_type', 'payment_cvv', 'payment_exp_month',
        'payment_exp_year', 'payment_address', 'payment_zip_code', 'payment_primary',
        'user_id', 'payment_visibility'
    ];


}
