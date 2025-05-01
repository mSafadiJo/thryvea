<?php

namespace App\Http\Controllers\Reports;

use App\CampaignsLeadsUsers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffiliateReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index(){
        return view('Reports.affiliate_reports');
    }

    public function listAffiliateReportShow(Request $request){
        $start_date1 = $request->from_date . ' 00:00:00';
        $end_date2 = $request->to_date . ' 23:59:59';

        //List Of Sellers
        $users = User::whereIn('role_id', [4,5])->where('user_visibility', 1)->get(['created_at', 'id','user_business_name']);

        //Total of received leads
        $TotalLeads = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('campaigns_leads_users.is_returned', 0)
            ->where('leads_customers.created_at', '>=', $start_date1)
            ->where('leads_customers.created_at', '<=', $end_date2)
            ->groupBy('Seller.id')
            ->selectRaw('COUNT(DISTINCT campaigns_leads_users.lead_id) AS TotalLeads, Seller.id as user_id_key')
            ->pluck('TotalLeads', "user_id_key")->toarray();

        //Total Selling Price
        $Leads_sum = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('campaigns_leads_users.is_returned', 0)
            ->where('leads_customers.created_at', '>=', $start_date1)
            ->where('leads_customers.created_at', '<=', $end_date2)
            ->groupBy('Seller.id')
            ->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS Leads_sum, Seller.id as user_id_key')
            ->pluck('Leads_sum', "user_id_key")->toarray();

        //Number of return seller leads
        $returnTotalLeads = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('campaigns_leads_users.is_returned', 1)
            ->where('leads_customers.created_at', '>=', $start_date1)
            ->where('leads_customers.created_at', '<=', $end_date2)
            ->groupBy('Seller.id')
            ->selectRaw('COUNT(DISTINCT campaigns_leads_users.lead_id) AS TotalLeads, Seller.id as user_id_key')
            ->pluck('TotalLeads', "user_id_key")->toarray();

        //Total Selling Price for return leads
        $returnLeads_sum = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('campaigns_leads_users.is_returned', 1)
            ->where('leads_customers.created_at', '>=', $start_date1)
            ->where('leads_customers.created_at', '<=', $end_date2)
            ->groupBy('Seller.id')
            ->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS Leads_sum, Seller.id as user_id_key')
            ->pluck('Leads_sum', "user_id_key")->toarray();

        //Total purchasing Price
        $purchasingLeads_sum =  DB::table('leads_customers')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('leads_customers.response_data', "Lead Accepted")
            ->where('leads_customers.created_at', '>=', $start_date1)
            ->where('leads_customers.created_at', '<=', $end_date2)
            ->groupBy('Seller.id')
            ->selectRaw('SUM(leads_customers.ping_price) AS Leads_sum, Seller.id as user_id_key')
            ->pluck('Leads_sum', "user_id_key")->toarray();

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '';
        $dataJason .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $dataJason .= 'id="datatable-buttons"';
        } else {
            $dataJason .= 'id="datatable"';
        }
        $dataJason .= '><thead>
                            <tr>
                                <th>Seller Name</th>
                                <th>Number of Leads</th>
                                <th>purchasing price</th>
                                <th>Selling price</th>
                                <th>Profit</th>
                                <th>Number of return Leads</th>
                                <th>Total return price</th>
                            </tr>
                         </thead>
                      <tbody>';

        $TotalLeadsSum = 0;
        $Leads_sumSum = 0;
        $returnTotalLeadsSum = 0;
        $returnLeads_sumSum = 0;
        $purchasingLeads_sumSum = 0;
        $ProfitSum = 0;
        if( !empty($users) ){
            foreach ( $users as $item ){
                $TotalLeadsData = ( !empty($TotalLeads[$item->id]) ? $TotalLeads[$item->id] : 0 );
                $Leads_sumData = ( !empty($Leads_sum[$item->id]) ? $Leads_sum[$item->id] : 0 );
                $returnTotalLeadsData = ( !empty($returnTotalLeads[$item->id]) ? $returnTotalLeads[$item->id] : 0 );
                $returnLeads_sumData = ( !empty($returnLeads_sum[$item->id]) ? $returnLeads_sum[$item->id] : 0 );
                $purchasingLeads_sumData = ( !empty($purchasingLeads_sum[$item->id]) ? $purchasingLeads_sum[$item->id] : 0 );
                $Profit = $Leads_sumData - $purchasingLeads_sumData;

                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $item['user_business_name'] . "</td>";
                $dataJason .= "<td>$TotalLeadsData</td>";
                $dataJason .= "<td>$" . number_format($purchasingLeads_sumData, 2, '.', ',') . "</td>";
                $dataJason .= "<td>$" . number_format($Leads_sumData, 2, '.', ',') . "</td>";
                $dataJason .= "<td>$" . number_format($Profit, 2, '.', ',') . "</td>";
                $dataJason .= "<td>$returnTotalLeadsData</td>";
                $dataJason .= "<td>$" . number_format($returnLeads_sumData, 2, '.', ',') . "</td>";
                $dataJason .= '</tr>';

                $TotalLeadsSum              += $TotalLeadsData;
                $Leads_sumSum               += $Leads_sumData;
                $returnTotalLeadsSum        += $returnTotalLeadsData;
                $returnLeads_sumSum         += $returnLeads_sumData;
                $purchasingLeads_sumSum     += $purchasingLeads_sumData;
                $ProfitSum                  += $Profit;
            }
        }

        $dataJason .= "</tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td>$TotalLeadsSum</td>
                                    <td>$". number_format($purchasingLeads_sumSum, 2, '.', ',')  ."</td>
                                    <td>$". number_format($Leads_sumSum, 2, '.', ',')  ."</td>
                                    <td>$". number_format($ProfitSum, 2, '.', ',')  ."</td>
                                    <td>$returnTotalLeadsSum</td>
                                    <td>$". number_format($returnLeads_sumSum, 2, '.', ',')  ."</td>
                                </tr>
                            </tfoot>
                        </table>";

        return $dataJason;
    }
}
