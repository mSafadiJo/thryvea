<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallCenterLeadsProfitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index()
    {
        return view('Reports.CallCenterProfit');
    }

    public function searchCallCenterReports(Request $request)
    {
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        //Traffic Sources
        $leadsCustomerSources = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('is_duplicate_lead', '=', 0)
            ->where('campaigns_leads_users.call_center', 1)
            ->where('campaigns_leads_users.is_returned',0)
            ->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date])
            ->groupBy('leads_customers.traffic_source')
            ->pluck('traffic_source')->toArray();

        //TOTAL Sold LEADS
        $leadsCustomerTotalNumberOfLeadsPerPrice = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('is_duplicate_lead', '=', 0)
            ->where('campaigns_leads_users.call_center', 1)
            ->where('campaigns_leads_users.is_returned',0)
            ->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date])
            ->selectRaw("COUNT(DISTINCT leads_customers.lead_id) AS TotalLead, leads_customers.traffic_source")
            ->groupBy('leads_customers.traffic_source')
            ->pluck('TotalLead','leads_customers.traffic_source')->toArray();

        //Total selling price per source
        $leadsCustomerTotalSellingPriceSource = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.is_duplicate_lead', 0)
            ->where('campaigns_leads_users.call_center', 1)
            ->where('campaigns_leads_users.is_returned',  0)
            ->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date])
            ->distinct('leads_customers.lead_id')
            ->selectRaw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS TotalSellingPrice, leads_customers.traffic_source")
            ->groupBy('traffic_source')
            ->pluck('TotalSellingPrice','traffic_source')->toArray();


        //TOTAL COUNT RETURN LEADS
        $leadsCustomerTotalNumberOfLeadsPerPriceReturned = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.is_duplicate_lead', 0)
            ->where('campaigns_leads_users.call_center', 1)
            ->where('campaigns_leads_users.is_returned', 1)
            ->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date])
            ->distinct('leads_customers.lead_id')
            ->selectRaw("COUNT(DISTINCT leads_customers.lead_id) AS TotalReturnNumber, leads_customers.traffic_source")
            ->groupBy('traffic_source')
            ->pluck('TotalReturnNumber', 'traffic_source')->toArray();

        //TOTAL RETURN LEADS Price
        $leadsCustomerTotalPriceReturnedLeads = DB::table('leads_customers')
            ->join('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.is_duplicate_lead', '=', 0)
            ->where('campaigns_leads_users.call_center', '=', 1)
            ->where('campaigns_leads_users.is_returned', '=', 1)
            ->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date])
            ->distinct('leads_customers.lead_id')
            ->selectRaw("SUM(campaigns_leads_users.campaigns_leads_users_bid ) AS TotalReturnPrice, leads_customers.traffic_source")
            ->groupBy('traffic_source')
            ->pluck('TotalReturnPrice', 'traffic_source');

        $data_table = '';
        $data_table .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if (empty($permission_users) || in_array('3-4', $permission_users)) {
            $data_table .= ' id="datatable-buttons" >';
        } else {
            $data_table .= ' id="datatable" >';
        }
        $data_table .= '<thead>
                            <tr>
                                <th>Source</th>
                                <th>Total # Of Sold Leads</th>
                                <th>Total Purchase Price</th>
                                <th>Total Selling Price</th>
                                <th>Profit</th>
                                <th>Total # Of Return Leads</th>
                                <th>Total Price Of Return Leads</th>
                            </tr>
                        </thead>
                        <tbody>';

        $totalAllSoldLead = 0;
        $totalAllPurchasePrice = 0;
        $totalAllSellingPrice = 0;
        $totalAllProfit = 0;
        $totalAllReturnLeads = 0;
        $totalAllReturnLeadsPriceTotal = 0;

        if (!empty($leadsCustomerSources)) {
            foreach ($leadsCustomerSources as $ts) {
                $source = (empty($ts) ? "" : $ts);
                $numberOfSoldLeads = (empty($leadsCustomerTotalNumberOfLeadsPerPrice[$ts]) ? 0 : $leadsCustomerTotalNumberOfLeadsPerPrice[$ts]);
                $totalAllSoldLead += $numberOfSoldLeads;

                if (str_contains(strtolower($ts), 'system') || str_contains(strtolower($ts), 'http')) {
                    $totalLeadPurchasePrice = $numberOfSoldLeads * 20;
                } else {
                    $totalLeadPurchasePrice = $numberOfSoldLeads * 35;
                }
                $totalAllPurchasePrice += $totalLeadPurchasePrice;

                $totalSellingPrice = empty($leadsCustomerTotalSellingPriceSource[$ts]) ? 0 : $leadsCustomerTotalSellingPriceSource[$ts];
                $totalAllSellingPrice += $totalSellingPrice;

                $profit = $totalSellingPrice - $totalLeadPurchasePrice;
                $totalAllProfit += $profit;

                $returnLeadsCount = empty($leadsCustomerTotalNumberOfLeadsPerPriceReturned[$ts]) ? 0 : $leadsCustomerTotalNumberOfLeadsPerPriceReturned[$ts];
                $totalAllReturnLeads += $returnLeadsCount;

                $returnLeadsPriceSum = empty($leadsCustomerTotalPriceReturnedLeads[$ts]) ? 0 : $leadsCustomerTotalPriceReturnedLeads[$ts];
                $totalAllReturnLeadsPriceTotal += $returnLeadsPriceSum;

                $data_table .= "<tr>";
                $data_table .= '<td>' . $source . '</td>';
                $data_table .= '<td>' . $numberOfSoldLeads . '</td>';
                $data_table .= '<td> $' . $totalLeadPurchasePrice . '</td>';
                $data_table .= '<td> $' . $totalSellingPrice . '</td>';
                $data_table .= '<td> $' . $profit . '</td>';
                $data_table .= '<td>' . $returnLeadsCount . '</td>';
                $data_table .= '<td> $' . $returnLeadsPriceSum . '</td>';
                $data_table .= "</tr>";

            }
        }

        $data_table .= "</tbody><tfoot>
                                <tr>
                                    <th>Total</th>
                                    <td>$totalAllSoldLead</td>
                                    <td>$$totalAllPurchasePrice</td>
                                    <td>$$totalAllSellingPrice</td>
                                    <td>$$totalAllProfit</td>
                                    <td>$totalAllReturnLeads</td>
                                    <td>$$totalAllReturnLeadsPriceTotal</td>
                                </tr>
                            </tfoot></table";
        return $data_table;

    }
}
