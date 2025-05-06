<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Models\SellerTotalAmount;
use App\Payment;
use App\Ticket;
use App\TotalAmount;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Session;
use Slack;

class BuyersDetailsAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function listOfUserWallet($user_id){
        $payments = Payment::where('payment_visibility', 1)
            ->where('user_id', $user_id)->get()->All();

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

        return view('Admin.payments.listOfPayment')
            ->with('totalAmmount', $totalAmmount)
            ->with('user_id', $user_id)
            ->with('payments', $payments);
    }

    public function createWallet($user_id){
        return view('Admin.payments.addWalet')
            ->with('user_id', $user_id)
            ->with('errormsg', "");
    }

    public function storeWallet(Request $request, $user_id){
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
            return view('Admin.payments.addWalet')
                ->with('user_id', $user_id)
                ->with('errormsg', "Invalid Zip Code");
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', $user_id)->where('payment_primary', 1)->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                return view('Admin.payments.addWalet')
                    ->with('user_id', $user_id)
                    ->with('errormsg', "You Can't Added Multi Primary card");
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
        $payment->user_id = $user_id;

        $payment->save();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $user_id,
            'section_name' => "Add Payment",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $email = User::where('id', $user_id)->first(['email']);
        $email = $email->email;
        //Slack::send("User $email added new payment credit/debit card :)");

        return redirect()->route('Admin.Buyers.Wallet', $user_id);
    }

    public function editWallet($payment_id){
        $payment = Payment::where('payment_id', $payment_id)->first();

        return view('Admin.payments.editwallet')->with('payment', $payment)->with('errormsg', '');
    }

    public function updateWallet(Request $request){
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
        $payment = Payment::where('payment_id', $request['id'])->first();

        if( empty( $zipcodes ) ){
            return view('Admin.payments.editwallet')->with('payment', $payment)->with('errormsg', "Invalid Zip Code");
        }

        if( $request['primary'] !== null ) {
            $hasPrimary = DB::table('payments')->where('user_id', Auth::user()->id)->where('payment_primary', 1)
                ->where('payment_id','<>', $request['id'])->first();

            if (!empty($hasPrimary) || $hasPrimary !== null) {
                $payment = Payment::where('payment_id', $request['id'])->first();

                return view('Admin.payments.editwallet')->with('payment', $payment)
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

        return redirect()->route('Admin.Buyers.Wallet', $payment->user_id);
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

    public function DeleteACHCredit(Request $request){
        $transaction_id = $request->transaction_id;
        $transactions_value = $request->transactions_value;
        $user_id = $request->user_id;

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        if(!empty($totalAmmount)){
            DB::table('total_amounts')->where('user_id', $user_id)
                ->update([
                    'total_amounts_value' => ($totalAmmount['total_amounts_value'] - $transactions_value)
                ]);

            DB::table('transactions')->where('transaction_id', $transaction_id)->delete();
        } else {
            return 0;
        }

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => Auth::user()->id,
            'section_name' => "Deleted ACH Credit",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Payment',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return 1;
    }

    public function listOfUserTransactions($user_id){
        //Transactions For Buyers
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $transactions = Transaction::leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
            ->leftjoin('users', 'users.id', '=', 'transactions.admin_id')
            ->where('transactions.user_id', $user_id)
            ->where('transactions.is_seller', '<>', "1")
            ->whereBetween('transactions.created_at', [$yesterday, $today])
            ->orderBy("transactions.created_at", "desc")
            ->Select([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type', 'users.username'
            ])->paginate(10);

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        $is_seller = 0;

        return view('Admin.payments.ListTransactions')
            ->with('is_seller', $is_seller)
            ->with('totalAmmount', $totalAmmount)
            ->with('user_id', $user_id)
            ->with('transactions', $transactions);
    }

    public function fetch_data_listOfUserTransactions(Request $request){
        if($request->ajax()) {
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $user_id = $request->buyer_id;
            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);
            $is_seller = 0;

            $transactions = DB::table('transactions')
                ->leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
                ->leftjoin('users', 'users.id', '=', 'transactions.admin_id')
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
                    'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type', 'users.username'
                ])->paginate(10);

            return view('Render.transactions.transactions_Render', compact('transactions','is_seller', 'user_id'))->render();
        }
    }

    public function listOfUserTransactionsExport(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $user_id = $request->buyer_id ;

        $transactions = DB::table('transactions')
            ->leftjoin('payments', 'payments.payment_id', '=', 'transactions.payment_id')
            ->leftjoin('users', 'users.id', '=', 'transactions.admin_id')
            ->where('transactions.user_id', $user_id)
            ->where('transactions.is_seller', '<>', "1")
            ->whereBetween('transactions.created_at', [$start_date, $end_date])
            ->orderBy("transactions.created_at", "desc")
            ->get([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_visa_type', 'users.username',
                DB::raw('CASE WHEN (transactions.transaction_status = 1 AND transactions.accept != 2) THEN "Credit" WHEN (transactions.transaction_status = 1 AND transactions.accept = 2) THEN "Failed"  WHEN transactions.transaction_status = 2 THEN "Refund"  WHEN (transactions.transaction_status != 1 AND transactions.transaction_status != 2) THEN "Sold"  END AS transactionStatus'),
            ]);

        return (new FastExcel($transactions))->download('List of Transactions.csv', function ($transaction) {
            return [
                'Value' => $transaction->transactions_value,
                'Status' => $transaction->transactionStatus,
                'Type' => $transaction->transactions_comments,
                'Admin' => $transaction->username,
                'Created At' => " " . $transaction->created_at . " "
            ];
        });
    }

    public function listOfSellersTransactions($user_id){
        //Transactions For Seller
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $transactions = DB::table('transactions')
            ->leftjoin('users', 'users.id', '=', 'transactions.admin_id')
            ->where('transactions.user_id', $user_id)
            ->where('transactions.is_seller', "1")
            ->whereBetween('transactions.created_at', [$yesterday, $today])
            ->orderBy("transactions.created_at", "desc")
            ->Select([
                'transactions.*', 'users.username'
            ])->paginate(10);

        $totalAmmount = SellerTotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        $is_seller = 1;

        return view('Admin.payments.ListTransactions')
            ->with('is_seller', $is_seller)
            ->with('totalAmmount', $totalAmmount)
            ->with('user_id', $user_id)
            ->with('transactions', $transactions);
    }

    public function fetch_data_Transactions_seller(Request $request){
        if($request->ajax()) {
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $user_id = $request->buyer_id;
            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $transactions = DB::table('transactions')
                ->leftjoin('users', 'users.id', '=', 'transactions.admin_id')
                ->where('transactions.user_id', $user_id)
                ->where('transactions.is_seller', "1")
                ->where(function ($query) use ($query_search) {
                    $query->where('transactions.transactions_value', 'like', '%' . $query_search . '%');
                    $query->orWhere('transactions.transactions_comments', 'like', '%' . $query_search . '%');
                });

            if (!empty($start_date) && !empty($end_date)) {
                $transactions = $transactions->whereBetween('transactions.created_at', [$start_date, $end_date]);
            }

            $transactions = $transactions->orderBy("transactions.created_at", "desc")
                ->Select([
                    'transactions.*', 'users.username'
                ])->paginate(10);

            $is_seller = 1;

            return view('Render.transactions.transactions_Render', compact('transactions','is_seller'))->render();
        }
    }

    public function payments($user_id){
        $payments = Payment::where('user_id', $user_id)->where('payment_visibility', 1)->get()->All();

        return view('Admin.payments.addPayment')->with('payments', $payments)->with('user_id', $user_id);
    }

    public function addPromotionalCredit(Request $request, $user_id){
        $this->validate($request, [
            'value_promo' => 'required|numeric',
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->transactions_value = $request['value_promo'];
        $transaction->transactions_comments = 'Promotional Credit';
        $transaction->admin_id = Auth::user()->id;
        $transaction->save();

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $request['value_promo'];

            $addtotalAmmount->save();
        } else {
            $total = $request['value_promo'] + $totalAmmount['total_amounts_value'];
            TotalAmount::where('user_id', $user_id)
                ->update([ 'total_amounts_value' => $total ]);
        }

        Session::flash('success', 'Payment successful!');

        return redirect()->back();
    }

    public function addACHCredit(Request $request, $user_id){
        $this->validate($request, [
            'value' => 'required|numeric',
            'type' => 'required|string',
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->transactions_value = $request['value'];
        $transaction->transactions_comments = $request['type'];
        $transaction->accept = 1;
        $transaction->admin_id = Auth::user()->id;
        $transaction->save();

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $request['value'];

            $addtotalAmmount->save();
        } else {
            $total = $request['value'] + $totalAmmount['total_amounts_value'];
            TotalAmount::where('user_id', $user_id)
                ->update([ 'total_amounts_value' => $total ]);
        }

        if($request['type'] == "ACH Credit"){
            $email = User::where('id', $user_id)->first(['email'])->email;
            $ammount = $request['value'];
            //Slack::send("User $email paid $$ammount using the ACH Credit :)");
        }

        Session::flash('success', 'Payment successful!');

        return redirect()->back();
    }

    public function addReturnLeadBidPayment(Request $request, $user_id){
        $this->validate($request, [
            'value_return' => 'required|numeric',
            'return_date' => 'required',
            'return_note'
        ]);

        $return_note = "";
        if( !empty($request['return_note']) ){
            $return_note = ": (" . $request['return_note'] . ")";
        }

        $transaction = new Transaction();

        $transaction->user_id = $user_id;
        $transaction->transactions_value = $request['value_return'];
        $transaction->transactions_comments = "Return Leads Amount$return_note";
        $transaction->accept = 1;
        $transaction->transactionauthid = date("Y-m-d", strtotime($request['return_date']));
        $transaction->admin_id = Auth::user()->id;

        $transaction->save();

        $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $request['value_return'];

            $addtotalAmmount->save();
        } else {
            $total = $request['value_return'] + $totalAmmount['total_amounts_value'];
            TotalAmount::where('user_id', $user_id)
                ->update([ 'total_amounts_value' => $total ]);
        }

        Session::flash('success', 'Return lead successful!');

        return redirect()->back();
    }

    public function listOfUserTicket($user_id){
        $from_date = date("Y-m-d", strtotime("-3 month", strtotime(date('Y-m') . '-01'))) . ' 00:00:00';
        $to_date = date('Y-m-t') . ' 23:59:59';

        //Return Ticket Issues for 2 month or not closed
        $ticket_issues = Ticket::where('ticket_type', 1)
            ->where('user_id', $user_id)
            ->where(function ($query) use($from_date, $to_date)  {
                $query->whereBetween('created_at', [$from_date, $to_date])
                    ->orWhere('ticket_status', '<>', 3);
            })
            ->orderBy('created_at', 'desc')
            ->get()->all();

        //Return Ticket Return Lead for 2 month or not closed
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('tickets.ticket_type', 2)
            ->where('tickets.user_id', $user_id)
            ->where(function ($query) use($from_date, $to_date)  {
                $query->whereBetween('tickets.created_at', [$from_date, $to_date])
                    ->orWhere('tickets.ticket_status', '<>', 3);
            })
            ->orderBy('tickets.created_at', 'desc')
            ->get([
                'reason_lead_returned.reason_lead_returned_name', 'tickets.*',
                'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_phone_number'
            ]);

        //Return Buyers Name
        $nameuser = User::where('id', $user_id)->first(['username']);

        return view('Admin.Ticket.index')
            ->with('ticket_issues', $ticket_issues)
            ->with('ticket_returnlead', $ticket_returnlead)
            ->with('nameuser', $nameuser->username);
    }

    public function ticketChangeStatus(Request $request){
        $ticket_id = $request->ticket_id;
        $status = $request->status;
        $type = $request->type;

        $buyer_data = Ticket::where('ticket_id', $ticket_id)->first([
            'user_id', 'campaigns_leads_users_id', 'ticket_status'
        ]);
        $buyer_id = $buyer_data->user_id;

        $buyer_details = User::where('id', $buyer_id)->first();

        $buyersEmail = $buyer_details->email;
        $buyersusername = $buyer_details->username;

        $change = '';
        if( $buyer_data->ticket_status == 3 || $buyer_data->ticket_status == 4 ){
            return response()->json($change, 200);
        }

        if( $status == 4 ){
            $change = Ticket::where('ticket_id', $ticket_id)
                ->update([
                    'ticket_status' => $status,
                    'reject_text' => $request->reject_text
                ]);

            //EMAIL
            $data = array(
                'name' => $buyersusername,
                'type' => 'Ticket #'. $ticket_id . ' was Rejected',
                'msg'  => $request->reject_text
            );
            Mail::send(['text'=>'Mail.ticketmail'], $data, function($message) use($buyersEmail, $buyersusername, $type) {
                $message->to($buyersEmail, $buyersusername)->subject($type);
                $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
            });
        }
        else if( $status == 3 ){
            $textAppend = '';
            if( $type != 'Issue Ticket' ){
                $textAppend = 'and credited';

                $lead_id = $buyer_data->campaigns_leads_users_id;
                $cridttotal = DB::table('campaigns_leads_users')
                    ->where('campaigns_leads_users_id', $lead_id)
                    ->where('user_id', $buyer_id)
                    ->where('is_returned', 0)
                    ->first(['campaigns_leads_users_bid']);

                if( !empty($cridttotal) ){
                    $countOfLead = DB::table('campaigns_leads_users')
                        ->where('user_id', $buyer_id)
                        ->where('date', '>=', date('Y-m') . '-1')
                        ->where('date', '<=', date('Y-m-t'))
                        ->count();

                    $countOfReturnLead = DB::table('campaigns_leads_users')
                        ->where('user_id', $buyer_id)
                        ->where('is_returned', 1)
                        ->where('date', '>=', date('Y-m') . '-1')
                        ->where('date', '<=', date('Y-m-t'))
                        ->count();

                    $permission_users = array();
                    if( !empty(Auth::user()->permission_users) ){
                        $permission_users = json_decode(Auth::user()->permission_users, true);
                    }

                    if( $countOfLead * 0.15 > $countOfReturnLead || Auth::user()->role_id == 1 ||
                        empty($permission_users) || in_array('4-5', $permission_users) ){
                        $valttotal = $cridttotal->campaigns_leads_users_bid;

                        $totalAmmount = TotalAmount::where('user_id', $buyer_id)->first('total_amounts_value');

                        if( empty($totalAmmount) ){
                            $addtotalAmmount = new TotalAmount();

                            $addtotalAmmount->user_id = $buyer_id;
                            $addtotalAmmount->total_amounts_value = $valttotal;

                            $addtotalAmmount->save();
                        } else {
                            $total = $valttotal + $totalAmmount['total_amounts_value'];
                            TotalAmount::where('user_id', $buyer_id)
                                ->update([ 'total_amounts_value' => $total ]);
                        }

                        DB::table('campaigns_leads_users')
                            ->where('campaigns_leads_users_id', $lead_id)
                            ->where('user_id', $buyer_id)
                            ->where('is_returned', 0)
                            ->update([
                                'is_returned' => 1
                            ]);

                        DB::table('campaigns_leads_users_affs')
                            ->where('campaigns_leads_users_id', $lead_id)
                            ->where('user_id', $buyer_id)
                            ->where('is_returned', 0)
                            ->update([
                                'is_returned' => 1
                            ]);

                        $change = Ticket::where('ticket_id', $ticket_id)
                            ->update([
                                'ticket_status' => $status
                            ]);

                        $data = array(
                            'name' => $buyersusername,
                            'type' => 'Ticket #'. $ticket_id . ' was Accepted ' . $textAppend,
                            'msg'  => ' '
                        );

                        Mail::send(['text'=>'Mail.ticketmail'], $data, function($message) use($buyersEmail, $buyersusername, $type) {
                            $message->to($buyersEmail, $buyersusername)->subject($type);
                            $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
                        });
                    } else {
                        return response()->json('graterThan', 200);
                    }
                }
            } else {
                $change = Ticket::where('ticket_id', $ticket_id)
                    ->update([
                        'ticket_status' => $status
                    ]);

                $data = array(
                    'name' => $buyersusername,
                    'type' => 'Ticket #'. $ticket_id . ' was  Closed ' . $textAppend,
                    'msg'  => ' '
                );

                Mail::send(['text'=>'Mail.ticketmail'], $data, function($message) use($buyersEmail, $buyersusername, $type) {
                    $message->to($buyersEmail, $buyersusername)->subject($type);
                    $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
                });
            }
        } else {
            $change = Ticket::where('ticket_id', $ticket_id)
                ->update([
                    'ticket_status' => $status
                ]);
        }

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $ticket_id,
            'section_name' => $request->type,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Ticket',
            'action'    => 'Update',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return response()->json($change, 200);
    }

    public function store_returnlead(Request $request){
        $this->validate($request, [
            'ticket_message' => ['required', 'string', 'max:255'],
            'user_id' => ['required'],
            'lead_id' => ['required'],
            'reason_returned' => ['required'],
        ]);

        $lead_found = DB::table('campaigns_leads_users')
            ->where('user_id', $request->user_id)
            ->where('campaigns_leads_users_id', $request->lead_id)
            ->where('is_returned', 0)
            ->first(['campaigns_leads_users_id', 'campaign_id',
                'campaigns_leads_users_type_bid', 'is_returned']);

        if( !empty($lead_found) ){
            $is_exist = Ticket::where('ticket_type', 2)
                ->where('user_id', $request->user_id)
                ->where('campaigns_leads_users_id', $request->lead_id)
                ->first();

            if( empty($is_exist) ){
                $ticket = new Ticket();

                $ticket->ticket_type = 2;
                $ticket->user_id = $request->user_id;
                $ticket->ticket_message = $request->ticket_message;
                $ticket->campaigns_leads_users_id = $request->lead_id;
                $ticket->reason_lead_returned_id = $request->reason_returned;
                $ticket->campaign_id = $lead_found->campaign_id;
                $ticket->campaigns_leads_users_type_bid = $lead_found->campaigns_leads_users_type_bid;
                $ticket->ticket_status = 1;

                $ticket->save();

                $newticketid = DB::getPdo()->lastInsertId();

                //Access LOG
                AccessLog::create([
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->username,
                    'section_id' => $newticketid,
                    'section_name' => 'Return Lead Ticket',
                    'user_role' => Auth::user()->role_id,
                    'section'   => 'Ticket',
                    'action'    => 'Create',
                    'ip_address' => request()->ip(),
                    'location' => json_encode(\Location::get(request()->ip())),
                    'request_method' => json_encode($request->all())
                ]);

                Session::flash('success', 'Return lead #' . $request->lead_id . ' successfully');
            } else {
                Session::flash('error', 'This lead has already been submitted!');
            }
        } else {
            Session::flash('error', 'Unknown Lead id#');
        }

        return redirect()->back();
    }
}
