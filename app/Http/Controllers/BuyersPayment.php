<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Payment;
use App\TotalAmount;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Slack;

class BuyersPayment extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer'], ['except' => ['getPaymentDetailsById']]);
    }

    public function index(){
        $payments = Payment::where('payment_visibility', 1)
            ->where('user_id', Auth::user()->id)->get()->All();

        $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

        return view('Buyers.Payment.listOfPayment')
            ->with('totalAmmount', $totalAmmount)
            ->with('payments', $payments);
    }

    public function AddForm(){
        return view('Buyers.Payment.AddPayment')->with('errormsg', "");
    }

    public function storePayment(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'visanumber' => 'required|string',
            'visatype' => 'required|string',
            'cvv' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string'
        ]);

        $zipcodes = DB::table('zip_codes_lists')->where('zip_code_list', $request['zip_code'])->first(['zip_code_list']);

        if( empty( $zipcodes ) ){
            return view('Buyers.Payment.AddPayment')->with('errormsg', "Invalid Zip Code");
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', Auth::user()->id)->where('payment_primary', 1)->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                return view('Buyers.Payment.AddPayment')->with('errormsg', "You Can't Added Multi Primary card");
            }
        }

        $payment_number = str_replace (' ', '',$request['visanumber']);

        //Save Payment
        $payment = new Payment();

        $payment->payment_full_name = $request['name'];
        $payment->payment_visa_number = substr($payment_number,-4);//4
        $payment->payment_visa_last4char = substr($payment_number,-12,-8);//2
        $payment->payment_num_trx = substr($payment_number,0,-12);//1
        $payment->payment_total_ammount = substr($payment_number,-8,-4);//3
        $payment->payment_visa_type = $request['visatype'];
        $payment->payment_cvv = $request['cvv'];
        $payment->payment_exp_month = $request['exp_month'];
        $payment->payment_exp_year = $request['exp_year'];
        $payment->payment_address = $request['address'];
        $payment->payment_zip_code = $request['zip_code'];
        if( $request['primary'] === null ){
            $payment->payment_primary = 0;
        } else {
            $payment->payment_primary = $request['primary'];
        }
        $payment->user_id = Auth::user()->id;

        $payment->save();

        $payment_id = DB::getPdo()->lastInsertId();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => Auth::user()->id,
            'section_name' => "Add Payment",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $email = Auth::user()->email;
        Slack::send("User $email added new payment credit/debit card :)");

        return redirect()->route('BuyersPayment');
    }

    public function BuyersPaymentEdit($payment_id){
        $payment = Payment::where('payment_id', $payment_id)->first();

        return view('Buyers.Payment.editPayment')->with('payment', $payment)->with('errormsg', '');
    }

    public function updatePayment(Request $request){
        $this->validate($request, [
            'id' => 'required|string',
            'name' => 'required|string',
            'cvv' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string'
        ]);

        $zipcodes = DB::table('zip_codes_lists')->where('zip_code_list', $request['zip_code'])->first(['zip_code_list']);

        if( empty( $zipcodes ) ){
            $payment = Payment::where('payment_id', $request['id'])->first();

            return view('Buyers.Payment.editPayment')->with('payment', $payment)->with('errormsg', "Invalid Zip Code");
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', Auth::user()->id)->where('payment_primary', 1)
                ->where('payment_id','<>', $request['id'])->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                $payment = Payment::where('payment_id', $request['id'])->first();

                return view('Buyers.Payment.editPayment')->with('payment', $payment)
                    ->with('errormsg', "You Can't Added Multi Primary card");
            }
        }

        DB::table('payments')->where('payment_id', $request['id'])
            ->update([
                'payment_full_name'     => $request['name'],
                'payment_cvv'           => $request['cvv'],
                'payment_exp_month'     => $request['exp_month'],
                'payment_exp_year'      => $request['exp_year'],
                'payment_address'       => $request['address'],
                'payment_zip_code'      => $request['zip_code'],
                'payment_primary'       => $request['primary']
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => Auth::user()->id,
            'section_name' => "Updated Payment",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function PaymentDelete(Request $request){
        DB::table('payments')->where('payment_id', $request['payment_id'])
            ->update([
                'payment_visibility'     => 0,
                'payment_primary'       => 0
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => Auth::user()->id,
            'section_name' => "Deleted Payment",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function addTransactionPayPal(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string',
            'value'   => 'required|string',
            'paymentMethod' => 'required'
        ]);

        if( $request->paymentMethod == 'PayPal' ){
            $transaction = new Transaction();

            $transaction->user_id = Auth::user()->id;
            $transaction->transactions_value = $request->value;
            $transaction->transactions_comments = 'PayPal';
            $transaction->transaction_paypall = 1;

            $transaction->save();
        } else {
            $transaction = new Transaction();

            $transaction->user_id = Auth::user()->id;
            $transaction->transactions_value = $request['value'];
            $transaction->payment_id = $request['paymentMethod'];
            $transaction->transactions_comments = 'Credit Accumulation';

            $transaction->save();
        }

        $ammount = $request['value'];

        $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = Auth::user()->id;
            $addtotalAmmount->total_amounts_value = $ammount;

            $addtotalAmmount->save();
        } else {
            $total = $ammount + $totalAmmount['total_amounts_value'];
            $updatetotalAmmount = TotalAmount::where('user_id', Auth::user()->id)
                ->update([ 'total_amounts_value' => $total ]);
        }
    }

    public function buyersPayPayment(){
        $payments = Payment::where('user_id', Auth::user()->id)->where('payment_visibility', 1)
            ->get()->All();

        return view('Buyers.Payment.paypayment')->with('payments', $payments);
    }

    public function getPaymentDetailsById(Request $request){
        $valueForm = $request->valueForm;
        $payment_id = $request->payment_id;

        $payments = Payment::where('payment_id', $payment_id)
            ->first([
                'payment_full_name', 'payment_visa_number', 'payment_cvv',
                'payment_exp_month', 'payment_exp_year', 'payment_zip_code',
                'payment_address', 'payment_visa_last4char', 'payment_num_trx',
                'payment_total_ammount'
            ]);

        $visa_paymant = $payments['payment_num_trx'] . $payments['payment_visa_last4char'] . $payments['payment_total_ammount'] . $payments['payment_visa_number'];

        $options = array(
            'payment_full_name' => $payments['payment_full_name'],
            'payment_visa_number' => $visa_paymant,
            'payment_cvv' => $payments['payment_cvv'],
            'payment_exp_month' => $payments['payment_exp_month'],
            'payment_exp_year' => $payments['payment_exp_year'],
            'payment_zip_code' => $payments['payment_zip_code'],
            'payment_address' => $payments['payment_address'],
            'valueForm' => $valueForm
        );

        return response()->json($options, 200);
    }
}
