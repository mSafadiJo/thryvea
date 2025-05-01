<?php

namespace App\Http\Controllers;

use App\TotalAmount;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentPayPalButtonsConroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $client_id = config('services.paypal.PAYPAL_CLIENT_ID', '');

        return view('Buyers.Payment.paypalbuttons')
            ->with('client_id', $client_id);
    }

    public function storTransaction(Request $request){

        $user_id = Auth::user()->id;
        if( !empty($request->user_id) ){
            $user_id = $request->user_id;
        }

        $transaction = new Transaction();

        $transaction->user_id = $user_id;
        $transaction->transactions_value = $request['value'];
        $transaction->transactions_comments = 'Credit Accumulation';

        $transaction->save();

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $request['value'];

            $addtotalAmmount->save();
        } else {
            $total = $request['value'] + $totalAmmount['total_amounts_value'];
            $updatetotalAmmount = TotalAmount::where('user_id', $user_id)
                ->update([ 'total_amounts_value' => $total ]);
        }

        return response()->json('', 200);
    }
}
