<?php

namespace App\Http\Controllers\Api;

use App\LeadsCustomer;
use App\Services\AllServicesQuestions;
use App\Services\ApiMain;
use App\Services\APIValidations;
use App\Services\ServiceQueries;
use App\TotalAmount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeadCallToolsController extends Controller
{
    public function lead_from_call_tools(Request $request)
    {
        $request->headers->set('Accept', 'application/json');
        $this->validate($request, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'zipcode' => ['required'],
            'campaign_id' => ['required'],
            'type' => ['required']
        ]);

        //==============================REMOVED AFTER Zone==================================
        if(!empty($request->campaign_id)){
            $campain_idarr = explode('-', $request->campaign_id);
            $request->campaign_id = trim($campain_idarr[0]);
            $request['campaign_id'] = trim($campain_idarr[0]);
        }

        if(!empty($request->numberofwindows)){
            $windows_number_data = explode('-', $request->numberofwindows);
            $request->numberofwindows = trim($windows_number_data[0]);
            $request['numberofwindows'] = trim($windows_number_data[0]);
        }

        if(!empty($request->projectnature)){
            $project_nature_data = explode('-', $request->projectnature);
            $request['projectnature'] = trim($project_nature_data[0]);
            $request->projectnature = trim($project_nature_data[0]);

            $request['nature_flooring_project'] = trim($project_nature_data[0]);
            $request->nature_flooring_project = trim($project_nature_data[0]);

            $request['nature_of_roofing'] = trim($project_nature_data[0]);
            $request->nature_of_roofing = trim($project_nature_data[0]);

            switch (trim($project_nature_data[0])){
                case 1:
                    $request['nature_of_siding'] = 1;
                    $request->nature_of_siding = 1;
                    break;
                case 2:
                    $request['nature_of_siding'] = 3;
                    $request->nature_of_siding = 3;
                    break;
                default:
                    $request['nature_of_siding'] = 4;
                    $request->nature_of_siding = 4;
            }
        }

        if(!empty($request->priority)){
            $start_time_data = explode('-', $request->priority);
            $request->priority = trim($start_time_data[0]);
            $request['priority'] = trim($start_time_data[0]);
        }

        if(!empty($request->ownership)){
            $ownership_data = explode('-', $request->ownership);
            $request->ownership = trim($ownership_data[0]);
            $request['ownership'] = trim($ownership_data[0]);
        }

        if(!empty($request->solor_solution)){
            $solor_solution_data = explode('-', $request->solor_solution);
            $request->solor_solution = trim($solor_solution_data[0]);
            $request['solor_solution'] = trim($solor_solution_data[0]);
        }

        if(!empty($request->solor_sun)){
            $solor_sun_data = explode('-', $request->solor_sun);
            $request->solor_sun = trim($solor_sun_data[0]);
            $request['solor_sun'] = trim($solor_sun_data[0]);
        }

        if(!empty($request->avg_money)){
            $avg_money_data = explode('-', $request->avg_money);
            $request->avg_money = trim($avg_money_data[0]);
            $request['avg_money'] = trim($avg_money_data[0]);
        }

        if(empty($request->utility_provider)){
            $request->utility_provider = "Other";
            $request['utility_provider'] = "Other";
        }

        if(!empty($request->property_type_c)){
            $property_type_c_data = explode('-', $request->property_type_c);
            $request->property_type_c = trim($property_type_c_data[0]);
            $request['property_type_c'] = trim($property_type_c_data[0]);
        }

        if(!empty($request->type_of_roofing)){
            $type_of_roofing_data = explode('-', $request->type_of_roofing);
            $request->type_of_roofing = trim($type_of_roofing_data[0]);
            $request['type_of_roofing'] = trim($type_of_roofing_data[0]);
        }

        if(!empty($request->bathroom_type)){
            $bathroom_type_data = explode('-', $request->bathroom_type);
            $request->bathroom_type = trim($bathroom_type_data[0]);
            $request['bathroom_type'] = trim($bathroom_type_data[0]);
        }
        //==========================================================================================================

        //To Return last 10 numbers
        $request['phone_number'] = substr($request['phone_number'],-10);

        $api_validations = new APIValidations();
        // Lead Address ==========================================================================
        $address = array();
        $address_state_id = "";
        $address_zip_state_id = "";
        //Check From Array
        $state_arr =  $api_validations->check_state_array(strtoupper($request['state']));
        if( empty($state_arr) ){
            return 'Invalid state value';
        }
        else {
            $address['state_id'] = $state_arr['state_id'];
            $address['state_arr_id'] = array($state_arr['state_id']);
            $address['state_name'] = $state_arr['state_name'];
            $address['state_code'] = $state_arr['state_code'];
            $address_state_id = $state_arr['state_id'];
        }

        //Check Zipcode From Cache
        $zipcode_arr = $api_validations->check_zipcode_cache($request['zipcode']);
        if( empty($zipcode_arr) ){
            //Check Zipcode From DataBase
            $zipcode_arr = $api_validations->check_zipcode($request['zipcode']);
            if( empty($zipcode_arr) ){
                return 'Invalid zipcode value';
            }
            else {
                $address['zipcode_id'] = $zipcode_arr->zip_code_list_id;
                $address['zipcode_arr_id'] = array($zipcode_arr->zip_code_list_id);
                $address['zipcode_arr_name'] = array($zipcode_arr->zip_code_list);
                $address['zip_code_list'] = $zipcode_arr->zip_code_list;
                $address['county_id'] = $zipcode_arr->county_id;
                $address['city_id'] = $zipcode_arr->city_id;
                $address['city_arr_id'] = array($zipcode_arr->city_id);
                $address['zip_state_id'] = $zipcode_arr->state_id;
                $address_zip_state_id = $zipcode_arr->state_id;

                $counties_arr = DB::table('counties')
                    ->where('county_id', $address['county_id'])
                    ->first();
                $address['county_name'] = $counties_arr->county_name;

                $city_arr = DB::table('cities')
                    ->where('city_id', $address['city_id'])
                    ->first();
                $address['city_name'] = $city_arr->city_name;
            }
        } else {
            $zipcode_arr = $zipcode_arr->getData();
            $address['zipcode_id'] = $zipcode_arr->zip_code_list_id;
            $address['zipcode_arr_id'] = array($zipcode_arr->zip_code_list_id);
            $address['zipcode_arr_name'] = array($zipcode_arr->zip_code_list);
            $address['zip_code_list'] = $zipcode_arr->zip_code_list;
            $address['county_id'] = $zipcode_arr->county_id;
            $address['city_id'] = $zipcode_arr->city_id;
            $address['city_arr_id'] = array($zipcode_arr->city_id);
            $address['zip_state_id'] = $zipcode_arr->state_id;
            $address['county_name'] = $zipcode_arr->county_name;
            $address['city_name'] = $zipcode_arr->city_name;
            $address_zip_state_id = $zipcode_arr->state_id;
        }

        if( $address_state_id != $address_zip_state_id ){
            return 'Invalid Location';
        }
        //==============================================================================================================

        //Check if exist Campaign and return service id
        $campaign_id_curent = $request->campaign_id;
        $campaign_service = DB::table('campaigns')
            ->where('campaign_visibility', 1)
            ->where('campaign_status_id', 1)
            ->where('is_seller', 0)
            ->where('campaign_id', $campaign_id_curent)
            ->first(['campaigns.service_campaign_id']);

        if( empty($campaign_service) ){
            return 'No Campaign Found with this Id: ' . $campaign_id_curent;die;
        }
        //Return Service ID
        $service_id = $campaign_service->service_campaign_id;
        //====================================================================

        $appointment_date = '';
        $appointment_type = '';
        if(!(empty($request['appointment_date']))){
            $appointment_date_data = explode('(', $request['appointment_date']);
            if(!empty($appointment_date_data[0])){
                $request['appointment_date'] = $appointment_date_data[0];
            }
        }

        //start check questions ========================================================================
        $request['service_id'] = $service_id;
        $questions = $api_validations->check_questions_ids_array($request);
        $dataMassageForBuyers = $questions['dataMassageForBuyers'];
        $Leaddatadetails = $questions['Leaddatadetails'];
        $LeaddataIDs = $questions['LeaddataIDs'];
        $dataMassageForDB = $questions['dataMassageForDB'];
        //end check questions ==========================================================================

        //Add LeadsCustomer
        $traffic_source = (empty($request['traffic_source']) ? 'Campaign (our system)' : $request['traffic_source']);
        $trusted_form = $request['trusted_form'];

        //Edit on TransactionId id and convert json to array
        $TransactionId_value = (!empty($request['TransactionId']) ? json_decode($request['TransactionId'], true) : array());
        $leadCustomer_id = (!empty($TransactionId_value['lead_id']) ? $TransactionId_value['lead_id'] : "");
        if(empty($leadCustomer_id)){
            $leadsCustomer = new LeadsCustomer();

            $allServicesQues = new AllServicesQuestions();

            $leadsCustomer = $allServicesQues->callToolsLeadsCustomerSave($leadsCustomer, $request, $service_id, $address, $traffic_source, $dataMassageForDB, $appointment_type);

            $result = $leadsCustomer['leadsCustomer'];

            $appointment_type = $leadsCustomer['appointmentType'];

            $result->save();
            $leadCustomer_id = DB::getPdo()->lastInsertId();
        }

        $lead_details = LeadsCustomer::where('lead_id', $leadCustomer_id)->first();

        $lead_source = "Verified";
        $lead_source_api = "ADMV20";
        $lead_source_id = 14;
        if( !empty($lead_details->lead_source_text) ){
            $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source', 'name')
                ->where('name', $lead_details->lead_source_text)->first();
            if( !empty($marketing_platform) ){
                $lead_source_api = $marketing_platform->lead_source;
                $lead_source = $marketing_platform->name;
                $lead_source_id = $marketing_platform->id;
            }
        }

        //For return TransactionId & CallerPhoneNumber from callTools ====================
        $TransactionId = (!empty($TransactionId_value['TransactionId']) ? $TransactionId_value['TransactionId'] : "");
        $CallerPhoneNumber = (!empty($request['CallerPhoneNumber']) ? $request['CallerPhoneNumber'] : "");
        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $address['city_name']);
        $county_arr = explode('=>', $address['county_name']);
        $data_msg = array(
            'leadCustomer_id' => $leadCustomer_id,
            'leadName' => $lead_details->lead_fname . ' ' . $lead_details->lead_lname,
            'LeadEmail' => $lead_details->lead_email,
            'LeadPhone' => $lead_details->lead_phone_number,
            'Address' => 'Address: ' . $lead_details->lead_address . ', City: ' . $city_arr[0] . ', State: ' . $address['state_name'] . ', Zipcode: ' . $address['zip_code_list'],
            'service_id' => $service_id,
            'data' => $dataMassageForBuyers,
            'trusted_form' => $trusted_form,
            'street' => $lead_details->lead_address,
            'City' => trim($city_arr[0]),
            'State' =>  $address['state_name'],
            'state_code' => $address['state_code'],
            'Zipcode' => $address['zip_code_list'],
            'county' => trim($county_arr[0]),
            'first_name' => $lead_details->lead_fname,
            'last_name' => $lead_details->lead_lname,
            'UserAgent' => $lead_details->lead_aboutUserBrowser,
            'OriginalURL' => $lead_details->lead_serverDomain,
            'OriginalURL2' => ( !empty($lead_details->lead_serverDomain ) ? "https://www.".$lead_details->lead_serverDomain : "" ),
            'SessionLength' => $lead_details->lead_timeInBrowseData,
            'IPAddress' => $lead_details->lead_ipaddress,
            'LeadId' => $lead_details->universal_leadid,
            'browser_name' => $lead_details->lead_browser_name,
            'tcpa_compliant' => $lead_details->tcpa_compliant,
            'TCPAText' => $lead_details->tcpa_consent_text,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => "",
            'google_ts' => "",
            'is_multi_service' => $lead_details->is_multi_service,
            'is_sec_service' => $lead_details->is_sec_service,
            'dataMassageForBuyers' => $dataMassageForBuyers,
            'Leaddatadetails' => $Leaddatadetails,
            'LeaddataIDs' => $LeaddataIDs,
            'dataMassageForDB' => $dataMassageForDB,
            'appointment_date' => $appointment_date,
            'appointment_type' => $appointment_type,
            "is_lead_review" => 0,
            "ping_post_data" => array(
                "TransactionId" => $TransactionId,
                "CallerPhoneNumber" => $CallerPhoneNumber
            ),
        );
        //Lead Info =====================================================================================================================

        //Check if Match Lead ==============================================================
        $service_queries = new ServiceQueries();
        $campaign = $service_queries->check_if_match_campaign_callTools($LeaddataIDs, $service_id, $address, $campaign_id_curent);
        if( empty($campaign) ) {
            return "Campaign criteria not match";
        }
        //Check if Match Lead ==============================================================

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
            return 'failed to Push Lead!, Please check time delivery for this campaign';
        }
        //================================================================================

        $user_id = $campaign->user_id;
        $buyersEmail = $campaign->email; //from user
        $buyersusername = $campaign->username; //from user
        $service_campaign_name = $campaign->service_campaign_name; //from servece
        $curentDate = date('Y-m-d');

        $data_msg['name'] = $buyersusername;
        $data_msg['LeadService'] = $service_campaign_name;

        $budget_bid_shared = filter_var($campaign->campaign_budget_bid_shared, FILTER_SANITIZE_NUMBER_INT);
        $budget_bid_ex = filter_var($campaign->campaign_budget_bid_exclusive, FILTER_SANITIZE_NUMBER_INT);
        if( $budget_bid_ex != 0 && $budget_bid_ex >= $budget_bid_shared ){
            $listOFCampainDB_type = 'Exclusive';
            $budget_bid = filter_var($campaign->campaign_budget_bid_exclusive, FILTER_SANITIZE_NUMBER_INT);

            $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
            $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

            $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
            $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;
        } else {
            $listOFCampainDB_type = 'Shared';
            $budget_bid = filter_var($campaign->campaign_budget_bid_shared, FILTER_SANITIZE_NUMBER_INT);

            $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
            $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

            $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
            $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;
        }
        $virtual_price = $campaign->virtual_price;
        $budget_bid = $budget_bid - $virtual_price;
        $payment_type_method_status = $campaign->payment_type_method_status; //from user
        $payment_type_method_id = $campaign->payment_type_method_id; //from user
        $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT); //from user
        $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );
        //=========================Payment Here==========================
        if( ( $totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0 )
            || ( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8'])
                && abs($totalAmmountUser_value - $budget_bid) <=  $payment_type_method_limit ) ){
            //Check Campaign Budget
            $completeStatus_final = 0;
            $leadsCampaignsDailies = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->where('date', date("Y-m-d"))
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m'). '-1',date('Y-m-t')])
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
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
                    } else if( $period_campaign_count_lead_id == 2 ){
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
                return 'failed to Push Lead!, Please check the daily/weekly/monthly budget for this campaign';
            }

            //To send CRM data
            $main_api_file = new ApiMain();
            $status_of_send_lead = $main_api_file->delivery_methods($data_msg, $campaign, $listOFCampainDB_type, 1);
            if( $status_of_send_lead != 0 && !empty($status_of_send_lead) ){
                $is_ping_account = $campaign->is_ping_account;
                $main_api_file->payment_lead($user_id, $budget_bid);
                $dataleads = array(
                    'user_id' =>   $user_id,
                    'campaign_id' => $campaign_id_curent,
                    'lead_id' => $leadCustomer_id,
                    'curent_date' => $curentDate,
                    'type_bid' => $listOFCampainDB_type,
                    'bid_budget' => $budget_bid,
                    'transactionId' => $status_of_send_lead,
                    'is_recorded' => 0,
                    'agent_name' => $request['agent_name'],
                    'callCenter' => 1,
                );
                $main_api_file->AddLeadsCampaignUser($dataleads);

                // E-TopUp ===========================================================================
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
                // E-TopUp ===========================================================================
            } else {
                return "failed to Push Lead; the POST refused this lead!";
            }

            //==============================================================
        } else {
            return "failed to Push Lead!, Out of Budget!!!";
        }
        return "The Lead Has Been Sent Successfully";
    }
}
