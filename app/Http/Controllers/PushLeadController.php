<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignsLeadsUsers;
use App\LeadsCustomer;
use App\Models\CallCenterSources;
use App\Services\Allied\PingCRMAllied;
use App\Services\AllServicesQuestions;
use App\Services\ApiMain;
use App\Services\APIValidations;
use App\Services\Zone\PingCRMZone;
use App\TotalAmount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AccessLog;
use Illuminate\Support\Facades\Auth;

class PushLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index($id){
        $leadsCustomer = LeadsCustomer::join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->where('leads_customers.lead_id', $id)
            ->first([
                'service__campaigns.service_campaign_name', 'leads_customers.*',
                'states.state_name',  'cities.city_name',  'zip_codes_lists.zip_code_list'
            ]);

        $listof_camp = CampaignsLeadsUsers::where('lead_id', $id)->pluck('campaign_id')->toArray();

        $zipcode_id = $leadsCustomer->lead_zipcode_id;
        $city_id = $leadsCustomer->lead_city_id;
        $county_id = $leadsCustomer->lead_county_id;
        $state_id = $leadsCustomer->lead_state_id;

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $agents = User::whereIn("account_type", ["Call Center", "Lead Review"])->whereIn('users.role_id', [1, 2])->get(['username', "user_business_name"]);

        $call_center_source = CallCenterSources::get(['name']);

        if (in_array($leadsCustomer->lead_source, array(10, 11, 12)) ){
            $campaigns->where('campaigns.service_campaign_id', $leadsCustomer->lead_type_service_id)
                ->where('campaigns.campaign_visibility', 1)
                ->where('campaigns.campaign_status_id', 1)
                ->where('campaigns.is_seller', 0)
                ->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
                    $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
                    $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
                    $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
                    $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
                })
                ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
                ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
                ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id);

            if( !empty($listof_camp) ){
                $campaigns->whereNotIn('campaigns.campaign_id', $listof_camp);
            }

            $campaigns = $campaigns->get([
                'campaigns.*',
                'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status'
            ]);
        }
        else {
            $projectnatureArrayData = array();
            if ($leadsCustomer->lead_installing_id == "") {
                $projectnatureArrayData[] = 1;
                $projectnatureArrayData[] = 2;
                $projectnatureArrayData[] = 3;
            } else if ($leadsCustomer->lead_installing_id != 3) {
                $projectnatureArrayData[] = 1;
                $projectnatureArrayData[] = 2;
            } else {
                $projectnatureArrayData[] = 3;
            }

            $ownershipArrayData = array();
            if ( !isset($leadsCustomer->lead_ownership) || $leadsCustomer->lead_ownership == '3') {
                $ownershipArrayData[] = 0;
                $ownershipArrayData[] = 1;
            } else {
                $ownershipArrayData[] = $leadsCustomer->lead_ownership;
            }

            $property_typeArrayData = array();
            if ($leadsCustomer->property_type_campaign_id == "") {
                if (!empty($leadsCustomer->lead_property_type_roofing_id)) {
                    if ($leadsCustomer->lead_property_type_roofing_id == 1) {
                        $property_typeArrayData[] = 1;
                        $property_typeArrayData[] = 2;
                    } else {
                        $property_typeArrayData[] = 3;
                    }
                } else {
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = $leadsCustomer->property_type_campaign_id;
            }

            //Check Services
            $allQuestions = new AllServicesQuestions();

            $campaigns = $allQuestions->pushLead($campaigns, $leadsCustomer, $ownershipArrayData, $property_typeArrayData, $projectnatureArrayData);

            if (!empty($listof_camp)) {
                $campaigns->whereNotIn('campaigns.campaign_id', $listof_camp);
            }

            $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
                $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
                $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
                $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
                $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
            })
                ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
                ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
                ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
                ->whereNotIn('campaigns.campaign_Type', array(2, 3, 5, 9))
                ->where('campaigns.campaign_visibility', 1)
                ->where('campaigns.campaign_status_id', 1)
                ->where('users.user_visibility', 1)
                ->where('campaigns.service_campaign_id', $leadsCustomer->lead_type_service_id)
                ->where('campaigns.is_seller', 0)
                ->get([
                    'campaigns.*', 'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status'
                ])->unique(['campaign_id']);
        }

        return view('Admin.CampaignLeads.push_lead', compact('leadsCustomer', 'campaigns', 'id', 'agents','call_center_source'));
    }

    public function addLead(Request $request, $id){
        $this->validate($request, [
            'campaign_id' => ['required'],
            'type' => ['required']
        ]);

        $campaign = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->where('campaigns.campaign_id', $request->campaign_id)
            ->first([
                'campaigns.*', 'service__campaigns.service_campaign_name',
                'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
                'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
                'campaign_time_delivery.*', 'total_amounts.total_amounts_value', 'users.user_auto_pay_status', 'users.user_auto_pay_amount'
            ]);
        $service_id = $campaign->service_campaign_id;

        $leadcustomer_info = LeadsCustomer::join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->leftjoin('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->where('lead_id', $id)
            ->first([
                'states.state_name', 'states.state_code', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'leads_customers.*'
            ]);

        $Leaddatadetails = array();
        $dataMassageForBuyers = array(
            'one' => '',
            'two' => '',
            'three' => '',
            'four' => '',
            'five' => '',
            'six' => ''
        );
        if ( !(in_array($leadcustomer_info->lead_source, array(10, 11, 12))) ) {
            $api_validations = new APIValidations();
            $questions = $api_validations->check_questions_ids_push_array($leadcustomer_info);
            $dataMassageForBuyers = $questions['dataMassageForBuyers'];
            $Leaddatadetails = $questions['Leaddatadetails'];
        }

        $campaign_id_curent = $campaign->campaign_id;
        $user_id = $campaign->user_id;
        $buyersEmail = $campaign->email; //from user
        $buyersusername = $campaign->username; //from user
        $service_campaign_name = $campaign->service_campaign_name; //from servece

        $lead_source_api = "ADMS20";
        $lead_source = "SEO";
        $lead_source_id = 1;
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source', 'name')
            ->where('name', $leadcustomer_info->lead_source_text)->first();
        if( !empty($marketing_platform) ){
            $lead_source_api = $marketing_platform->lead_source;
            $lead_source = $marketing_platform->name;
            $lead_source_id = $marketing_platform->id;
        }

        $city_arr = explode('=>', $leadcustomer_info->city_name);
        $county_arr = explode('=>', $leadcustomer_info->county_name);
        $data_msg = array(
            'leadCustomer_id' => $id,
            'name' => $buyersusername,
            'leadName' => $leadcustomer_info->lead_fname . ' ' . $leadcustomer_info->lead_lname,
            'LeadEmail' => $leadcustomer_info->lead_email,
            'LeadPhone' => $leadcustomer_info->lead_phone_number,
            'Address' =>  'Address: ' . $leadcustomer_info->lead_address . ', City: ' . $city_arr[0] . ', State: ' . $leadcustomer_info->state_name . ', Zipcode: ' . $leadcustomer_info->zip_code_list,
            'LeadService' => $service_campaign_name,
            'service_id' => $service_id,
            'data' => $dataMassageForBuyers,
            'street' => $leadcustomer_info->lead_address,
            'City' => trim($city_arr[0]),
            'State' =>  $leadcustomer_info->state_name,
            'state_code' =>  $leadcustomer_info->state_code,
            'Zipcode' =>$leadcustomer_info->zip_code_list,
            'county' => trim($county_arr[0]),
            'first_name' => $leadcustomer_info->lead_fname,
            'last_name' => $leadcustomer_info->lead_lname,
            'trusted_form' => $leadcustomer_info->trusted_form,
            'appointment_date' => "",
            'appointment_type' => '',
            "is_lead_review" => 0,
            'UserAgent' => $leadcustomer_info->lead_aboutUserBrowser,
            'OriginalURL' => $leadcustomer_info->lead_serverDomain,
            'OriginalURL2' => "https://www.".$leadcustomer_info->lead_serverDomain,
            'SessionLength' => $leadcustomer_info->lead_timeInBrowseData,
            'IPAddress' => $leadcustomer_info->lead_ipaddress,
            'LeadId' => $leadcustomer_info->universal_leadid,
            'browser_name' => $leadcustomer_info->lead_browser_name,
            'tcpa_compliant' => $leadcustomer_info->tcpa_compliant,
            'TCPAText' => $leadcustomer_info->tcpa_consent_text,
            'is_multi_service' => $leadcustomer_info->is_multi_service,
            'is_sec_service' => $leadcustomer_info->is_sec_service,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $leadcustomer_info->traffic_source,
            'google_ts' => $leadcustomer_info->google_ts,
            'dataMassageForBuyers' => $dataMassageForBuyers,
            'Leaddatadetails' => $Leaddatadetails,
        );

        //check if ts from call center =====================================
        $agent_name = "";
        $callCenter = 0;
        if( (!empty($leadcustomer_info->google_ts) && in_array($leadcustomer_info->google_ts, array("raf1", "raf2"))) || !empty($request->agent_name)){
            $agent_name = (!empty($request->agent_name) ? $request->agent_name : "VerifiedLR");
            $callCenter = 1;
            if(!empty($request->call_center_source)){
                LeadsCustomer::where('lead_id', $id)->update([ 'traffic_source' => $request->call_center_source]);
            }
        }
        //========================================================================

        $main_api_file = new ApiMain();
        //==================================================================================================
        //PING & POST CRMs
        $ping_approved_arr = array();
        if ($campaign->is_ping_account == 1) {
            if( config('app.name', '') == "Zone1Remodeling" ){
                $ping_and_post_class = new PingCRMZone();
            } else {
                $ping_and_post_class = new PingCRMAllied();
            }

            $type_oflead = ($request->type == 'Exclusive' ? 1 : 2);
            $ping_approved = $ping_and_post_class->pingandpost($campaign, $data_msg, 1, $type_oflead, 0);
            $ping_approved_arr = json_decode($ping_approved, true);

            if ( $ping_approved_arr['Result'] != 1 || $ping_approved_arr['Payout'] < 1 ) {
                \Session::put('error', 'failed to Push Lead; PING Response refused this lead Or the bid is less than $1');
                return redirect()->back();
            }
        }
        //==================================================================================================

        $ping_post_data = array();
        if( empty($ping_approved_arr) ){
            //For return custom Bid
            $custom_bid_campaign = json_decode($campaign->custom_paid_campaign_id, true);

            if( $request->type == 'Exclusive' ){
                $listOFCampainDB_type = 'Exclusive';
                $budget_bid = filter_var($campaign->campaign_budget_bid_exclusive, FILTER_SANITIZE_NUMBER_INT);

                if( $budget_bid == 0 || !in_array(1, $custom_bid_campaign)){
                    \Session::put('error', 'failed to Push Lead; you can only push this lead as a shared lead.');
                    return redirect()->back();
                }
            } else {
                $listOFCampainDB_type = 'Shared';
                $budget_bid = filter_var($campaign->campaign_budget_bid_shared, FILTER_SANITIZE_NUMBER_INT);

                if( $budget_bid == 0 || !in_array(2, $custom_bid_campaign)){
                    \Session::put('error', 'failed to Push Lead; you can only push this lead as an exclusive lead.');
                    return redirect()->back();
                }
            }
            $virtual_price = $campaign->virtual_price;
            $budget_bid = $budget_bid - $virtual_price;
        } else {
            $listOFCampainDB_type = ($request->type == 'Exclusive' ? 'Exclusive' : 'Shared');
            $budget_bid = $ping_approved_arr['Payout'];
            $ping_post_data = $ping_approved_arr;
        }

        $data_msg['ping_post_data'] = $ping_post_data;

        $payment_type_method_status = $campaign->payment_type_method_status; //from user
        $payment_type_method_id = $campaign->payment_type_method_id; //from user
        $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT); //from user

        //=========================Payment Here==========================
        $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

        if( ( $totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0 )
            || ( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8'])
                && abs($totalAmmountUser_value - $budget_bid) <=  $payment_type_method_limit ) ){

            //Time Delivery Validation
            $completeStatus = 1;
            if ($campaign->campaign_time_delivery_status != 1) {
                date_default_timezone_set('UTC');
                $timezone = $campaign->campaign_time_delivery_timezone;
                if( $timezone == 5 ){
                    date_default_timezone_set('America/New_York');
                } else if( $timezone == 6 ){
                    date_default_timezone_set('America/Chicago');
                } else if( $timezone == 7 ){
                    date_default_timezone_set('America/denver');
                } else {
                    date_default_timezone_set('America/Los_Angeles');
                }
                $todayDay = date('D', strtotime(date('Y-m-d H:i:s')));
                $todayHour = date('H:i:s', strtotime(date('Y-m-d H:i:s')));

                if ($todayDay == 'Sun') {
                    if ($campaign->status_sun != 1) {
                        if(date('H:i:s', strtotime($campaign->start_sun)) != date('H:i:s', strtotime($campaign->end_sun))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_sun)) || $todayHour > date('H:i:s', strtotime($campaign->end_sun))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Mon') {
                    if ($campaign->status_mon != 1) {
                        if(date('H:i:s', strtotime($campaign->start_mon)) != date('H:i:s', strtotime($campaign->end_mon))){
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_mon)) || $todayHour > date('H:i:s', strtotime($campaign->end_mon))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Tue') {
                    if ($campaign->status_tus != 1) {
                        if(date('H:i:s', strtotime($campaign->start_tus)) != date('H:i:s', strtotime($campaign->end_tus))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_tus)) || $todayHour > date('H:i:s', strtotime($campaign->end_tus))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Wed') {
                    if ($campaign->status_wen != 1) {
                        if(date('H:i:s', strtotime($campaign->start_wen)) != date('H:i:s', strtotime($campaign->end_wen))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_wen)) || $todayHour > date('H:i:s', strtotime($campaign->end_wen))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Thu') {
                    if ($campaign->status_thr != 1) {
                        if(date('H:i:s', strtotime($campaign->start_thr)) != date('H:i:s', strtotime($campaign->end_thr))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_thr)) || $todayHour > date('H:i:s', strtotime($campaign->end_thr))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Fri') {
                    if ($campaign->status_fri != 1) {
                        if(date('H:i:s', strtotime($campaign->start_fri)) != date('H:i:s', strtotime($campaign->end_fri))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_fri)) || $todayHour > date('H:i:s', strtotime($campaign->end_fri))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Sat') {
                    if ($campaign->status_sat != 1) {
                        if(date('H:i:s', strtotime($campaign->start_sat)) != date('H:i:s', strtotime($campaign->end_sat))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_sat)) || $todayHour > date('H:i:s', strtotime($campaign->end_sat))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                date_default_timezone_set("America/Los_Angeles");
            }

            if( $completeStatus == 0 ){
                \Session::put('error', 'failed to Push Lead!, Please check time delivery for this campaign');
                return redirect()->back();
            }

            //Check Campaign Budget
            if( $request->type == 'Exclusive' ){
                $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                $typeOFBidLead = 'Exclusive';
            } else {
                $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                $typeOFBidLead = 'Shared';
            }

            $completeStatus_final = 0;
            //==================================================================================================
            $leadsCampaignsDailies = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->where('date', date("Y-m-d"))
                ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
                ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m'). '-1',date('Y-m-t')])
                ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $data_is_identical = 0;
            if( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']) ){
                if (abs($totalAmmountUser_value - $budget_bid) <= $payment_type_method_limit) {
                    $data_is_identical = 1;
                }
            } else {
                if( $totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0 ){
                    $data_is_identical = 1;
                }
            }

            if( $data_is_identical == 1 ){
                if( $period_campaign_count_lead_id == $budget_campaign_count_lead_id ){
                    if( $period_campaign_count_lead_id == 1 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    } else  if( $period_campaign_count_lead_id == 2 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    } else {
                        if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    }
                } else {
                    if( $period_campaign_count_lead_id == 1 ){
                        if( $budget_campaign_count_lead_id == 2 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    } else  if( $period_campaign_count_lead_id == 2 ){
                        if( $budget_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    } else {
                        if( $budget_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    }
                }
            }

            if( $completeStatus_final == 0 ){
                \Session::put('error', 'failed to Push Lead!, Please check the daily/weekly/monthly budget for this campaign');
                return redirect()->back();
            }

            //To send CRM data
            $status_of_send_lead = $main_api_file->delivery_methods($data_msg, $campaign, $listOFCampainDB_type, 1);
            $is_ping_account = $campaign->is_ping_account;
            if( $status_of_send_lead != 0 && !empty($status_of_send_lead) ){
                $main_api_file->payment_lead($user_id, $budget_bid);
                $curentDate = date('Y-m-d');
                //=========================Insert Data===========================
                $dataleads = array(
                    'user_id' =>   $user_id,
                    'campaign_id' => $campaign_id_curent,
                    'lead_id' => $id,
                    'curent_date' => $curentDate,
                    'type_bid' => $listOFCampainDB_type,
                    'bid_budget' => $budget_bid,
                    'transactionId' => $status_of_send_lead,
                    'is_recorded' => 0,
                    'agent_name' => $agent_name,
                    'callCenter' => $callCenter
                );
                $leadsCustomerCampaign_id = $main_api_file->AddLeadsCampaignUser($dataleads);

                if( !empty($leadsCustomerCampaign_id) && $leadsCustomerCampaign_id > 0 ){
                    \Session::put('success', 'Lead Pushed successful!');
                } else {
                    \Session::put('error', 'failed to Push Lead!');
                }
            } else {
                \Session::put('error', 'failed to Push Lead; POST response refused this lead!');
            }
            //==============================================================

            // To check if User amount is low or less than $50
            $totalAmmountUser_new_list = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
            $totalAmmountUser_new = (!empty($totalAmmountUser_new_list) ? $totalAmmountUser_new_list->total_amounts_value : 0);

            $status = 0;
            if( $is_ping_account != 1 ){
                if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                    if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                        $status = 1;
                    }
                } else {
                    if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                        $status = 1;
                    }
                }
            } else {
                if ($totalAmmountUser_new <= 150) {
                    $status = 1;
                }
            }

            if (($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {
                if ($totalAmmountUser_new <= 0) {
                    if ($payment_type_method_limit - abs($totalAmmountUser_new) <= 150) {
                        $main_api_file->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                    }
                }
            } else {
                if ($campaign->user_auto_pay_status == 1 && $campaign->user_auto_pay_amount > 0) {
                    if ($totalAmmountUser_new <= 50) {
                        $main_api_file->autopaycampaign($campaign->user_id, $campaign->user_auto_pay_amount);
                    }
                } else {
                    if ($status == 1) {
                        $main_api_file->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                    }
                }
            }
        } else {
            \Session::put('error', 'failed to Push Lead!, Out of Budget!!!');
            return redirect()->back();
        }
        //===============================================================

        $status_response = "";
        if ($message = \Session::get('success')){
            $status_response = $message;
        } elseif($message = \Session::get('error')){
            $status_response = $message;
        }

        $array_data_access_log = array(
            "lead_id" => $id,
            "campaign_id" => $campaign_id_curent,
            "campaign_name" => $campaign->campaign_name,
            "status" => $status_response
        );

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $leadcustomer_info->lead_fname." ".$leadcustomer_info->lead_lname,
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Push Lead',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($array_data_access_log)
        ]);

        return redirect()->back();
    }
}
