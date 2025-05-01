<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
    use Notifiable;

    public function zipCode()
    {
        return $this->belongsTo(Zip_code::class, 'zip_code_id', 'zip_code_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function payment(){
        return $this->belongsTo('App\Payment');
    }

    public function totalAmounts(){
        return $this->hasMany('App\TotalAmount');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_first_name', 'user_last_name', 'username', 'email', 'user_owner', 'num_of_login', 'password',
        'zip_code_id', 'user_business_name', 'user_phone_number', 'user_mobile_number', 'role_id', 'account_type',
        'permission_users', 'user_type', 'contracts', 'documents', 'hash_phone_number', 'hash_mobile_number',
        'sales_id', 'sdr_id', 'acc_manger_id', 'adminIcon1', 'adminIcon2', 'adminIcon3', 'profit_percentage',
        'user_auto_pay_status', 'user_auto_pay_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
