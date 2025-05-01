<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\PingLeads;
use App\TotalAmount;
use App\Models\SellerTotalAmount;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ListOfSellerController extends Controller
{

    public function __construct(Request $request)
    {
        $request->permission_page = '23-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function ListOfSellers(){
        $ListOfLeadsSeller = DB::table('users')
            ->leftJoin('seller_total_amounts', 'users.id', '=', 'seller_total_amounts.user_id')
            ->leftJoin('users AS sales_users', 'sales_users.id', '=', 'users.sales_id')
            ->leftJoin('users AS sdr_users', 'sdr_users.id', '=', 'users.sdr_id')
            ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
            ->whereIn('users.role_id', ['4', '5'])
            ->groupBy('users.id')
            ->get([
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type_data'),
                DB::raw('(CASE WHEN users.user_visibility = 1 THEN "Active" WHEN users.user_visibility = 2 THEN "Not Active" ELSE "Closed" END) AS users_status_visibility'),
                'users.*','seller_total_amounts.total_amounts_value'
            ]);

        $last_trx_arr = DB::table('leads_customers')
            ->join('campaigns', 'campaigns.vendor_id', '=', 'leads_customers.vendor_id')
            ->orderBy("leads_customers.created_at", "desc")
            ->selectRaw('campaigns.user_id as user_data_id, MAX(leads_customers.created_at) as last_date')
            ->groupBy('campaigns.user_id')
            ->pluck('last_date', "user_data_id")->toarray();

        return view('Admin.Seller.ListOfSeller', compact('ListOfLeadsSeller','last_trx_arr'));
    }

    public function seller_return(Request $request){
        $this->validate($request, [
            'paymentValue' => 'required|numeric'
        ]);

        $paymentType = $request['type'];
        $user_id =  $request['user_id'];
        $value =  $request['paymentValue'];

        $return_note = "";
        if( !empty($request['return_note']) ){
            $return_note = ": (" . $request['return_note'] . ")";
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->transactions_value = $value;
        if($paymentType == 1){
            $transaction->transactions_comments = "ACH Credit";
        } else {
            $transaction->transactions_comments = "Return Leads Amount$return_note";
        }
        $transaction->admin_id = Auth::user()->id;
        $transaction->is_seller = 1;

        $transaction->save();

        $totalAmmount = SellerTotalAmount::where('user_id', $user_id)->first('total_amounts_value');

        if (empty($totalAmmount)) {
            $addtotalAmmount = new SellerTotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $value;

            $addtotalAmmount->save();
        } else {
            $total = $totalAmmount['total_amounts_value'] - $value;
            SellerTotalAmount::where('user_id', $user_id)
                ->update(['total_amounts_value' => $total]);
        }

        return redirect()->back();
    }

}
