<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsReportController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function users_payments(Request $request){
        $start_date = (!empty($request->start_date) ? $request->start_date . " 00:00:00" : "");
        $end_date   = (!empty($request->end_date)   ? $request->end_date   . " 23:59:59"   : "");

        $users = $total_payments = $total_refunds = $first_payment = $first_date_payment = [];

        if(!empty($request->start_date) && !empty($request->end_date)){
            $users = User::leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
                ->whereNotIn('users.role_id', [1, 2])
                ->get(['users.*', 'total_amounts.total_amounts_value']);

            $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];

            //Get Total Payments for users
            $total_payments = DB::table('transactions')
                ->where('transaction_status', 1)
                ->where('accept', 1)
                ->whereIn('transactions_comments', $transactions_comments)
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->groupBy('user_id')
                ->selectRaw('sum(transactions_value) as sum_val, user_id')
                ->pluck('sum_val', "user_id")->toarray();

            //Get Total Refund Payments for users
            $total_refunds = DB::table('transactions')
                ->where('transaction_status', 2)
                ->where('transactions_comments', "Refund Payments")
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->groupBy('user_id')
                ->selectRaw('sum(transactions_value) as sum_val, user_id')
                ->pluck('sum_val', "user_id")->toarray();

            $first_payment = DB::table('transactions')
                ->where('transaction_status', 1)
                ->where('accept', 1)
                ->whereIn('transactions_comments', $transactions_comments)
                ->orderBy('created_at')
                ->groupBy('user_id')
                ->pluck('transactions_value', "user_id")->toarray();

            $first_date_payment = DB::table('transactions')
                ->where('transaction_status', 1)
                ->where('accept', 1)
                ->whereIn('transactions_comments', $transactions_comments)
                ->orderBy('created_at')
                ->groupBy('user_id')
                ->pluck('created_at', "user_id")->toarray();
        }

        return view('Reports.users_payments',
            compact('users', 'total_payments', 'total_refunds', 'first_payment', 'first_date_payment', 'start_date', 'end_date'));
    }
}
