<?php

namespace App\Http\Controllers;

use App\TotalAmount;
use App\Transaction;
use App\ZipCodesList;
use Illuminate\Http\Request;
use App\Payment\Cashier;
use Illuminate\Support\Facades\Auth;
use Session;

class payment2checkoutbuyers extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer']);
    }

    public function pay2checkout(Request $request){
        $paymentCardDetails = ZipCodesList::where('zip_code_list', $request->zipcode)
            ->join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->first([
                'states.state_code', 'cities.city_name'
            ]);

        $city_name_data = $paymentCardDetails['city_name'];
        $city_name_data = explode("=>",$city_name_data);
        $city_name = strtolower($city_name_data[0]);
        $city_name = ucfirst($city_name);

        $state_code = strtoupper($paymentCardDetails['state_code']);

        $address = array(
            "name" => $request->full_name,
            "addrLine1" => $request->addrLine1,
            "city" => $city_name,
            "state" => $state_code,
            "zipCode" => $request->zipcode,
            "country" => 'USA',
            "email" => Auth::user()->email,
            "phoneNumber" => Auth::user()->user_phone_number
        );

        $info = array(
            "sellerId" => config('services.2checkout.2CHECKOUT_SELLER_ID', ''),
            "merchantOrderId" => rand(1000,100000),
            "token" => $request->token,
            "currency" => 'USD',
            "total" => $request->value,
            'billingAddr' => $address,
            'shippingAddr' => $address
        );

        $res = Cashier::pay($info);

        if(!$res['success']){
            Session::flash('error', 'Unable to pay, please try again');
//            return response(['message'=>'Unable to pay, please try again'],400);
//            return response(['message'=>$res['message']],400);
            if( !empty($request->payment_id) ) {
                return redirect()->route('Transaction.Value.Create', $request->value);
            } else {
                return back();
            }
        }

        Session::flash('success', 'Payment successful!');

        if( !empty($request->payment_id) ){

            $payment_id = $request->payment_id;

            $transaction = new Transaction();

            $transaction->user_id = Auth::user()->id;
            $transaction->transactions_value = $request['value'];
            $transaction->payment_id = $payment_id;
            $transaction->transactions_comments = 'Credit Accumulation';

            $transaction->save();

            $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

            if( empty($totalAmmount) ){
                $addtotalAmmount = new TotalAmount();

                $addtotalAmmount->user_id = Auth::user()->id;
                $addtotalAmmount->total_amounts_value = $request['value'];

                $addtotalAmmount->save();
            } else {
                $total = $request['value'] + $totalAmmount['total_amounts_value'];
                $updatetotalAmmount = TotalAmount::where('user_id', Auth::user()->id)
                    ->update([ 'total_amounts_value' => $total ]);
            }
        }

        if( !empty($request->payment_id) ) {
            return redirect()->route('Transaction.Value.Create', $request->value);
        } else {
            return back();
        }
    }
}
