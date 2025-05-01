<?php

namespace App\Http\Controllers\Api\Jobs;

use App\Http\Controllers\Controller;
use App\Services\ApiMain;
use App\Services\APIValidations;
use App\Services\ServiceQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevShareJobController extends Controller
{
    public function __construct(){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index(){
        $ListOfLeads = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->where('leads_customers.is_duplicate_lead', 0)
            ->where('leads_customers.status', 0)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=',"test")
            ->where('leads_customers.lead_fname','!=', "testing")
            ->where('leads_customers.lead_lname','!=',"testing")
            ->where('leads_customers.lead_fname', '!=',"Test")
            ->where('leads_customers.lead_lname','!=',"Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.created_at', '>=', date('Y-m-d H', strtotime('-12 hours')) . ':00:00')
            ->where('leads_customers.created_at', '<=', date('Y-m-d H', strtotime('-12 hours')) . ':59:59')
            ->where(function ($query) {
                $query->whereNull('leads_customers.response_data');
                $query->orWhereIn('leads_customers.response_data', array(
                    "All buyers have rejected this lead", "Sold", "Not Match", "Lead Accepted", "Visitor is suspicious!"
                ));
            })
            ->get([
                'leads_customers.*', 'service__campaigns.service_campaign_name',
                'states.state_name', 'states.state_code', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list'
            ]);

        $main_api_file = new ApiMain();
        $api_validations = new APIValidations();
        $service_queries = new ServiceQueries();

        foreach ($ListOfLeads as $lead){
            //Return Questions
            $questions = $api_validations->check_questions_ids_push_array($lead);
            $dataMassageForBuyers = $questions['dataMassageForBuyers'];
            $Leaddatadetails = $questions['Leaddatadetails'];
            $LeaddataIDs = $questions['LeaddataIDs'];
            $dataMassageForDB = $questions['dataMassageForDB'];

            //Return Address
            $address = array(
                "county_id" => $lead->lead_county_id,
                "county_name" => $lead->county_name,

                "zipcode_id" => $lead->lead_zipcode_id,
                "zip_code_list" => $lead->zip_code_list,

                "city_id" => $lead->lead_city_id,
                "city_name" => $lead->city_name,

                "state_id" => $lead->lead_state_id,
                "state_name" => $lead->state_name,
                "state_code" => $lead->state_code
            );

            $city_arr = explode('=>', $lead->city_name);
            $county_arr = explode('=>', $lead->county_name);
            $data_msg = array(
                'leadCustomer_id' => $lead->lead_id,
                'name' => "",
                'leadName' => $lead->lead_fname . ' ' . $lead->lead_lname,
                'LeadEmail' => $lead->lead_email,
                'LeadPhone' => $lead->lead_phone_number,
                'Address' =>  'Address: ' . $lead->lead_address . ', City: ' . $city_arr[0] . ', State: ' . $lead->state_name . ', Zipcode: ' . $lead->zip_code_list,
                'LeadService' => $lead->service_campaign_name,
                'service_id' => $lead->lead_type_service_id,
                'data' => $dataMassageForBuyers,
                'street' => $lead->lead_address,
                'City' => trim($city_arr[0]),
                'State' =>  $lead->state_name,
                'state_code' =>  $lead->state_code,
                'Zipcode' =>$lead->zip_code_list,
                'county' => trim($county_arr[0]),
                'first_name' => $lead->lead_fname,
                'last_name' => $lead->lead_lname,
                'trusted_form' => $lead->trusted_form,
                'appointment_date' => "",
                'appointment_type' => '',
                "is_lead_review" => 0,
                'UserAgent' => $lead->lead_aboutUserBrowser,
                'OriginalURL' => $lead->lead_serverDomain,
                'OriginalURL2' => "https://www.".$lead->lead_serverDomain,
                'SessionLength' => $lead->lead_timeInBrowseData,
                'IPAddress' => $lead->lead_ipaddress,
                'LeadId' => $lead->universal_leadid,
                'browser_name' => $lead->lead_browser_name,
                'tcpa_compliant' => $lead->tcpa_compliant,
                'TCPAText' => $lead->tcpa_consent_text,
                'is_multi_service' => $lead->is_multi_service,
                'is_sec_service' => $lead->is_sec_service,
                'lead_source' => "",
                'lead_source_name' => "",
                'lead_source_id' => "",
                'traffic_source' => $lead->traffic_source,
                'google_ts' => $lead->google_ts,
                'dataMassageForBuyers' => $dataMassageForBuyers,
                'Leaddatadetails' => $Leaddatadetails,
            );

            //Select List Of Campaign
            $listOFCampaigns = $service_queries->service_queries_new_way($lead->lead_type_service_id, $LeaddataIDs,  2, 0, $address, "", 2);
            foreach ($listOFCampaigns as $campaign){
                $campaign_id = $campaign->campaign_id;
                $user_id = $campaign->user_id;
                $buyersUsername = $campaign->username; //from user
                $data_msg['name'] = $buyersUsername;
                $typeOFBidLead = 'Shared';

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
                    return 0;
                }

                //To send CRM data
                $status_of_send_lead = $main_api_file->delivery_methods($data_msg, $campaign, $typeOFBidLead, 1);
                if( $status_of_send_lead != 0 && !empty($status_of_send_lead) ) {
                    //=========================Insert Data===========================
                    $dataleads = array(
                        'user_id' => $user_id,
                        'campaign_id' => $campaign_id,
                        'lead_id' => $lead->lead_id,
                        'curent_date' => date('Y-m-d'),
                        'type_bid' => $typeOFBidLead,
                        'bid_budget' => 0,
                        'transactionId' => $status_of_send_lead,
                        'is_recorded' => 0,
                        'agent_name' => "",
                        'callCenter' => ""
                    );

                    $main_api_file->AddLeadsCampaignUser($dataleads);
                }
            }
        }
        return 1;
    }
}
