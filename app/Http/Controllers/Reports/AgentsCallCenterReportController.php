<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentsCallCenterReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(){
        return view('Reports.agents_callCenter_reports');
    }

    public function listAgentsReportShow(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $agents = User::whereIn("account_type", ["Call Center", "Lead Review"])
            //->where('user_visibility', 1)
            ->get(['username', "user_business_name"]);

        //Total of received leads
        $TotalLeads = DB::table('campaigns_leads_users')
            ->where('is_returned', 0)
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->groupBy('agent_name')
            ->selectRaw('COUNT(campaigns_leads_users_id) AS TotalLeads, agent_name as user_id_key')
            ->pluck('TotalLeads', "user_id_key")->toarray();

        //Total Selling Price
        $Leads_sum = DB::table('campaigns_leads_users')
            ->where('is_returned', 0)
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->groupBy('agent_name')
            ->selectRaw('SUM(campaigns_leads_users_bid) AS Leads_sum, agent_name as user_id_key')
            ->pluck('Leads_sum', "user_id_key")->toarray();

        //Number of return seller leads
        $returnTotalLeads = DB::table('campaigns_leads_users')
            ->where('is_returned', 1)
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->groupBy('agent_name')
            ->selectRaw('COUNT(campaigns_leads_users_id) AS TotalLeads, agent_name as user_id_key')
            ->pluck('TotalLeads', "user_id_key")->toarray();

        //Total Selling Price for return leads
        $returnLeads_sum = DB::table('campaigns_leads_users')
            ->where('is_returned', 1)
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->groupBy('agent_name')
            ->selectRaw('SUM(campaigns_leads_users_bid) AS Leads_sum, agent_name as user_id_key')
            ->pluck('Leads_sum', "user_id_key")->toarray();

        $dataJason = '';
        $dataJason .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $dataJason .= 'id="datatable-buttons"';
        } else {
            $dataJason .= 'id="datatable"';
        }
        $dataJason .= '><thead>
                            <tr>
                                <th>Agent name</th>
                                <th>Number of leads</th>
                                <th>Total lead prices</th>
                                <th>Number of return leads</th>
                                <th>Total return lead price</th>
                            </tr>
                         </thead>
                      <tbody>';

        $TotalLeadsSum = 0;
        $Leads_sumSum = 0;
        $returnTotalLeadsSum = 0;
        $returnLeads_sumSum = 0;
        if( !empty($agents) ){
            foreach ( $agents as $item ){
                $TotalLeadsData = ( !empty($TotalLeads[$item->user_business_name]) ? $TotalLeads[$item->user_business_name] : 0 );
                $Leads_sumData = ( !empty($Leads_sum[$item->user_business_name]) ? $Leads_sum[$item->user_business_name] : 0 );
                $returnTotalLeadsData = ( !empty($returnTotalLeads[$item->user_business_name]) ? $returnTotalLeads[$item->user_business_name] : 0 );
                $returnLeads_sumData = ( !empty($returnLeads_sum[$item->user_business_name]) ? $returnLeads_sum[$item->user_business_name] : 0 );

                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $item->user_business_name. "</td>";
                $dataJason .= "<td>$TotalLeadsData</td>";
                $dataJason .= "<td>$" . number_format($Leads_sumData, 2, '.', ',') . "</td>";
                $dataJason .= "<td>$returnTotalLeadsData</td>";
                $dataJason .= "<td>$" . number_format($returnLeads_sumData, 2, '.', ',') . "</td>";
                $dataJason .= '</tr>';

                $TotalLeadsSum              += $TotalLeadsData;
                $Leads_sumSum               += $Leads_sumData;
                $returnTotalLeadsSum        += $returnTotalLeadsData;
                $returnLeads_sumSum         += $returnLeads_sumData;
            }
        }

        $dataJason .= "</tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td>$TotalLeadsSum</td>
                                    <td>$". number_format($Leads_sumSum, 2, '.', ',')  ."</td>
                                    <td>$returnTotalLeadsSum</td>
                                    <td>$".  number_format($returnLeads_sumSum, 2, '.', ',')  ."</td>
                                </tr>
                            </tfoot>
                        </table>";

        return $dataJason;
    }
}
