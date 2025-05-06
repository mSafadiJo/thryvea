<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Http\Controllers\AdminPayments\PaymentsController;
use App\Payment;
use App\TotalAmount;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Type;
use Rap2hpoutre\FastExcel\FastExcel;
use Session;
use Slack;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer'],
            ['except' => ['storeValue', 'CreateValueAdmin', 'storeOtherpaymentValAdmin']]);
    }

    public function index(){
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';
        $user_id = Auth::user()->id;

        $transactions = Transaction::leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
            ->where('transactions.user_id', Auth::user()->id)
            ->where('transactions.is_seller', '<>', "1")
            ->whereBetween('transactions.created_at', [$yesterday, $today])
            ->orderBy('transactions.created_at', 'DESC')
            ->Select([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type'
            ])->paginate(10);

        $payments = Payment::where('user_id', Auth::user()->id)->where('payment_visibility', 1)->get()->All();
        $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

        return view('Buyers.transaction.index')
            ->with('transactions', $transactions)
            ->with('totalAmmount', $totalAmmount)
            ->with('user_id', $user_id)
            ->with('payments', $payments);
    }

    public function fetch_data_BuyerTransactions(Request $request){
        if($request->ajax()) {
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $user_id = $request->buyer_id;
            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $transactions = DB::table('transactions')
                ->leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
                ->where('transactions.user_id', $user_id)
                ->where('transactions.is_seller', '<>', "1")
                ->where(function ($query) use ($query_search) {
                    $query->where('transactions.transactions_value', 'like', '%' . $query_search . '%');
                    $query->orWhere('transactions.transactions_comments', 'like', '%' . $query_search . '%');
                });

            if (!empty($start_date) && !empty($end_date)) {
                $transactions = $transactions->whereBetween('transactions.created_at', [$start_date, $end_date]);
            }

            $transactions = $transactions->orderBy("transactions.created_at", "desc")
                ->Select([
                    'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type'
                ])->paginate(10);

            return view('Render.transactions.transactions_Buyer_Render', compact('transactions'))->render();
        }
    }

    public function export(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $user_id = $request->buyer_id;

        $transactions = DB::table('transactions')
            ->leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
            ->where('transactions.user_id', $user_id)
            ->where('transactions.is_seller', '<>', "1")
            ->whereBetween('transactions.created_at', [$start_date, $end_date])
            ->orderBy("transactions.created_at", "desc")
            ->get([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type',
                DB::raw('CASE WHEN (transactions.transaction_status = 1 AND transactions.accept != 2) THEN "Credit" WHEN (transactions.transaction_status = 1 AND transactions.accept = 2) THEN "Failed"  WHEN transactions.transaction_status = 2 THEN "Refund"  WHEN (transactions.transaction_status != 1 AND transactions.transaction_status != 2) THEN "Sold"  END AS transactionStatus'),
            ]);

        return (new FastExcel($transactions))->download('List of Transactions.csv', function ($transaction) {
            return [
                'Value' => $transaction->transactions_value,
                'Status' => $transaction->transactionStatus,
                'Type' => $transaction->transactions_comments,
                'Created At' => " " . $transaction->created_at . " "
            ];
        });
    }

    public function storeValue(Request $request){
        $this->validate($request, [
            'value' => 'required',
            'paymentMethod' => 'required'
        ]);

        if( $request['paymentMethod'] == 'other' ){
            return redirect()->route('Transaction.Value.Create', $request['value']);
        }

        $listPayment = Payment::where('payment_id', $request['paymentMethod']);
        if( Auth::user()->role_id != 3 ){
            $listPayment->where('user_id', $request['user_id']);
        } else {
            $listPayment->where('user_id', Auth::user()->id);
        }
        $listPayment = $listPayment->first([
                'payment_full_name', 'payment_visa_number', 'payment_cvv',
                'payment_exp_month', 'payment_exp_year'
            ]);

        $transaction = new Transaction();

        if( Auth::user()->role_id != 3 ){
            $transaction->admin_id = Auth::user()->id;
            $transaction->user_id = $request['user_id'];
        } else {
            $transaction->user_id = Auth::user()->id;
        }

        $transaction->transactions_value = $request['value'];
        $transaction->payment_id = $request['paymentMethod'];
        $transaction->transactions_comments = 'Credit Accumulation';

        $transaction->save();

        $user_id = Auth::user()->id;
        if( Auth::user()->role_id != 3 ){
            $user_id = $request['user_id'];
        }

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
//        return redirect()->back();
    }

    public function CreateValue($value, $type = "Stripe"){
        return view('Buyers.Payment.valueOther')
            ->with('errormsg', '')
            ->with('loader', '0')
            ->with('value', $value)
            ->with('type', $type)
            ->with('dataDetails', '');
    }

    public function CreateValueAdmin($id, $value, $type = "Stripe"){
        return view('Admin.payments.addotherpayment')
            ->with('errormsg', '')
            ->with('loader', '0')
            ->with('value', $value)
            ->with('user_id', $id)
            ->with('payment_id', "")
            ->with('type', $type)
            ->with('dataDetails', '');
    }

    public function storeOtherpaymentValAdmin(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'user_id' => 'required|string',
            'visanumber' => 'required|string',
            'visatype' => 'required|string',
            'cvv' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string',
            'value' => 'required|string'
        ]);

        $zipcodes = DB::table('zip_codes_lists')->where('zip_code_list', $request['zip_code'])->first(['zip_code_list']);

        if( empty( $zipcodes ) ){
            \Session::put('errormsg', 'Invalid Zip Code');
            return redirect()->back();
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', $request['user_id'])->where('payment_primary', 1)->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                \Session::put('errormsg', "You Can't Added Multi Primary card");
                return redirect()->back();
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
        $payment->user_id = $request['user_id'];

        $payment->save();
        $payment_id = DB::getPdo()->lastInsertId();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request['user_id'],
            'section_name' => "Add Payment",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        Session::flash('sucessAdd', 'Payment successful!');

        $email = User::where('id', $request['user_id'])->first(['email']);
        $email = $email->email;
        //Slack::send("User $email added new payment credit/debit card :)");

        $request['merchant_account'] = $request['type'];
        $request['payment_id'] = $payment_id;
        $request['amount'] = $request['value'];
        $payments_controller = new PaymentsController();
        $payments_controller->add($request);

        return redirect()->route("Admin.Buyers.payments", $request->user_id);
    }

    public function storeOtherpaymentVal(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'visanumber' => 'required|string',
            'visatype' => 'required|string',
            'cvv' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string',
            'value' => 'required|string'
        ]);

        $zipcodes = DB::table('zip_codes_lists')->where('zip_code_list', $request['zip_code'])->first(['zip_code_list']);

        if( empty( $zipcodes ) ){
            \Session::put('errormsg', 'Invalid Zip Code');
            return redirect()->back();
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', Auth::user()->id)->where('payment_primary', 1)->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                \Session::put('errormsg', "You Can't Added Multi Primary card");
                return redirect()->back();
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

        Session::flash('sucessAdd', 'Payment successful!');

        $email = Auth::user()->email;
        //Slack::send("User $email added new payment credit/debit card :)");

        $request['merchant_account'] = $request['type'];
        $request['payment_id'] = $payment_id;
        $request['amount'] = $request['value'];
        $payments_controller = new PaymentsController();
        $payments_controller->add($request);

        return redirect()->route("AddValuePayment");
    }
}
