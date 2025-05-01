<?php

namespace App\Services\User;

use App\User;
use App\Zip_code;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\BuyersClaim;
use App\AccessLog;
use Illuminate\Support\Facades\Hash;

class UserUpdateService
{

    public function updateUser(array $data)
    {
        return User::where('id', Auth::user()->id)
            ->update([
                'user_first_name' => $data['firstname'],
                'user_last_name' => $data['lastname'],
                'username' => ucwords($data['firstname'] . ' ' . $data['lastname']),
                'email' => $data['email'],
                'user_owner' => $data['owner'],
                'user_business_name' => $data['businessname'],
                'user_phone_number' => $data['phonenumber'],
                'user_mobile_number' => $data['mobilenumber']
            ]);
    }


    public function updateUserPassword($password){
        return User::where('id', Auth::user()->id)
            ->update([
                'password' => Hash::make($password)
            ]);
    }

    public function updateBuyersClaim(array $data)
    {
        $name = ucwords($data['firstname'] . ' ' . $data['lastname']);

        BuyersClaim::where('admin_id', Auth::user()->id)
            ->update(['admin_name' => $name]);

        BuyersClaim::where('confirmed_by_id', Auth::user()->id)
            ->update(['confirmed_by_name' => $name]);
    }

    public function updatePaymentTypeAdmin(array $data)
    {
        $name = ucwords($data['firstname'] . ' ' . $data['lastname']);

        DB::table('user_payment_type_admin')
            ->where('admin_id', Auth::user()->id)
            ->update(['admin_name' => $name]);

        DB::table('user_payment_type_admin')
            ->where('confirmed_by_id', Auth::user()->id)
            ->update(['confirmed_by_name' => $name]);
    }

}
