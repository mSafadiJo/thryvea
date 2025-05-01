<?php

namespace App\Http\Controllers;

use App\BuyersClaim;
use App\CampaignsLeadsUsers;
use App\LeadsCustomer;
use App\LeadTrafficSources;
use App\State;
use App\Ticket;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;
use App\Services\ApiMain;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function lead_volume(){
        return view('Reports.lead_volume');
    }

    public function lead_volume_data(Request $request)
    {
        $admin_id = array();
        if( !empty($request->admin_id) ){
            $admin_id = explode(',', $request->admin_id);
        }

        $service_id = array();
        if( !empty($request->service_id) ){
            $service_id = explode(',', $request->service_id);
        }

        $state_id = array();
        if( !empty($request->state_id) ){
            $state_id = explode(',', $request->state_id);
        }

        $county_id = array();
        if( !empty($request->county_id) ){
            $county_id = explode(',', $request->county_id);
        }

        $city_id = array();
        if( !empty($request->city_id) ){
            $city_id = explode(',', $request->city_id);
        }

        $zipcode_id = array();
        if( !empty($request->zipcode_id) ){
            $zipcode_id = explode(',', $request->zipcode_id);
        }

        $trafficSource = array();
        if( !empty($request->trafficSource) ){
            $trafficSource = explode(',', $request->trafficSource);
        }

        $from_date = $request->from_date . " 00:00:00";
        $to_date = $request->to_date . " 23:59:59";

//        $lead_volumes = CampaignsLeadsUsers::join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
//            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
//            ->leftjoin('buyers_claims', function ($join) {
//                $join->on('buyers_claims.buyer_id', '=', 'users.id')
//                    ->where('buyers_claims.is_claim', 1);
//            })

        $lead_volumes = LeadsCustomer::join('states','states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('zip_codes_lists','zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->where('leads_customers.is_duplicate_lead',  "<>", 1 )
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=',"test")
            ->where('leads_customers.lead_fname','!=', "testing")
            ->where('leads_customers.lead_lname','!=',"testing")
            ->where('leads_customers.lead_fname', '!=',"Test")
            ->where('leads_customers.lead_lname','!=',"Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.created_at', '>=', $from_date)
            ->where('leads_customers.created_at', '<=', $to_date);


        if (!empty($service_id)) {
            $lead_volumes->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

//        if (!empty($admin_id)) {
//            $lead_volumes->whereIn('buyers_claims.admin_id', $admin_id);
//        }

        if (!empty($state_id)) {
            $lead_volumes->whereIn('leads_customers.lead_state_id', $state_id);
        }

        if (!empty($county_id)) {
            $lead_volumes->whereIn('leads_customers.lead_county_id', $county_id);
        }

        if (!empty($city_id)) {
            $lead_volumes->whereIn('leads_customers.lead_city_id', $city_id);
        }

        if (!empty($zipcode_id) && empty($request->distance_area)) {
            $lead_volumes->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
        }

        if (!empty($trafficSource)) {
            $lead_volumes->whereIn('leads_customers.google_ts', $trafficSource);
        }

        if (!empty($zipcode_id)) {
            if( count($zipcode_id) == 1 && !empty($request->distance_area) ){
                if( !empty( $zipcode_id[0] ) ){
                    $zipcode_data = DB::table('zip_codes_lists')
                        ->where('zip_code_list_id', $zipcode_id[0])
                        ->first(['zip_code_list']);
                    $zipcode = $zipcode_data->zip_code_list;
                    $distancekm = $request->distance_area;

                    $main_api_file = new ApiMain();
                    $decoded_result = $main_api_file->zipcodes_distance_filter($zipcode, $distancekm);

                    if( !empty($decoded_result['zip_codes']) ){
                        $zipcodes_distance_arr = array_column($decoded_result['zip_codes'], 'zip_code');

                        $lead_volumes->whereIn('zip_codes_lists.zip_code_list', $zipcodes_distance_arr);
                    }
                }
            }
        }

        $lead_volume_data = $lead_volumes->groupBy('states.state_name')->get([
            DB::raw("COUNT(leads_customers.lead_id) AS totallead"),
            "states.state_name AS states",
        ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $count= 0 ;
        $data_Returned = '';

        $data_Returned .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_Returned .= ' id="datatable-buttons" >';
        } else {
            $data_Returned .= ' id="datatable" >';
        }
        $data_Returned .= '<thead>
                            <tr>
                                <th>State</th>
                                <th>Lead#</th>
                            </tr>
                            </thead>
                            <tbody>';
        if( !empty($lead_volume_data) ){
            foreach ( $lead_volume_data as $item ){
                if( !empty($item->sumbid) ){
                    $datasumbid = $item->sumbid;
                } else {
                    $datasumbid = 0;
                }
                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->states  . "</td>";
                $data_Returned .=  "<td>". $item->totallead. "</td>";
                $data_Returned .=  "</tr>";
                $count += $item->totallead ;
            }
        }

        $data_Returned .= "</tbody>
                            <tfoot>
                            <tr>
                             <th>Total</th>
                            <td>$count</td>
                            </tr>
                            </tfoot>
                            </thead>
                        </table>";

        return $data_Returned;
    }

    //==================================================================================================================

    public function performance_reports(){
        return view('Reports.performance_report');
    }

    public function Performance_Reports_data(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $users = User::leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
            ->whereIn('users.role_id', [3,4,6])
            ->get([
                'users.username', 'users.created_at', 'users.id','users.user_business_name', 'acc_manager_users.username AS acc_manager_username'
            ]);

        //Exclusive Data
        $ExclusiveLeads_sum = CampaignsLeadsUsers::where('campaigns_leads_users_type_bid', 'Exclusive')
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->where('is_returned', 0)
            ->groupBy('user_id')
            ->selectRaw('SUM(campaigns_leads_users_bid) AS ExclusiveLeads, user_id as user_id_key')
            ->pluck('ExclusiveLeads', "user_id_key")->toarray();

        $TotalExclusiveLeads = CampaignsLeadsUsers::where('campaigns_leads_users_type_bid', 'Exclusive')
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->where('is_returned', 0)
            ->groupBy('user_id')
            ->selectRaw('COUNT(campaigns_leads_users_id) AS TotalExclusiveLeads, user_id as user_id_key')
            ->pluck('TotalExclusiveLeads', "user_id_key")->toarray();

        //Shared Data
        $SharedeLeads_sum = CampaignsLeadsUsers::where('campaigns_leads_users_type_bid', 'Shared')
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->where('is_returned', 0)
            ->groupBy('user_id')
            ->selectRaw('SUM(campaigns_leads_users_bid) AS SharedeLeads, user_id as user_id_key')
            ->pluck('SharedeLeads', "user_id_key")->toarray();

        $TotalSharedLeads = CampaignsLeadsUsers::where('campaigns_leads_users_type_bid', 'Shared')
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->where('is_returned', 0)
            ->groupBy('user_id')
            ->selectRaw('COUNT(campaigns_leads_users_id) AS TotalSharedLeads, user_id as user_id_key')
            ->pluck('TotalSharedLeads', "user_id_key")->toarray();

        //Return Leads
        $returnLead_sum = Ticket::join('campaigns_leads_users','campaigns_leads_users.campaigns_leads_users_id','=','tickets.campaigns_leads_users_id')
            ->where('tickets.ticket_status', 3)
            ->where('tickets.ticket_type', 2)
            ->where('campaigns_leads_users.is_returned', 1)
            ->where('campaigns_leads_users.date', '>=', $from_date)
            ->where('campaigns_leads_users.date', '<=', $to_date)
            ->groupBy('tickets.user_id')
            ->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS ReturnBidLeads, campaigns_leads_users.user_id as user_id_key')
            ->pluck('ReturnBidLeads', "user_id_key")->toarray();

        $returnLead_count = Ticket::join('campaigns_leads_users','campaigns_leads_users.campaigns_leads_users_id','=','tickets.campaigns_leads_users_id')
            ->where('tickets.ticket_status', 3)
            ->where('tickets.ticket_type', 2)
            ->where('campaigns_leads_users.is_returned', 1)
            ->where('campaigns_leads_users.date', '>=', $from_date)
            ->where('campaigns_leads_users.date', '<=', $to_date)
            ->groupBy('tickets.user_id')
            ->selectRaw('COUNT(tickets.campaigns_leads_users_id) AS totalleadReturend, campaigns_leads_users.user_id as user_id_key')
            ->pluck('totalleadReturend', "user_id_key")->toarray();

        $list_of_return_amount = DB::table('transactions')
            ->where("accept", 1)
            ->where('transactions_comments', 'like', '%Return Leads Amount%')
            ->whereNotNull("transactionauthid")
            ->where('transactionauthid', '>=', $from_date)
            ->where('transactionauthid', '<=', $to_date)
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $data_Returned = '';

        $data_Returned .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_Returned .= ' id="datatable-buttons" >';
        } else {
            $data_Returned .= ' id="datatable" >';
        }
        $data_Returned .= '<thead>
                                <tr>
                                    <th>SoldTo</th>
                                    <th>Account Manager</th>
                                    <th>Number of Shared Leads</th>
                                    <th>Total Shared Bid</th>
                                    <th>Number of Exclusive Leads</th>
                                    <th>Total Exclusive Bid</th>
                                    <th>Number Return Leads</th>
                                    <th>Total Return Leads</th>
                                    <th>Total Return Amounts</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>';
        $sumShared = 0 ;
        $sumTotalShared = 0 ;
        $sumExclusiv = 0 ;
        $sumTotalExclusiv = 0 ;
        $sumReturnBidLeads = 0;
        $sumTotalleadReturend = 0;
        $sumTotalleadReturendAmount = 0;
        if( !empty($users) ){
            foreach ( $users as $item ){
                $TotalSharedLeads_data =  ( !empty($TotalSharedLeads[$item->id]) ? $TotalSharedLeads[$item->id] : 0 );
                $SharedeLeads_sum_data =  ( !empty($SharedeLeads_sum[$item->id]) ? $SharedeLeads_sum[$item->id] : 0 );
                $ExclusiveLeads_sum_data = ( !empty($ExclusiveLeads_sum[$item->id]) ? $ExclusiveLeads_sum[$item->id] : 0 );
                $TotalExclusiveLeads_data = ( !empty($TotalExclusiveLeads[$item->id]) ? $TotalExclusiveLeads[$item->id] : 0 );
                $returnLead_sum_data = ( !empty($returnLead_sum[$item->id]) ? $returnLead_sum[$item->id] : 0 );
                $returnLead_count_data = ( !empty($returnLead_count[$item->id]) ? $returnLead_count[$item->id] : 0 );
                $list_of_return_amount_data = ( !empty($list_of_return_amount[$item->id]) ? $list_of_return_amount[$item->id] : 0 );

                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->user_business_name . "</td>";
                $data_Returned .=  "<td>". $item->acc_manager_username . "</td>";
                $data_Returned .=  "<td>". $TotalSharedLeads_data . "</td>";
                $data_Returned .=  "<td>$". number_format($SharedeLeads_sum_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>". $TotalExclusiveLeads_data . "</td>";
                $data_Returned .=  "<td>$". number_format($ExclusiveLeads_sum_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>". $returnLead_count_data . "</td>";
                $data_Returned .=  "<td>$". number_format($returnLead_sum_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>$". number_format($list_of_return_amount_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>". date('Y-m-d', strtotime($item['created_at'])) . "</td>";
                $data_Returned .=  "</tr>";
                $sumShared              += $SharedeLeads_sum_data;
                $sumTotalShared         += $TotalSharedLeads_data;
                $sumExclusiv            += $ExclusiveLeads_sum_data;
                $sumTotalExclusiv       += $TotalExclusiveLeads_data;
                $sumReturnBidLeads      += $returnLead_sum_data;
                $sumTotalleadReturend   += $returnLead_count_data;
                $sumTotalleadReturendAmount += $list_of_return_amount_data;
            }
        }

        $data_Returned .= "</tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td>". $sumTotalShared  ."</td>
                                    <td>$". number_format($sumShared, 2, '.', ',')  ."</td>
                                    <td>". $sumTotalExclusiv  ."</td>
                                    <td>$".  number_format($sumExclusiv, 2, '.', ',')  ."</td>
                                    <td>". $sumTotalleadReturend  ."</td>
                                    <td>$". number_format($sumReturnBidLeads, 2, '.', ',')  ."</td>
                                    <td>$". number_format($sumTotalleadReturendAmount, 2, '.', ',')  ."</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>";

        return $data_Returned;
    }

    //==================================================================================================================

    public function lead_from_websites(){

        return view('Reports.lead_from_websites');
    }

    public function lead_from_websites_data(Request $request)
    {
        $sold_status  =  $request->sold_status ;

        $service_id = array();
        if( !empty($request->service_id) ){
            $service_id = explode(',', $request->service_id);
        }

        $state_id = array();
        if( !empty($request->state_id) ){
            $state_id = explode(',', $request->state_id);
        }

        $county_id = array();
        if( !empty($request->county_id) ){
            $county_id = explode(',', $request->county_id);
        }

        $city_id = array();
        if( !empty($request->city_id) ){
            $city_id = explode(',', $request->city_id);
        }

        $zipcode_id = array();
        if( !empty($request->zipcode_id) ){
            $zipcode_id = explode(',', $request->zipcode_id);
        }

        $trafficSource = array();
        if( !empty($request->trafficSource) ){
            $trafficSource = explode(',', $request->trafficSource);
        }

        $from_date = $request->from_date . " 00:00:00";
        $to_date = $request->to_date . " 23:59:59";

        $lest_of_lead = array();
        if(!empty($sold_status)){
            if( $sold_status == 1 ){
                $lest_of_lead = CampaignsLeadsUsers::where('is_returned','0')
                    ->pluck('lead_id')->toArray();
            } else if( $sold_status == 2 ){
                $lest_of_lead = CampaignsLeadsUsers::where('is_returned','1')
                    ->pluck('lead_id')->toArray();
            } else if( $sold_status == 3 ) {
                $lest_of_lead = CampaignsLeadsUsers::pluck('lead_id')->toArray();
            }
        }

        $lead_website = LeadsCustomer::where('created_at', '>=', $from_date)
            ->where('is_duplicate_lead',  0 )
            ->where('lead_fname', '!=', "test")
            ->where('lead_lname', '!=',"test")
            ->where('lead_fname','!=', "testing")
            ->where('lead_lname','!=',"testing")
            ->where('lead_fname', '!=',"Test")
            ->where('lead_lname','!=',"Test")
            ->where('is_test', 0)
            ->where('created_at', '<=', $to_date);

        if(!empty($sold_status)){
            if(  $sold_status == 3 ){
                $lead_website->whereNotIn('lead_id', $lest_of_lead );
            } else {
                $lead_website->whereIn('lead_id', $lest_of_lead );
            }
        }

        if (!empty($service_id)) {
            $lead_website->whereIn('lead_type_service_id', $service_id);
        }

        if (!empty($state_id)) {
            $lead_website->whereIn('lead_state_id', $state_id);
        }

        if (!empty($county_id)) {
            $lead_website->whereIn('lead_county_id', $county_id);
        }

        if (!empty($city_id)) {
            $lead_website->whereIn('lead_city_id', $city_id);
        }

        if (!empty($zipcode_id)) {
            $lead_website->whereIn('lead_zipcode_id', $zipcode_id);
        }

        if (!empty($trafficSource)) {
            $lead_website->whereIn('google_ts', $trafficSource);
        }

        $lead_website->groupBy("lead_serverDomain");
        $lead_website = $lead_website->get([
            DB::raw("lead_serverDomain"),
            DB::raw("COUNT(lead_id) AS totallead"),
        ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $data_Returned = '';

        $data_Returned .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_Returned .= ' id="datatable-buttons" >';
        } else {
            $data_Returned .= ' id="datatable" >';
        }
        $data_Returned .= '<thead>
                            <tr>
                                 <td>Website</td>
                                 <td>Number of lead</td>
                            </tr>
                            </thead>
                            <tbody>';

        $totalBid = 0;
        if( !empty($lead_website) ){
            foreach ( $lead_website as $item ){
                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->lead_serverDomain . "</td>";
                $data_Returned .=  "<td>". $item->totallead . "</td>";
                $data_Returned .=  "</tr>";
                $totalBid += $item->totallead;
            }
        }

        $data_Returned .= '</tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td>'. $totalBid .'</td>
                                </tr>
                            </tfoot>

                        </table>';


        return $data_Returned;
    }

    //==================================================================================================================

    public function lead_report(){

        return view('Reports.lead_report');
    }

    public function lead_report_data(Request $request)
    {
        $service_id = array();
        if( !empty($request->service_id) ){
            $service_id = explode(',', $request->service_id);
        }

        $state_id = array();
        if( !empty($request->state_id) ){
            $state_id = explode(',', $request->state_id);
        }

        $county_id = array();
        if( !empty($request->county_id) ){
            $county_id = explode(',', $request->county_id);
        }

        $city_id = array();
        if( !empty($request->city_id) ){
            $city_id = explode(',', $request->city_id);
        }

        $zipcode_id = array();
        if( !empty($request->zipcode_id) ){
            $zipcode_id = explode(',', $request->zipcode_id);
        }

        $buyers_id = array();
        if( !empty($request->buyers) ){
            $buyers_id = explode(',', $request->buyers);
        }

        $trafficSource = array();
        if( !empty($request->trafficSource) ){
            $trafficSource = explode(',', $request->trafficSource);
        }

        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';

        $lest_of_lead= LeadsCustomer::leftJoin('campaigns_leads_users','leads_customers.lead_id','=','campaigns_leads_users.lead_id')
            ->leftJoin('users','users.id','=','campaigns_leads_users.user_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('leads_customers.is_duplicate_lead',  0 )
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=',"test")
            ->where('leads_customers.lead_fname','!=', "testing")
            ->where('leads_customers.lead_lname','!=',"testing")
            ->where('leads_customers.lead_fname', '!=',"Test")
            ->where('leads_customers.lead_lname','!=',"Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.status', 0)
            ->where('leads_customers.created_at', '>=', $from_date)
            ->where('leads_customers.created_at', '<=', $to_date);

        if (!empty($service_id)) {
            $lest_of_lead->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

        if (!empty($state_id)) {

            $lest_of_lead->whereIn('leads_customers.lead_state_id', $state_id);
        }

        if (!empty($county_id)) {
            $lest_of_lead->whereIn('leads_customers.lead_county_id', $county_id);
        }

        if (!empty($city_id)) {
            $lest_of_lead->whereIn('leads_customers.lead_city_id', $city_id);
        }

        if (!empty($zipcode_id)) {
            $lest_of_lead->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
        }

        if (!empty($trafficSource)) {
            $lest_of_lead->whereIn('leads_customers.google_ts', $trafficSource);
        }

        if (!empty($buyers_id)) {
            $lest_of_lead->whereIn('campaigns_leads_users.user_id', $buyers_id);
        }

        $lead_report = $lest_of_lead->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->get([
                DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                "leads_customers.traffic_source AS traffic_source",
                "leads_customers.lead_id AS lead_id",
                "leads_customers.lead_website AS websites",
                "leads_customers.lead_fname AS lead_fname",
                "leads_customers.lead_lname AS lead_lname",
                "leads_customers.lead_serverDomain",
                "leads_customers.created_at",
                "leads_customers.google_ts",
                "states.state_code"
            ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $data_Returned = '';

        $data_Returned .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_Returned .= ' id="datatable-buttons" >';
        } else {
            $data_Returned .= ' id="datatable" >';
        }
        $data_Returned .= '<thead>
                                <tr>
                                    <td>Lead Id</td>
                                    <td>Lead Name</td>
                                    <td>State</td>
                                    <td>SoldTo</td>
                                    <td>Total Bid</td>
                                    <td>Websites</td>
                                    <td>Traffic Source</td>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>';


        if( !empty( $lead_report) ){
            foreach ( $lead_report as $item ){
                $traffic_source = "---";
                if(!empty($item->traffic_source) && !empty($item->google_ts)) {
                    $traffic_source = $item->traffic_source . " - " . $item->google_ts;
                } else {
                    if(!empty($item->traffic_source)) {
                        $traffic_source = $item->traffic_source;
                    } else if(!empty($item->google_ts)) {
                        $traffic_source = $item->google_ts;
                    }
                }
                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->lead_id . "</td>";
                $data_Returned .=  "<td>". $item->lead_fname . " " .$item->lead_lname. "</td>";
                $data_Returned .=  "<td>". $item->state_code . "</td>";
                $data_Returned .=  "<td>". (!empty($item->buyerUser) ?  $item->buyerUser : "---") . "</td>";
                $data_Returned .=  "<td>". (!empty($item->sum_bid) ?  $item->sum_bid : "0") . "</td>";
                $data_Returned .=  "<td>". $item->lead_serverDomain . "</td>";
                $data_Returned .=  "<td>". $traffic_source ."</td>";
                $data_Returned .=  "<td>". $item->created_at . "</td>";
                $data_Returned .=  "</tr>";
            }
        }

        $data_Returned .= '</tbody>
                        </table>';


        return $data_Returned;
    }

    //==================================================================================================================

    public function sales_report(){
        return view('Reports.sales_reports');
    }

    public function sales_report_data(Request $request){
        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';

        $users = User::whereIn('users.role_id', [1,2])
            ->where('account_type', 'Sales')
            ->get(['username', 'created_at', 'id', 'account_type']);

        $table_data = '';
        $table_data .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $table_data .= ' id="datatable-buttons" >';
        } else {
            $table_data .= ' id="datatable" >';
        }
        $table_data .= '<thead>
                <tr>
                    <th>#</th>
                    <th>Admin Name</th>
                    <th>Admin Type</th>
                    <th>Number Of Buyer/Enterprise</th>
                    <th>Number Of Aggregator</th>
                    <th>Number Of Sign up</th>
                    <th>Total Bid</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($users as $key => $user) {
            $table_data .= "<tr>";
            $table_data .= "<td>". ($key+1) . "</td>";
            $table_data .= "<td>". $user->username . "</td>";
            $table_data .= "<td>". $user->account_type . "</td>";

            $number_of_buyers = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sales')
                ->whereIn('users.role_id', [3,6])
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_Aggregator = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sales')
                ->where('users.role_id', 4)
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_signup = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sales')
                ->whereIn('users.role_id', [3,4,6])
                ->where('users.user_visibility', 1)
                ->whereBetween('users.created_at', [$from_date, $to_date])
                ->count();

            $pluck_of_users = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sales')
                ->where('buyers_claims.claim_type', '<>', 'Account Manager')
                ->whereIn('users.role_id', [3,4,6])
                ->where('users.user_visibility', 1)
                ->pluck('buyers_claims.buyer_id')->toArray();

            $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];
            $total_bid = Transaction::whereIn('user_id', $pluck_of_users)
                ->whereBetween('created_at', [$from_date, $to_date])
                ->where('transaction_status', 1)
                ->whereIn('transactions_comments', $transactions_comments)
                ->sum('transactions_value');

            $table_data .= "<td>". $number_of_buyers . "</td>";
            $table_data .= "<td>". $number_of_Aggregator . "</td>";
            $table_data .= "<td>". $number_of_signup . "</td>";
            $table_data .= "<td>". round(( !empty($total_bid) ? $total_bid : 0 ),2) . "</td>";
            $table_data .= "<td>". date('d/M/Y H:i', strtotime($user->created_at)) . "</td>";
            $table_data .= "</tr>";
        }

        $table_data .= "</tbody>
                        </table>";

        return $table_data;
    }

    //==================================================================================================================

    public function sdr_report(){
        return view('Reports.sdr_reports');
    }

    public function sdr_report_data(Request $request){
        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';

        $users = User::whereIn('users.role_id', [1,2])
            ->where('account_type', 'Sdr')
            ->get(['username', 'created_at', 'id', 'account_type']);

        $table_data = '';
        $table_data .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $table_data .= ' id="datatable-buttons" >';
        } else {
            $table_data .= ' id="datatable" >';
        }
        $table_data .= '<thead>
                <tr>
                    <th>#</th>
                    <th>Admin Name</th>
                    <th>Admin Type</th>
                    <th>Number Of Buyer/Enterprise</th>
                    <th>Number Of Aggregator</th>
                    <th>Number Of Sign up</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($users as $key => $user) {
            $table_data .= "<tr>";
            $table_data .= "<td>". ($key+1) . "</td>";
            $table_data .= "<td>". $user->username . "</td>";
            $table_data .= "<td>". $user->account_type . "</td>";

            $number_of_buyers = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sdr')
                ->whereIn('users.role_id', [3,6])
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_Aggregator = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sdr')
                ->where('users.role_id', 4)
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_signup = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Sdr')
                ->whereIn('users.role_id', [3,4,6])
                ->where('users.user_visibility', 1)
                ->whereBetween('users.created_at', [$from_date, $to_date])
                ->count();

            $table_data .= "<td>". $number_of_buyers . "</td>";
            $table_data .= "<td>". $number_of_Aggregator . "</td>";
            $table_data .= "<td>". $number_of_signup . "</td>";
            $table_data .= "<td>". date('d/M/Y H:i', strtotime($user->created_at)) . "</td>";
            $table_data .= "</tr>";
        }

        $table_data .= "</tbody>
                        </table>";

        return $table_data;
    }

    //==================================================================================================================

    public function accountManager_report(){
        return view('Reports.account_manager_reports');
    }

    public function accountManager_report_data(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $users = User::whereIn('users.role_id', [1,2])
            ->where('user_visibility', 1)
            ->where('account_type', 'Account Manager')
            ->get(['username', 'created_at', 'id', 'account_type']);

        $table_data = '';
        $table_data .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%" ';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $table_data .= ' id="datatable-buttons" >';
        } else {
            $table_data .= ' id="datatable" >';
        }
        $table_data .= '<thead>
                <tr>
                    <th>#</th>
                    <th>Admin Name</th>
                    <th>Admin Type</th>
                    <th>Number Of Buyer/Enterprise</th>
                    <th>Number Of Aggregator</th>
                    <th>Number Of Sign up</th>
                    <th>Total Bid</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($users as $key => $user) {
            $table_data .= "<tr>";
            $table_data .= "<td>". ($key+1) . "</td>";
            $table_data .= "<td>". $user->username . "</td>";
            $table_data .= "<td>". $user->account_type . "</td>";

            $number_of_buyers = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Account Manager')
                ->whereIn('users.role_id', [3,6])
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_Aggregator = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Account Manager')
                ->where('users.role_id', 4)
                ->where('users.user_visibility', 1)
                ->count();

            $number_of_signup = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Account Manager')
                ->whereIn('users.role_id', [3,4,6])
                ->where('users.user_visibility', 1)
                ->whereBetween('users.created_at', [$from_date, $to_date])
                ->count();

            $pluck_of_users = BuyersClaim::join('users','users.id','=','buyers_claims.buyer_id')
                ->where('buyers_claims.admin_id', $user->id)
                ->where('buyers_claims.is_claim', 1)
                ->where('buyers_claims.claim_type', 'Account Manager')
                ->whereIn('users.role_id', [3,4,6])
                ->where('users.user_visibility', 1)
                ->pluck('buyers_claims.buyer_id')->toArray();

            $total_bid = CampaignsLeadsUsers::whereIn('user_id', $pluck_of_users)
                ->where('date', '>=', $from_date)
                ->where('date', '<=', $to_date)
                ->where('is_returned', "<>", 1)
                ->sum('campaigns_leads_users_bid');

            $list_of_return_amount = DB::table('transactions')
                ->whereIn('user_id', $pluck_of_users)
                ->where("accept", 1)
                ->where('transactions_comments', 'like', '%Return Leads Amount%')
                ->whereNotNull("transactionauthid")
                ->where('transactionauthid', '>=', $from_date)
                ->where('transactionauthid', '<=', $to_date)
                ->sum('transactions_value');

            $table_data .= "<td>". $number_of_buyers . "</td>";
            $table_data .= "<td>". $number_of_Aggregator . "</td>";
            $table_data .= "<td>". $number_of_signup . "</td>";
            $table_data .= "<td>". round(( !empty($total_bid) ? ( !empty($list_of_return_amount) ? $total_bid - $list_of_return_amount : $total_bid ) : 0 ), 2) . "</td>";
            $table_data .= "<td>". date('d/M/Y H:i', strtotime($user->created_at)) . "</td>";
            $table_data .= "</tr>";
        }

        $table_data .= "</tbody>
                        </table>";

        return $table_data;
    }

    public function marketing_cost(){
        return view('Reports.marketing_cost');
    }

    public function marketing_cost_data(Request $request){
        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';

        $traffic_sources = LeadTrafficSources::join('marketing_platforms', 'marketing_platforms.id', '=', 'lead_traffic_sources.marketing_platform_id')
            ->orderBy('lead_traffic_sources.created_at', 'DESC')
            ->get([
                'lead_traffic_sources.*', 'marketing_platforms.name AS platform_name'
            ]);

        //if lead is sold Data
        $leads_sold_sum = CampaignsLeadsUsers::join('leads_customers', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.created_at', '>=', $from_date)
            ->where('leads_customers.created_at', '<=', $to_date)
            ->where('campaigns_leads_users.is_returned', 0)
            ->groupBy('leads_customers.google_ts')
            ->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS LeadsPrice, leads_customers.google_ts')
            ->pluck('LeadsPrice', "google_ts")->toarray();

        //sum cost all lead
        $all_lead_cost_sum = DB::table('leads_customers')
            ->where('created_at', '>=', $from_date)
            ->where('created_at', '<=', $to_date)
            ->where('status', 0)
            ->where('is_duplicate_lead',"<>", 1)
            ->where('lead_fname', '!=', "test")
            ->where('lead_lname', '!=', "test")
            ->where('lead_fname', '!=', "testing")
            ->where('lead_lname', '!=', "testing")
            ->where('lead_fname', '!=', "Test")
            ->where('lead_lname', '!=', "Test")
            ->where('is_test', 0)
            ->groupBy('google_ts')
            ->selectRaw('SUM(ping_price) AS LeadsPrice, google_ts')
            ->pluck('LeadsPrice', "google_ts")->toarray();

        $TotalLeads = DB::table('leads_customers')
            ->where('created_at', '>=', $from_date)
            ->where('created_at', '<=', $to_date)
            ->where('status', 0)
            ->where('is_duplicate_lead',"<>", 1)
            ->where('lead_fname', '!=', "test")
            ->where('lead_lname', '!=', "test")
            ->where('lead_fname', '!=', "testing")
            ->where('lead_lname', '!=', "testing")
            ->where('lead_fname', '!=', "Test")
            ->where('lead_lname', '!=', "Test")
            ->where('is_test', 0)
            ->groupBy('google_ts')
            ->selectRaw('COUNT(lead_id) AS TotalLeads, google_ts')
            ->pluck('TotalLeads', "google_ts")->toarray();

        $leads_sold_sum = array_change_key_case($leads_sold_sum, CASE_LOWER);
        $all_lead_cost_sum = array_change_key_case($all_lead_cost_sum, CASE_LOWER);
        $TotalLeads = array_change_key_case($TotalLeads, CASE_LOWER);

        $data_Returned = '';
        $data_Returned .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $data_Returned .= ' id="datatable-buttons">';
        } else {
            $data_Returned .= ' id="datatable">';
        }
        $data_Returned .= '<thead>
                                <tr>
                                    <th>TS Name</th>
                                    <th>Platform</th>
                                    <th>Number of Leads</th>
                                    <th>Cost</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>';

        $sumTotalLead = 0 ;
        $sum_all_lead_cost = 0;
        $sum_leads_sold_sum = 0;
        $sum_profit = 0;
        if( !empty($traffic_sources) ){
            foreach ( $traffic_sources as $item ){
                $item_name = strtolower($item->name);

                $TotalLeads_data  =  (!empty($TotalLeads["$item_name"])    ? $TotalLeads["$item_name"] : 0);
                $TotalLeads_data +=  (!empty($TotalLeads["$item_name-r"])  ? $TotalLeads["$item_name-r"] : 0);
                $TotalLeads_data +=  (!empty($TotalLeads["$item_name-r1"]) ? $TotalLeads["$item_name-r1"] : 0);
                $TotalLeads_data +=  (!empty($TotalLeads["$item_name-r2"]) ? $TotalLeads["$item_name-r2"] : 0);

                $all_lead_cost_sum_data  =  (!empty($all_lead_cost_sum["$item_name"])    ? $all_lead_cost_sum["$item_name"] : 0);
                $all_lead_cost_sum_data +=  (!empty($all_lead_cost_sum["$item_name-r"])  ? $all_lead_cost_sum["$item_name-r"] : 0);
                $all_lead_cost_sum_data +=  (!empty($all_lead_cost_sum["$item_name-r1"]) ? $all_lead_cost_sum["$item_name-r1"] : 0);
                $all_lead_cost_sum_data +=  (!empty($all_lead_cost_sum["$item_name-r2"]) ? $all_lead_cost_sum["$item_name-r2"] : 0);

                $leads_sold_sum_data  =  (!empty($leads_sold_sum["$item_name"])    ? $leads_sold_sum["$item_name"] : 0);
                $leads_sold_sum_data +=  (!empty($leads_sold_sum["$item_name-r"])  ? $leads_sold_sum["$item_name-r"] : 0);
                $leads_sold_sum_data +=  (!empty($leads_sold_sum["$item_name-r1"]) ? $leads_sold_sum["$item_name-r1"] : 0);
                $leads_sold_sum_data +=  (!empty($leads_sold_sum["$item_name-r2"]) ? $leads_sold_sum["$item_name-r2"] : 0);

                $profit = $leads_sold_sum_data - $all_lead_cost_sum_data;

                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->name . "</td>";
                $data_Returned .=  "<td>". $item->platform_name . "</td>";
                $data_Returned .=  "<td>". $TotalLeads_data . "</td>";
                $data_Returned .=  "<td>$". number_format($all_lead_cost_sum_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>$". number_format($leads_sold_sum_data, 2, '.', ',') . "</td>";
                $data_Returned .=  "<td>$". number_format($profit, 2, '.', ',') . "</td>";
                $data_Returned .=  "</tr>";

                $sumTotalLead           += $TotalLeads_data;
                $sum_all_lead_cost      += $all_lead_cost_sum_data;
                $sum_leads_sold_sum     += $leads_sold_sum_data;
                $sum_profit             += $profit;
            }
        }

        $data_Returned .= "</tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td>". $sumTotalLead  ."</td>
                                    <td>$". number_format($sum_all_lead_cost, 2, '.', ',')  ."</td>
                                    <td>$". number_format($sum_leads_sold_sum, 2, '.', ',')  ."</td>
                                    <td>$". number_format($sum_profit, 2, '.', ',')  ."</td>
                                </tr>
                            </tfoot>
                        </table>";

        return $data_Returned;
    }


}
