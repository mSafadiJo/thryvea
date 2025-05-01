<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function payment(){
        return $this->belongsTo('App\Payment');
    }

    protected $fillable = [
        'user_id', 'transactions_value', 'payment_id', 'transaction_status', 'transaction_paypall', 'is_seller', 'request_method',
        'transactions_comments', 'admin_id', 'transactionauthid', 'transactions_response', 'is_refund', 'accept', 'payment_type'
    ];
}
