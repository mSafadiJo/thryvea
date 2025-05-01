<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class  SalesCommissionController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function salesCommission(){
        return view('Reports.SalesCommission');
    }

    public function salesCommissionSearch(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $users = User::leftJoin('users AS sales_users', 'sales_users.id', '=', 'users.sales_id')
            ->leftJoin('users AS sdr_users', 'sdr_users.id', '=', 'users.sdr_id')
            ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
            ->leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
            ->where('users.user_visibility', '<>', 4)
            ->whereIn('users.role_id', ['3', '4', '6']);

        if( !empty($request->start_date) && !empty($request->end_date) ){
            //$users->whereBetween('users.created_at', [$start_date, $end_date]);
        }

        $users = $users->orderBy('users.created_at', 'DESC')
            ->groupBy('users.id')
            ->get([
                'users.*', 'total_amounts.total_amounts_value',
                'sales_users.username AS sales_username', 'sdr_users.username AS sdr_username', 'acc_manager_users.username AS acc_manager_username',
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type'),
                DB::raw('(CASE WHEN users.user_visibility = 1 THEN "Active" WHEN users.user_visibility = 2 THEN "Not Active" ELSE "Closed" END) AS users_status_visibility'),
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT service__campaigns.service_campaign_name) as service_campaign_name FROM campaigns
                        JOIN service__campaigns ON service__campaigns.service_campaign_id=campaigns.service_campaign_id
                        WHERE campaigns.user_id = users.id) AS service_campaign_name"),
                DB::raw("(SELECT GROUP_CONCAT(campaign_target_area.stateFilter_code separator '|') as stateFilter_code
                        FROM campaigns
                        JOIN campaign_target_area ON campaign_target_area.campaign_id=campaigns.campaign_id
                        WHERE campaigns.user_id = users.id AND campaigns.campaign_visibility = 1 ) AS stateFilter_code")
            ]);

        $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];

        $trx_date = DB::table('transactions')
            ->where('transaction_status', 1)
            ->where('accept', 1)
            ->whereIn('transactions_comments', $transactions_comments)
            ->orderBy('created_at')
            ->groupBy('user_id')
            ->pluck('created_at', "user_id")->toarray();

        $trx_value = DB::table('transactions')
            ->where('transaction_status', 1)
            ->where('accept', 1)
            ->whereIn('transactions_comments', $transactions_comments)
            ->orderBy('created_at')
            ->groupBy('user_id')
            ->pluck('transactions_value', "user_id")->toarray();

        $trx_type = DB::table('transactions')
            ->where('transaction_status', 1)
            ->where('accept', 1)
            ->whereIn('transactions_comments', $transactions_comments)
            ->orderBy('created_at')
            ->groupBy('user_id')
            ->pluck('transactions_comments', "user_id")->toarray();

        $trx_return = DB::table('transactions')
            ->where('transaction_status', 1)
            ->where('accept', 1)
            ->whereIn('transactions_comments', $transactions_comments)
            ->orderBy('created_at')
            ->groupBy('user_id')
            ->pluck('is_refund', "user_id")->toarray();

        $last_trx_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->orderBy("date", "desc")
            ->selectRaw('user_id, MAX(date) as last_date')
            ->groupBy('user_id')
            ->pluck('last_date', "user_id")->toarray();

        $total_spend_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->groupBy('user_id')
            ->selectRaw('SUM(campaigns_leads_users_bid) as total_spend, user_id as user_id_key')
            ->pluck('total_spend', "user_id_key")->toarray();

        $total_bid_arr = DB::table('transactions')
            ->where("transaction_status", 1)
            ->where("accept", 1)
            ->whereIn("transactions_comments", $transactions_comments)
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        $list_of_return_amount = DB::table('transactions')
            ->where("accept", 1)
            ->where('transactions_comments', 'like', '%Return Leads Amount%')
            ->whereNotNull("transactionauthid")
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        $data_table = '';
        $data_table .= '<table class="table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_table .= ' id="datatable-buttons" >';
        } else {
            $data_table .= ' id="datatable" >';
        }

        $data_table .= '<thead>
                            <tr>
                                <th>#</th>
                                <th>Business Name</th>
                                <th>Type</th>
                                <th>States</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>1<sup>st</sup> Fund Date</th>
                                <th>1<sup>st</sup> Fund Value</th>
                                <th>Payment Type</th>
                                <th>SDR Claimer</th>
                                <th>Sales Claimer</th>
                                <th>Account Manager Claimer</th>
                                <th>Current Balance</th>
                                <th>Total Spent</th>
                                <th>Total Funded</th>
                                <th>Last Transaction</th>
                            </tr>
                          </thead>
                          <tbody>';

        if( !empty($users) ){
            foreach ( $users as $user ){
                $trx_date_data = (!empty($trx_date[$user->id]) ? $trx_date[$user->id] : "");
                $trx_value_data = (!empty($trx_value[$user->id]) ? $trx_value[$user->id] : "");
                $trx_type_data = (!empty($trx_type[$user->id]) ? $trx_type[$user->id] : "");
                $trx_return_data = (!empty($trx_return[$user->id]) ? $trx_return[$user->id] : "");
                $list_of_return_amount_data = (!empty($list_of_return_amount[$user->id]) ? $list_of_return_amount[$user->id] : 0);

                if(!empty($request->start_date) && !empty($request->end_date)){
                    if(!empty($trx_date_data)){
                        if(!($trx_date_data >= $start_date && $trx_date_data <= $end_date)){
                            continue;
                        }
                    } else {
                        continue;
                    }
                }

                $List_code="";
                if(!empty($user->stateFilter_code)){
                    $Filter_code  = $user->stateFilter_code;
                    $Filter_code_array = str_replace(array('[',']','"'),'', explode("|", $Filter_code));
                    $Filter_code_unique = array();

                    foreach ($Filter_code_array as $t) {
                        $Filter_code_array1 = explode(',', $t);
                        $Filter_code_unique = array_unique(array_merge($Filter_code_unique, $Filter_code_array1));
                    }
                    $List_code = implode(',', $Filter_code_unique);
                }

                $data_table .=  "<tr>";
                $data_table .=  "<td>". $user->id . "</td>";
                $data_table .=  "<td>". $user->user_business_name . "</td>";
                $data_table .=  "<td>". $user->user_type . "</td>";
                $data_table .=  "<td><span style='cursor: pointer;' onclick='return sellerCommissionStateModel(\"". str_replace(",", ", ", $List_code) ."\", \"" . $user->user_business_name ."\", \"States\")'>".Str::limit($List_code , $limit = 10, $end = '...') . "</span></td>";
                $data_table .=  "<td><span style='cursor: pointer;' onclick='return sellerCommissionStateModel(\"". str_replace(",", ", ", $user->service_campaign_name) ."\", \"" . $user->user_business_name ."\", \"Services\")'>".Str::limit($user->service_campaign_name , $limit = 10, $end = '...') . "</span></td>";
                $data_table .=  "<td>". $user->users_status_visibility . "</td>";
                $data_table .=  "<td>". $user->created_at . "</td>";
                $data_table .=  "<td>". $trx_date_data . "</td>";
                $data_table .=  "<td>". ( !empty($trx_value_data) ? "$".$trx_value_data : "") . "</td>";
                $data_table .=  "<td>". $trx_type_data . ( $trx_return_data == 1 ? " (Refund)" : "") . "</td>";
                $data_table .=  "<td>". $user->sdr_username . "</td>";
                $data_table .=  "<td>". $user->sales_username . "</td>";
                $data_table .=  "<td>". $user->acc_manager_username . "</td>";
                $data_table .=  "<td>". (!empty($user->total_amounts_value) ? "$".$user->total_amounts_value : "$0") . "</td>";
                $data_table .=  "<td>". (!empty($total_spend_arr[$user->id]) ? "$".$total_spend_arr[$user->id] - $list_of_return_amount_data : "$0") . "</td>";
                $data_table .=  "<td>". (!empty($total_bid_arr[$user->id]) ? "$".$total_bid_arr[$user->id] : "$0") . "</td>";
                $data_table .=  "<td>". (!empty($last_trx_arr[$user->id]) ? date('m/d/Y', strtotime($last_trx_arr[$user->id])) : "----") . "</td>";
                $data_table .=  "</tr>";
            }
        }
        $data_table .=  "</tbody></table";

        return $data_table;
    }
}
