<?php

namespace App\Http\Controllers\Api\PingPost;

use App\LeadsCustomer;
use App\Services\AllServicesQuestions;
use App\Services\CrmApi;
use App\TotalAmount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\PingLeads;
use App\Services\ServiceQueries;
use Carbon\Carbon;
use App\Services\ApiMain;
use App\Services\APIValidations;
use Illuminate\Support\Facades\Log;

class MainApiController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    //Ping
    public function ping(Request $request){
        // set headers as application/json
        // validate request data comes from api
        // Check OF Campaign ID + Key code are correct
        // Re-format UserAgent
        // Check TCPA Content (tcpa_compliant , tcpa_compliant_text)
        // check state and zipCode
        // Check Vendor ID if correct
        // check questions if valid
        // save ping lead to DB
        // Check if any Campaign match ping lead details
        //
        $request->headers->set('Accept', 'application/json');

        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_key' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zipcode' => ['required'],
        ]);

        $is_set_error = 0;
        $error_log = array();
        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => '',
            'transaction_id' => '',
            'price' => '0.00'
        );

        //Check OF Campaign ID + Key
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code['error'] = 'Invalid campaign_id or campaign_key value';
            return response()->json($response_code);
        }

        //hash_legs_sold Required if is_shared == 1
        $hash_legs_sold = "";
        if( $request->is_shared == 1 ){
            $this->validate($request, [
                'hash_legs_sold' => ['required']
            ]);

            if( is_array($request['hash_legs_sold']) ){
                $hash_legs_sold = json_encode($request['hash_legs_sold']);
            } else {
                if( is_array(json_decode($request['hash_legs_sold'], true)) ){
                    $hash_legs_sold = $request['hash_legs_sold'];
                }
            }
        }

        $request['UserAgent'] = (!empty($request['UserAgent']) ? $request['UserAgent']  : "");
        $request['UserAgent'] = str_replace("#", "%23", $request['UserAgent']);
        $request['UserAgent'] = str_replace('"', "'", $request['UserAgent']);
        $request['UserAgent'] = str_replace('&', "%26", $request['UserAgent']);
        $request['UserAgent'] = str_replace('[]', "", $request['UserAgent']);

        //Check TCPA Content if null
        $request->tcpa_consent_text = str_replace("#", "%23", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('"', "'", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('&', "%26", $request->tcpa_consent_text);
        if( !empty($request['tcpa_compliant']) ){
            if( $request['tcpa_compliant'] == 1 ){
                if( empty($request->tcpa_consent_text)){
                    $response_code['error'] = 'The tcpa_consent_text is empty';
                    return response()->json($response_code);
                }
            }
        }

        $service = $request->service;
        $lead_website = 'Third Party';
        $main_api_file = new ApiMain();
        $api_validations = new APIValidations();

        // Lead Address ==========================================================================
        $address = array();
        $address_state_id = "";
        $address_zip_state_id = "";
        //Check From Array
        $state_arr =  $api_validations->check_state_array(strtoupper($request['state']));
        if( empty($state_arr) ){
            $error_log[] = 'Invalid state value';
            $is_set_error = 1;
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
                $error_log[] = 'Invalid zipcode value';
                $is_set_error = 1;
            } else {
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
        }
        else {
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
            $error_log[] = 'Invalid Location';
            $is_set_error = 1;
        }

        if( $is_set_error == 1 ){
            $response_code['error'] = $error_log;
            return response()->json($response_code);
        }
        // Lead Address ==========================================================================

        //Check Of Vendor Id ==============================================================
        $is_valid_vendor_id = $api_validations->check_vendor_id($request['vendor_id'], $service);
        if( empty($is_valid_vendor_id) ) {
            $response_code['error'] = 'Invalid Vendor Id';
            return response()->json($response_code);
        }
        //Check Of Vendor Id ==============================================================

        //start check questions ==========================================================================
        $questions = $api_validations->check_questions_array($request, $service);
        if( $questions['valid'] == 2 ){
            $response_code['error'] = 'Invalid service value';
            return response()->json($response_code);
        }
        else if( $questions['valid'] == 3 ){
            $response_code['error'] = $questions['error'];
            return response()->json($response_code);
        }
        //end check questions ==========================================================================

        //Add PingLeads
        $pingLeads = new PingLeads();

        $pingLeads->lead_address = $request['street'];
        $pingLeads->lead_state_id = $address['state_id'];
        $pingLeads->lead_city_id = $address['city_id'];
        $pingLeads->lead_zipcode_id = $address['zipcode_id'];
        $pingLeads->lead_county_id = $address['county_id'];
        $pingLeads->lead_ipaddress = $request['ip_address'];
        $pingLeads->lead_type_service_id = $service;
        $pingLeads->trusted_form = $request['trusted_form'];
        $pingLeads->lead_website = $lead_website;
        $pingLeads->lead_timeInBrowseData = $request['SessionLength'];
        $pingLeads->lead_serverDomain = $request['OriginalURL'];
        $pingLeads->lead_FullUrl = $request['OriginalURL'];
        $pingLeads->lead_aboutUserBrowser = $request['UserAgent'];
        $pingLeads->lead_browser_name = $request['browser_name'];
        $pingLeads->universal_leadid = $request['lead_id'];
        $pingLeads->created_at = date('Y-m-d H:i:s');
        $pingLeads->price = "0.00";
        $pingLeads->original_price = "0.00";
        $pingLeads->ping_post_data_arr = json_encode(array());
        $pingLeads->campaign_id_arr = json_encode(array());
        $pingLeads->vendor_id = $request['vendor_id'];
        $pingLeads->lead_details_text = $questions['data_arr']['dataMassageForDB'];
        $pingLeads->hash_legs_sold = $hash_legs_sold;
        $pingLeads->tcpa_compliant = ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0);
        $pingLeads->tcpa_consent_text = $request->tcpa_consent_text;

        //Get TS
        $lead_source = trim($is_valid_vendor_id->typeOFLead_Source);
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source')
            ->where('name', $lead_source)->first();
        $lead_source_id = $marketing_platform->id;
        $lead_source_api = $marketing_platform->lead_source;
        $pingLeads->lead_source = $lead_source_id;
        $pingLeads->lead_source_text = $lead_source;

        //if test Lead
        if ($request['is_test'] == 1 ){
            $pingLeads->status = "Test Lead";
            $pingLeads->is_test = 1;
        }

        $servcesFunct = new AllServicesQuestions();

        $pingLeads = $servcesFunct->saveQuesAnswersInDb($pingLeads, $questions, $service);

        $pingLeads->save();
        $pingLeads_id = $pingLeads->lead_id;
        //save transaction id value on ping table
        $transaction_id = md5($pingLeads_id . "-" . time());
        DB::table('ping_leads')->where('lead_id', $pingLeads_id)->update(["transaction_id" => $transaction_id]);

        //Check if this Test Lead ==============================================================
        if ($request['is_test'] == 1 ){
            $response_code = array(
                'response_code' => 'true',
                'message' => 'Lead Accepted',
                'error' => '',
                'transaction_id' => $transaction_id,
                'price' => '1.00'
            );

            return response()->json($response_code);
        }
        //Check if this Test Lead ==============================================================

//        if( config('app.name', '') == "Zone1Remodeling" ){
//            $vendors_users_id = array(11);
//        } else {
//            $vendors_users_id = array(152);
//        }
//        if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id)) {
//            //Jornaya ID Validations1=========================================================================
//            $check_lead_id = $api_validations->check_lead_id($request->lead_id);
//            if ($check_lead_id == "false") {
//                $response_code['error'] = 'Invalid Universal LeadID';
//                PingLeads::where('lead_id', $pingLeads_id)->update([
//                    "status" => 'Invalid Universal LeadID'
//                ]);
//                return response()->json($response_code);
//            }
//            //Jornaya ID Validations1=========================================================================
//        }

        //Check if Match Lead ==============================================================
        $if_campaign_is_set = $this->check_if_match_campaign($questions['data_arr']['LeaddataIDs'], $service, $address, $request['vendor_id'], $request['sub_id']);
        // return $if_campaign_is_set;
        if( empty($if_campaign_is_set) ) {
            PingLeads::where('lead_id', $pingLeads_id)->update([
                "status" => 'Not Match'
            ]);

            $response_code['error'] = 'Not Match';
            return response()->json($response_code);
        }
        //Check if Match Lead ==============================================================



        //Shearing regarding budget ====================================================================================
        $period_campaign_count_lead_id =  $if_campaign_is_set->period_campaign_count_lead_id_exclusive;
        $numberOfLeadCampaign = filter_var($if_campaign_is_set->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

        $leadsCampaignsDailies = DB::table('campaigns_leads_users_affs')
            ->where('is_returned', '<>', 1)
            ->where('vendor_id_aff', $request['vendor_id']);

        $exceeded_allowed_number = 0;
        if( $period_campaign_count_lead_id == 1 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->where('date', date("Y-m-d"))->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        }
        else if( $period_campaign_count_lead_id == 2 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        }
        else if( $period_campaign_count_lead_id == 3 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        }

        if( $exceeded_allowed_number == 1 || $numberOfLeadCampaign == 0 || empty($numberOfLeadCampaign) ){
            PingLeads::where('lead_id', $pingLeads_id)->update([
                "status" => "you've exceeded the allowed number of leads"
            ]);

            $response_code['error'] = "you've exceeded the allowed number of leads";
            return response()->json($response_code);
        }
        //Shearing regarding budget ====================================================================================

        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $address['city_name']);
        $county_arr = explode('=>', $address['county_name']);
        $data_msg = array(
            'leadCustomer_id' => $pingLeads_id,
            'leadName' => $request['first_name'] . ' ' . $request['last_name'],
            'LeadEmail' => $request['email'],
            'LeadPhone' => $request['phone_number'],
            'Address' => 'Address: ' . $request['street'] . ', City: ' . $city_arr[0] . ', State: ' . $address['state_name'] . ', Zipcode: ' . $address['zip_code_list'],
            'service_id' => $service,
            'data' => $questions['data_arr']['dataMassageForBuyers'],
            'trusted_form' => $request['trusted_form'],
            'street' => $request['street'],
            'City' => $city_arr[0],
            'State' => $address['state_name'],
            'state_code' => $address['state_code'],
            'Zipcode' => $address['zip_code_list'],
            'county' => $county_arr[0],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'UserAgent' => $request['UserAgent'],
            'OriginalURL' => $request['OriginalURL'],
            'OriginalURL2' => $request['OriginalURL'],
            'SessionLength' => $request['SessionLength'],
            'IPAddress' => $request['ip_address'],
            'LeadId' => $request['lead_id'],
            'browser_name' => $request['browser_name'],
            'tcpa_compliant' => ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0),
            'TCPAText' => $request->tcpa_consent_text,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $request['sub_id'],
            'google_ts' => $request['sub_id'],
            'is_multi_service' => 0,
            'is_sec_service' => 0,
            'dataMassageForBuyers' => $questions['data_arr']['dataMassageForBuyers'],
            'Leaddatadetails' => $questions['data_arr']['Leaddatadetails'],
            'LeaddataIDs' => $questions['data_arr']['LeaddataIDs'],
            'dataMassageForDB' => $questions['data_arr']['dataMassageForDB'],
            'appointment_date' => '',
            'appointment_type' => '',
            "is_lead_review" => 0
        );
        //Lead Info =====================================================================================================================

        //Select List Of Campaign
        $service_queries = new ServiceQueries();
        $questions['data_arr']['LeaddataIDs']['if_seller_api'] = 1;
        $questions['data_arr']['LeaddataIDs']['seller_id'] = $is_valid_vendor_id->user_id;
        $questions['data_arr']['LeaddataIDs']['hash_legs_sold'] = (!empty($hash_legs_sold) ? json_decode($hash_legs_sold, true) : "" );

        $listOFCampain_sharedDB = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 2, 0, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
        $campaigns_list_direct_sh = $listOFCampain_sharedDB->pluck('campaign_id')->toArray();

        $listOFCampain_pingDB_sh = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 2, 1, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
        $campaigns_list_ping_sh = $listOFCampain_pingDB_sh->pluck('campaign_id')->toArray();

        $campaigns_list_sh = array_merge($campaigns_list_direct_sh, $campaigns_list_ping_sh);

        if( $request->is_shared != 1 ){
            $listOFCampain_exclusiveDB = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 1, 0, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
            $campaigns_list_direct_ex = $listOFCampain_exclusiveDB->pluck('campaign_id')->toArray();

            $listOFCampain_pingDB_ex = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 1, 1, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
            $campaigns_list_ping_ex = $listOFCampain_pingDB_ex->pluck('campaign_id')->toArray();

            $campaigns_list_ex = array_merge($campaigns_list_direct_ex, $campaigns_list_ping_ex);
        } else {
            $listOFCampain_exclusiveDB['campaigns'] = array();
            $listOFCampain_pingDB_ex['campaigns'] = array();
            $campaigns_list_ex = array();
        }

        //Filtration
        $leadsCampaignsDailiesExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->where('date', date("Y-m-d"))
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsWeeklyExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsMonthlyExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsDailiesShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->where('date', date("Y-m-d"))
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsWeeklyShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsMonthlyShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] = json_decode($leadsCampaignsDailiesExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive'] = json_decode($leadsCampaignsWeeklyExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] = json_decode($leadsCampaignsMonthlyExclusive,true);

        $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] = json_decode($leadsCampaignsDailiesShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] = json_decode($leadsCampaignsWeeklyShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] = json_decode($leadsCampaignsMonthlyShared,true);

        $listOFCampainDB_array_shared = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_sharedDB, $data_msg, 10, 2, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        $listOFCampainDB_array_ping_sh = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_sh, $data_msg, 2, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        if( $request['is_shared'] != 1 ){
            $listOFCampainDB_array_exclusive = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_exclusiveDB, $data_msg, 5, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
            $listOFCampainDB_array_ping_ex = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_ex, $data_msg, 1, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        } else {
            $listOFCampainDB_array_exclusive['campaigns'] = array();
            $listOFCampainDB_array_ping_ex['campaigns'] = array();
            $listOFCampainDB_array_ping_ex['response'] = array();
        }

        //multi pings api responses
        $crm_api_file = new CrmApi();
        $multi_pings_api_responses_sh = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_sh['response']);
        if( $request['is_shared'] != 1 ){
            $multi_pings_api_responses_ex = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_ex['response']);
        } else {
            $multi_pings_api_responses_ex['campaigns'] = array();
            $multi_pings_api_responses_ex['response'] = array();
        }

        $campaigns_sh = array_merge($listOFCampainDB_array_shared['campaigns'],$multi_pings_api_responses_sh['campaigns']);
        $campaigns_ex = array_merge($listOFCampainDB_array_exclusive['campaigns'],$multi_pings_api_responses_ex['campaigns']);
        $ping_post_arr = array_merge($multi_pings_api_responses_ex['response'],$multi_pings_api_responses_sh['response']);

        //Sort Campaign By Bid
        $campaigns_sh = collect($campaigns_sh);
        $campaigns_sh_sorted = $campaigns_sh->sortByDesc('campaign_budget_bid_shared');
        $campaigns_ex = collect($campaigns_ex);
        $campaigns_ex_sorted = $campaigns_ex->sortByDesc('campaign_budget_bid_exclusive');

        //Get Column Bid + Filtration + Sum
        $campaigns_sh_col = $campaigns_sh_sorted->pluck('campaign_budget_bid_shared')->slice(0, 3)->sum();
        $campaigns_ex_col = $campaigns_ex_sorted->pluck('campaign_budget_bid_exclusive')->slice(0,1)->sum();

        //Who is larger exclusive or shared?
        if( $campaigns_sh_col >= $campaigns_ex_col ){
            $listOFCampainDB_type = 'Shared';
            $listOFCampainDB = $campaigns_sh_sorted;
            $count_of_lead = 5;
        } else {
            $listOFCampainDB_type = 'Exclusive';
            $listOFCampainDB = $campaigns_ex_sorted;
            $count_of_lead = 2;
        }

        $response_code = $this->check_ping_if_sold($listOFCampainDB, $listOFCampainDB_type, $if_campaign_is_set, $pingLeads_id, $transaction_id, $ping_post_arr, $address['state_id'], $count_of_lead, $data_msg['google_ts']);
        return response()->json($response_code);
    }

    //Post
    public function post(Request $request){

        $request->headers->set('Accept', 'application/json');
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_key' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zipcode' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required'],
            'email' => ['required', 'string', 'max:255'],
            'transaction_id' => ['required', 'string', 'max:255'],
            'trusted_form' => ['required'],
            'lead_id' => ['required'],
            'start_time',
            'is_test',
            'ip_address' => ['required'],
            'UserAgent'  => ['required'],
            'OriginalURL' => ['required'],
            'SessionLength',
            'browser_name',
            'is_shared',
            'tcpa_compliant'  => ['required'],
            'tcpa_consent_text'  => ['required'],
        ]);

        $is_set_error = 0;
        $error_log = array();
        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => '',
            'transaction_id' => '',
            'price' => '0.00'
        );

        //Check OF Campaign ID + Key
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code['error'] = 'Invalid campaign_id or campaign_key value';
            return response()->json($response_code);
        }

        //hash_legs_sold Required if is_shared == 1
        $hash_legs_sold = "";
        if( $request->is_shared == 1 ){
            $this->validate($request, [
                'hash_legs_sold' => ['required']
            ]);

            if( is_array($request['hash_legs_sold']) ){
                $hash_legs_sold = json_encode($request['hash_legs_sold']);
            } else {
                if( is_array(json_decode($request['hash_legs_sold'], true)) ){
                    $hash_legs_sold = $request['hash_legs_sold'];
                } else {
//                    $response_code['error'] = 'Invalid hash_legs_sold structure value';
//                    return response()->json($response_code);
                }
            }
        }

        $request['UserAgent'] = (!empty($request['UserAgent']) ? $request['UserAgent']  : "");
        $request['UserAgent'] = str_replace("#", "%23", $request['UserAgent']);
        $request['UserAgent'] = str_replace('"', "'", $request['UserAgent']);
        $request['UserAgent'] = str_replace('&', "%26", $request['UserAgent']);
        $request['UserAgent'] = str_replace('[]', "", $request['UserAgent']);

//        $request['UserAgent'] = isset($request['UserAgent'])
//            ? str_replace(['#', '"', '&', '[]'], ['%23', "'", '%26', ''], $request['UserAgent'])
//            : '';

        //Check TCPA Content if null
        $request->tcpa_consent_text = str_replace("#", "%23", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('"', "'", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('&', "%26", $request->tcpa_consent_text);
        if( !empty($request['tcpa_compliant']) ){
            if( $request['tcpa_compliant'] == 1 ){
                if( empty($request->tcpa_consent_text)){
                    $response_code['error'] = 'The tcpa_consent_text is empty';
                    return response()->json($response_code);
                }
            }
        }


//        // Normalize TCPA consent text
//        if (isset($request->tcpa_consent_text)) {
//            $request->tcpa_consent_text = str_replace(['#', '"', '&'], ['%23', "'", '%26'], $request->tcpa_consent_text
//            );
//        }
//
//        // Validate TCPA compliance
//        if (!empty($request->tcpa_compliant) && $request->tcpa_compliant == 1 && empty($request->tcpa_consent_text)) {
//            return response()->json(['error' => 'The tcpa_consent_text is empty']);
//        }


        $service = $request->service;
        $lead_website = 'Third Party';
        $main_api_file = new ApiMain();
        $api_validations = new APIValidations();

        // Lead Address ==========================================================================
        $address = array();
        $address_state_id = "";
        $address_zip_state_id = "";
        //Check From Array
        $state_arr =  $api_validations->check_state_array(strtoupper($request['state']));
        if( empty($state_arr) ){
            $error_log[] = 'Invalid state value';
            $is_set_error = 1;
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
                $error_log[] = 'Invalid zipcode value';
                $is_set_error = 1;
            } else {
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
        }
        else {
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
            $error_log[] = 'Invalid Location';
            $is_set_error = 1;
        }

        if( $is_set_error == 1 ){
            $response_code['error'] = $error_log;
            return response()->json($response_code);
        }
        // Lead Address ==========================================================================

        //Check Of Vendor Id ==============================================================
        $is_valid_vendor_id = $api_validations->check_vendor_id($request['vendor_id'], $service);
        if( empty($is_valid_vendor_id) ) {
            $response_code['error'] = 'Invalid Vendor Id';
            return response()->json($response_code);
        }
        //Check Of Vendor Id ==============================================================

        //start window questions ==========================================================================
        $questions = $api_validations->check_questions_array($request, $service);
        if( $questions['valid'] == 2 ){
            $response_code['error'] = 'Invalid service value';
            return response()->json($response_code);
        }
        else if( $questions['valid'] == 3 ){
            $response_code['error'] = $questions['error'];
            return response()->json($response_code);
        }
        //end window questions ==========================================================================

        //Check If duplicate Leads
        $is_set_lead = LeadsCustomer::where(function ($query) use($request) {
            $query->where('lead_phone_number', $request['phone_number']);
            $query->OrWhere('lead_email', $request->email);
        })->first();

        //Add POST Lead
        $postLeads = new LeadsCustomer();

        $postLeads->lead_fname = $request['first_name'];
        $postLeads->lead_lname = $request['last_name'];
        $postLeads->lead_phone_number = $request['phone_number'];
        $postLeads->lead_email = $request['email'];
        $postLeads->lead_address = $request['street'];
        $postLeads->lead_state_id = $address['state_id'];
        $postLeads->lead_city_id = $address['city_id'];
        $postLeads->lead_zipcode_id = $address['zipcode_id'];
        $postLeads->lead_county_id = $address['county_id'];
        $postLeads->lead_ipaddress = $request['ip_address'];
        $postLeads->lead_type_service_id = $service;
        $postLeads->trusted_form = $request['trusted_form'];
        $postLeads->lead_website = $lead_website;
        $postLeads->lead_details_text = $questions['data_arr']['dataMassageForDB'];;
        $postLeads->lead_timeInBrowseData = $request['SessionLength'];
        $postLeads->lead_serverDomain = $request['OriginalURL'];
        $postLeads->lead_FullUrl = $request['OriginalURL'];
        $postLeads->lead_aboutUserBrowser = $request['UserAgent'];
        $postLeads->lead_browser_name = $request['browser_name'];
        $postLeads->universal_leadid = $request['lead_id'];
        $postLeads->created_at = date('Y-m-d H:i:s');
        $postLeads->token = $request['transaction_id'];
        $postLeads->vendor_id = $request['vendor_id'];
        $postLeads->hash_legs_sold = $hash_legs_sold;

        //Get TS
        $lead_source = trim($is_valid_vendor_id->typeOFLead_Source);
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source')
            ->where('name', $lead_source)->first();
        $lead_source_id = $marketing_platform->id;
        $lead_source_api = $marketing_platform->lead_source;
        $postLeads->lead_source = $lead_source_id;
        $postLeads->lead_source_text = $lead_source;
        $postLeads->traffic_source = $request['sub_id'];
        $postLeads->google_ts = $request['sub_id'];
        $postLeads->google_c = $request['sub_id2'];
        $postLeads->google_g = $request['sub_id3'];
        $postLeads->google_k = $request['sub_id4'];

        $postLeads->tcpa_compliant = ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0);
        $postLeads->tcpa_consent_text = $request->tcpa_consent_text;

        if ( $request['is_test'] == 1 || strtolower($request['first_name']) == 'test' || strtolower($request['last_name']) == 'test' ){
            $postLeads->status = "Test Lead";
            $postLeads->response_data = 'Test Lead';
            $postLeads->is_test = 1;
        } elseif( !empty($is_set_lead) ) {
            $postLeads->is_duplicate_lead = 1;
            $postLeads->response_data = 'Duplicated Lead';
        }

        $servcesFunct = new AllServicesQuestions();

        $postLeads = $servcesFunct->saveQuesAnswersInDb($postLeads, $questions, $service);

        $postLeads->save();
        // $postLeads_id = DB::getPdo()->lastInsertId();
        $postLeads_id = $postLeads->lead_id;

        //Check if Valid Transaction Id =================================================================
        $lead_details_ping_check_transaction_id = PingLeads::where('transaction_id', $request->transaction_id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(15)->toDateTimeString())
            ->first();
        if( empty($lead_details_ping_check_transaction_id) ){
            LeadsCustomer::where('lead_id', $postLeads_id)->update([
                "response_data" => 'Invalid transaction_id Or expired'
            ]);

            $response_code['error'] = 'Invalid transaction_id Or expired';
            return response()->json($response_code);
        }
        LeadsCustomer::where('lead_id', $postLeads_id)->update([
            "lead_ping_id" => $lead_details_ping_check_transaction_id->lead_id
        ]);
        //Check if Valid Transaction Id =================================================================

        //Check if this Test Lead ==============================================================
        if ( $request['is_test'] == 1 || strtolower($request['first_name']) == 'test' || strtolower($request['last_name']) == 'test' ){
            $response_code = array(
                'response_code' => 'true',
                'message' => 'Lead Accepted',
                'error' => '',
                'transaction_id' => $request->transaction_id,
                'price' => '1.00'
            );

            return response()->json($response_code);
        }
        //Check if this Test Lead ==============================================================

        //Check if this Duplicated Lead ==============================================================
        if( !empty($is_set_lead) ) {
            $response_code['error'] = 'Duplicated Lead';
            return response()->json($response_code);
        }
        //Check if this Duplicated Lead ==============================================================

        //start content info ==========================================================================
        if( config('app.name', '') == "Zone1Remodeling" ){
            $vendors_users_id = array(11);
            $vendors_users_id2_ip = array();
            $vendors_users_id3_tf = array();
        } else {
            $vendors_users_id = array(630);
            $vendors_users_id2_ip = array();
            $vendors_users_id3_tf = array(1131);
        }
        if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id)) {
            //Name Validations
            $name_validations_msg = $api_validations->name_validations($request->first_name, $request->last_name);
            if ($name_validations_msg != "true") {
                $response_code['error'] = $name_validations_msg;
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => $name_validations_msg
                ]);

                return response()->json($response_code);
            }

            //Email Validations
            $email_validations_msg = $api_validations->email_validation($request->email);
            if ($email_validations_msg != "true") {
                $response_code['error'] = $email_validations_msg;
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => $email_validations_msg
                ]);

                return response()->json($response_code);
            }

//            //Jornaya ID Validations
//            $check_lead_id = $api_validations->check_lead_id($request->lead_id);
//            if ($check_lead_id == "false") {
//                $response_code['error'] = 'Invalid Universal LeadID';
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => 'Invalid Universal LeadID'
//                ]);
//
//                return response()->json($response_code);
//            }

            //IPQS User Info
//            $leadDataArray = array(
//                "first_name" => $request['first_name'],
//                "last_name" => $request['last_name'],
//                "phone_number" => $request['phone_number'],
//                "email" => $request['email'],
//                "street" => $request['street'],
//                "ip_address" => $request['ip_address'],
//                "UserAgent" => $request['UserAgent'],
//                "state_name" => $address['state_name'],
//                "zip_code_list" => $address['zip_code_list'],
//                "billing_country" => "USA",
//                "fast" => "false",
//                "strictness" => 1,
//                "lighter_penalties" => "true",
//                "allow_public_access_points" => "true",
//                "apiToken" => "xvS204UnNAuBABLERblpdHHXEvx50t8X"
//            );
//
//            $ipqs_lead_details_validation = $api_validations->lead_details_validation_ipqs($leadDataArray);
//            if ($ipqs_lead_details_validation != "true") {
//                $response_code['error'] = $ipqs_lead_details_validation;
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => $ipqs_lead_details_validation
//                ]);
//
//                return response()->json($response_code);
//            }

            // //IPQS IP Validation
            // if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id2_ip)) {
            //     $lead_ip_validation = $api_validations->lead_ip_validation_ipqs($request->ip_address);
            //     if ($lead_ip_validation != "true"){
            //         $response_code['error'] = $lead_ip_validation;
            //         LeadsCustomer::where('lead_id', $postLeads_id)->update([
            //             "response_data" => $lead_ip_validation
            //         ]);
            //         return response()->json($response_code);
            //     }
            // }


            //ipregistry IP Validation
//                $ipregistry_validation = $api_validations->ipregistry_validation($request->ip_address);
//
//                if ($ipregistry_validation != "true") {
//                    $response_code['error'] = $lead_ip_validation;
//                    LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                        "response_data" => $lead_ip_validation
//                    ]);
//                    return response()->json($response_code);
//                }


            //Phone Validations
//            $phone_validations_msg = $api_validations->phone_validations($request['phone_number']);
//            if ($phone_validations_msg != "true") {
//                $response_code['error'] = $phone_validations_msg;
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => $phone_validations_msg
//                ]);
//
//                return response()->json($response_code);
//            }
            //trestleiq Validations
//            $trestleiq_validation = $api_validations->trestleiq_validation($request->phone_number,$request->email,$request->first_name,$request->last_name);
//            if ($trestleiq_validation != "true") {
//                $response_code['error'] = $trestleiq_validation;
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => $trestleiq_validation
//                ]);
//
//                return response()->json($response_code);
//            }

            //Trusted Form Audit
//            if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id3_tf)) {
//                $trusted_form_audit_msg = $api_validations->trusted_form_audit($request['trusted_form']);
//                if ($trusted_form_audit_msg != "true") {
//                    $response_code['error'] = $trusted_form_audit_msg;
//                    LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                        "response_data" => $trusted_form_audit_msg
//                    ]);
//
//                    return response()->json($response_code);
//                }
//            }
        }
        //end content info ==========================================================================

        //Get Ping Lead data By Transaction ID ==================================================================
        $lead_details_ping = PingLeads::where('lead_id', $lead_details_ping_check_transaction_id->lead_id)
            ->where('lead_zipcode_id', $address['zipcode_id'])
            ->where('lead_type_service_id', $service)
            ->first();

        if(empty($lead_details_ping)){
            $response_code['error'] = "Not Match";
            LeadsCustomer::where('lead_id', $postLeads_id)->update([
                "response_data" => "Not Match"
            ]);

            return response()->json($response_code);
        }

        //Insert Lead Price, response, campaign id, and bid type
        LeadsCustomer::where('lead_id', $postLeads_id)->update([
            "ping_price" => $lead_details_ping->price,
            "ping_original_price" => $lead_details_ping->original_price,
            "ping_response_data" => $lead_details_ping->status,
            "ping_campaign_id_arr" => $lead_details_ping->campaign_id_arr,
            "ping_lead_bid_type" => $lead_details_ping->lead_bid_type
        ]);
        //=================================================================

        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $address['city_name']);
        $county_arr = explode('=>', $address['county_name']);
        $data_msg = array(
            'leadCustomer_id' => $postLeads_id,
            'leadName' => $request['first_name'] . ' ' . $request['last_name'],
            'LeadEmail' => $request['email'],
            'LeadPhone' => $request['phone_number'],
            'Address' => 'Address: ' . $request['street'] . ', City: ' . $city_arr[0] . ', State: ' . $address['state_name'] . ', Zipcode: ' . $address['zip_code_list'],
            'service_id' => $service,
            'data' => $questions['data_arr']['dataMassageForBuyers'],
            'trusted_form' => $request['trusted_form'],
            'street' => $request['street'],
            'City' => $city_arr[0],
            'State' => $address['state_name'],
            'state_code' => $address['state_code'],
            'Zipcode' => $address['zip_code_list'],
            'county' => $county_arr[0],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'UserAgent' => $request['UserAgent'],
            'OriginalURL' => $request['OriginalURL'],
            'OriginalURL2' => $request['OriginalURL'],
            //'OriginalURL2' => "https://www.".$request['OriginalURL'],
            'SessionLength' => $request['SessionLength'],
            'IPAddress' => $request['ip_address'],
            'LeadId' => $request['lead_id'],
            'browser_name' => $request['browser_name'],
            'tcpa_compliant' => ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0),
            'TCPAText' => $request->tcpa_consent_text,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $request['sub_id'],
            'google_ts' => $request['sub_id'],
            'is_multi_service' => 0,
            'is_sec_service' => 0,
            'dataMassageForBuyers' => $questions['data_arr']['dataMassageForBuyers'],
            'Leaddatadetails' => $questions['data_arr']['Leaddatadetails'],
            'LeaddataIDs' => $questions['data_arr']['LeaddataIDs'],
            'dataMassageForDB' => $questions['data_arr']['dataMassageForDB'],
            'appointment_date' => '',
            'appointment_type' => '',
            "is_lead_review" => 0,
            'oldNumber' => $request['phone_number'],
            'newNumber' => "",
            'seller_id' => $is_valid_vendor_id->user_id
        );
        //Lead Info =====================================================================================================================

        $response_code = $main_api_file->check_post_if_sold_and_send($lead_details_ping, $data_msg, $request->transaction_id);

        return response()->json($response_code);
    }

    //Direct POST
    public function direct_post(Request $request){
        $request->headers->set('Accept', 'application/json');
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_key' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zipcode' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required'],
            'email' => ['required', 'string', 'max:255'],
            'trusted_form' => ['required'],
            'lead_id' => ['required'],
        ]);

        $is_set_error = 0;
        $error_log = array();
        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => '',
            'transaction_id' => '',
            'price' => '0.00'
        );

        //Check OF Campaign ID + Key
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code['error'] = 'Invalid campaign_id or campaign_key value';
            return response()->json($response_code);
        }

        //hash_legs_sold Required if is_shared == 1
        $hash_legs_sold = "";
        if( $request->is_shared == 1 ){
            $this->validate($request, [
                'hash_legs_sold' => ['required']
            ]);

            if( is_array($request['hash_legs_sold']) ){
                $hash_legs_sold = json_encode($request['hash_legs_sold']);
            } else {
                if( is_array(json_decode($request['hash_legs_sold'], true)) ){
                    $hash_legs_sold = $request['hash_legs_sold'];
                } else {
//                    $response_code['error'] = 'Invalid hash_legs_sold structure value';
//                    return response()->json($response_code);
                }
            }
        }

        $request['UserAgent'] = (!empty($request['UserAgent']) ? $request['UserAgent']  : "");
        $request['UserAgent'] = str_replace("#", "%23", $request['UserAgent']);
        $request['UserAgent'] = str_replace('"', "'", $request['UserAgent']);
        $request['UserAgent'] = str_replace('&', "%26", $request['UserAgent']);
        $request['UserAgent'] = str_replace('[]', "", $request['UserAgent']);

        //Check TCPA Content if null
        $request->tcpa_consent_text = str_replace("#", "%23", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('"', "'", $request->tcpa_consent_text);
        $request->tcpa_consent_text = str_replace('&', "%26", $request->tcpa_consent_text);
        if( !empty($request['tcpa_compliant']) ){
            if( $request['tcpa_compliant'] == 1 ){
                if( empty($request->tcpa_consent_text)){
                    $response_code['error'] = 'The tcpa_consent_text is empty';
                    return response()->json($response_code);
                }
            }
        }

        $service = $request->service;
        $lead_website = 'Third Party';
        $main_api_file = new ApiMain();
        $api_validations = new APIValidations();

        // Lead Address ==========================================================================
        $address = array();
        $address_state_id = "";
        $address_zip_state_id = "";
        //Check From Array
        $state_arr =  $api_validations->check_state_array(strtoupper($request['state']));
        if( empty($state_arr) ){
            $error_log[] = 'Invalid state value';
            $is_set_error = 1;
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
                $error_log[] = 'Invalid zipcode value';
                $is_set_error = 1;
            } else {
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
        }
        else {
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
            $error_log[] = 'Invalid Location';
            $is_set_error = 1;
        }

        if( $is_set_error == 1 ){
            $response_code['error'] = $error_log;
            return response()->json($response_code);
        }
        // Lead Address ==========================================================================

        //Check Of Vendor Id ==============================================================
        $is_valid_vendor_id = $api_validations->check_vendor_id($request['vendor_id'], $service);
        if( empty($is_valid_vendor_id) ) {
            $response_code['error'] = 'Invalid Vendor Id';
            return response()->json($response_code);
        }
        //Check Of Vendor Id ==============================================================

        //start window questions ==========================================================================
        $questions = $api_validations->check_questions_array($request, $service);
        if( $questions['valid'] == 2 ){
            $response_code['error'] = 'Invalid service value';
            return response()->json($response_code);
        }
        else if( $questions['valid'] == 3 ){
            $response_code['error'] = $questions['error'];
            return response()->json($response_code);
        }
        //end window questions ==========================================================================

        //Check If duplicate Leads
        $is_set_lead = LeadsCustomer::where(function ($query) use($request) {
            $query->where('lead_phone_number', $request['phone_number']);
            $query->OrWhere('lead_email', $request->email);
        })->first();

        //Add POST Lead
        $postLeads = new LeadsCustomer();

        $postLeads->lead_fname = $request['first_name'];
        $postLeads->lead_lname = $request['last_name'];
        $postLeads->lead_phone_number = $request['phone_number'];
        $postLeads->lead_email = $request['email'];
        $postLeads->lead_address = $request['street'];
        $postLeads->lead_state_id = $address['state_id'];
        $postLeads->lead_city_id = $address['city_id'];
        $postLeads->lead_zipcode_id = $address['zipcode_id'];
        $postLeads->lead_county_id = $address['county_id'];
        $postLeads->lead_ipaddress = $request['ip_address'];
        $postLeads->lead_type_service_id = $service;
        $postLeads->trusted_form = $request['trusted_form'];
        $postLeads->lead_website = $lead_website;
        $postLeads->lead_details_text = $questions['data_arr']['dataMassageForDB'];;
        $postLeads->lead_timeInBrowseData = $request['SessionLength'];
        $postLeads->lead_serverDomain = $request['OriginalURL'];
        $postLeads->lead_FullUrl = $request['OriginalURL'];
        $postLeads->lead_aboutUserBrowser = $request['UserAgent'];
        $postLeads->lead_browser_name = $request['browser_name'];
        $postLeads->universal_leadid = $request['lead_id'];
        $postLeads->created_at = date('Y-m-d H:i:s');
        $postLeads->vendor_id = $request['vendor_id'];
        $postLeads->hash_legs_sold = $hash_legs_sold;

        //Get TS
        $lead_source = trim($is_valid_vendor_id->typeOFLead_Source);
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source')
            ->where('name', $lead_source)->first();
        $lead_source_id = $marketing_platform->id;
        $lead_source_api = $marketing_platform->lead_source;
        $postLeads->lead_source = $lead_source_id;
        $postLeads->lead_source_text = $lead_source;
        $postLeads->traffic_source = $request['sub_id'];
        $postLeads->google_ts = $request['sub_id'];

        $postLeads->tcpa_compliant = ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0);
        $postLeads->tcpa_consent_text = $request->tcpa_consent_text;

        if ( $request['is_test'] == 1 || strtolower($request['first_name']) == 'test' || strtolower($request['last_name']) == 'test' ){
            $postLeads->status = "Test Lead";
            $postLeads->response_data = 'Test Lead';
            $postLeads->is_test = 1;
        } elseif( !empty($is_set_lead) ) {
            $postLeads->is_duplicate_lead = 1;
            $postLeads->response_data = 'Duplicated Lead';
        }

        $servcesFunct = new AllServicesQuestions();

        $postLeads = $servcesFunct->saveQuesAnswersInDb($postLeads, $questions, $service);

        $postLeads->save();
        // $postLeads_id = DB::getPdo()->lastInsertId();
        $postLeads_id = $postLeads->lead_id;

        $transaction_id = md5($postLeads_id . "-" . time());
        LeadsCustomer::where('lead_id', $postLeads_id)->update(["token" => $transaction_id]);
        $response_code['transaction_id'] = $transaction_id;

        //Check if this Test Lead ==============================================================
        if ( $request['is_test'] == 1 || strtolower($request['first_name']) == 'test' || strtolower($request['last_name']) == 'test' ){
            $response_code = array(
                'response_code' => 'true',
                'message' => 'Lead Accepted',
                'error' => '',
                'transaction_id' => $transaction_id,
                'price' => '1.00'
            );

            return response()->json($response_code);
        }
        //Check if this Test Lead ==============================================================

        //Check if this Duplicated Lead ==============================================================
        if( !empty($is_set_lead) ) {
            $response_code['error'] = 'Duplicated Lead';
            return response()->json($response_code);
        }
        //Check if this Duplicated Lead ==============================================================

        //start content info ==========================================================================
        if( config('app.name', '') == "Zone1Remodeling" ){
            $vendors_users_id = array(11);
            $vendors_users_id2_ip = array();
        }
        else {
            $vendors_users_id = array();
            $vendors_users_id2_ip = array(546);
        }
        if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id)) {
            //Name Validations
            $name_validations_msg = $api_validations->name_validations($request->first_name, $request->last_name);
            if ($name_validations_msg != "true") {
                $response_code['error'] = $name_validations_msg;
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => $name_validations_msg
                ]);

                return response()->json($response_code);
            }

            //Email Validations
            $email_validations_msg = $api_validations->email_validation($request->email);
            if ($email_validations_msg != "true") {
                $response_code['error'] = $email_validations_msg;
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => $email_validations_msg
                ]);

                return response()->json($response_code);
            }

            //Jornaya ID Validations
            $check_lead_id = $api_validations->check_lead_id($request->lead_id);
            if ($check_lead_id == "false") {
                $response_code['error'] = 'Invalid Universal LeadID';
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => 'Invalid Universal LeadID'
                ]);

                return response()->json($response_code);
            }

            // //IPQS IP Validation
            // if(!in_array($is_valid_vendor_id->user_id, $vendors_users_id2_ip)) {
            //     $lead_ip_validation = $api_validations->lead_ip_validation_ipqs($request->ip_address);
            //     if ($lead_ip_validation != "true"){
            //         $response_code['error'] = $lead_ip_validation;
            //         LeadsCustomer::where('lead_id', $postLeads_id)->update([
            //             "response_data" => $lead_ip_validation
            //         ]);

            //         return response()->json($response_code);
            //     }
            // }


//                //ipregistry IP Validation
//                $ipregistry_validation = $api_validations->ipregistry_validation($request['ipaddress']);
//                if ($ipregistry_validation != "true") {
//                    $response_code['error'] = $lead_ip_validation;
//                    LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                        "response_data" => $lead_ip_validation
//                    ]);
//
//                    return response()->json($response_code);
//                }

            //Phone Validations
//            $phone_validations_msg = $api_validations->phone_validations($request['phone_number']);
//            if ($phone_validations_msg != "true") {
//                $response_code['error'] = $phone_validations_msg;
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => $phone_validations_msg
//                ]);
//
//                return response()->json($response_code);
//            }

            //trestleiq Validations
            $validation_phone_with_name=0;
            if(in_array($is_valid_vendor_id->user_id, $vendors_users_id2_ip)) {
                $validation_phone_with_name=1;
            }

            $trestleiq_validation = $api_validations->trestleiq_validation($request->phone_number,$request->email,$request->first_name,$request->last_name,$validation_phone_with_name);
            if ($trestleiq_validation != "true") {
                $response_code['error'] = $trestleiq_validation;
                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => $trestleiq_validation
                ]);

                return response()->json($response_code);
            }

            //Trusted Form Audit
//            $trusted_form_audit_msg = $api_validations->trusted_form_audit($request['trusted_form']);
//            if ($trusted_form_audit_msg != "true") {
//                $response_code['error'] = $trusted_form_audit_msg;
//                LeadsCustomer::where('lead_id', $postLeads_id)->update([
//                    "response_data" => $trusted_form_audit_msg
//                ]);
//
//                return response()->json($response_code);
//            }
        }
        //end content info ==========================================================================

        //Check if Match Lead ==============================================================
        $if_campaign_is_set = $this->check_if_match_campaign($questions['data_arr']['LeaddataIDs'], $service, $address, $request['vendor_id'], $request['sub_id'], 0);
        if( empty($if_campaign_is_set) ) {
            LeadsCustomer::where('lead_id', $postLeads_id)->update([
                "response_data" => 'Not Match'
            ]);

            $response_code['error'] = 'Not Match';
            return response()->json($response_code);
        }
        //Check if Match Lead ==============================================================

        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $address['city_name']);
        $county_arr = explode('=>', $address['county_name']);
        $data_msg = array(
            'leadCustomer_id' => $postLeads_id,
            'leadName' => $request['first_name'] . ' ' . $request['last_name'],
            'LeadEmail' => $request['email'],
            'LeadPhone' => $request['phone_number'],
            'Address' => 'Address: ' . $request['street'] . ', City: ' . $city_arr[0] . ', State: ' . $address['state_name'] . ', Zipcode: ' . $address['zip_code_list'],
            'LeadService' => $if_campaign_is_set->service_campaign_name,
            'service_id' => $service,
            'data' => $questions['data_arr']['dataMassageForBuyers'],
            'trusted_form' => $request['trusted_form'],
            'street' => $request['street'],
            'City' => $city_arr[0],
            'State' => $address['state_name'],
            'state_code' => $address['state_code'],
            'Zipcode' => $address['zip_code_list'],
            'county' => $county_arr[0],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'UserAgent' => $request['UserAgent'],
            'OriginalURL' => $request['OriginalURL'],
            'OriginalURL2' => $request['OriginalURL'],
            //'OriginalURL2' => "https://www.".$request['OriginalURL'],
            'SessionLength' => $request['SessionLength'],
            'IPAddress' => $request['ip_address'],
            'LeadId' => $request['lead_id'],
            'browser_name' => $request['browser_name'],
            'tcpa_compliant' => ($request['tcpa_compliant'] == 1 ?  $request['tcpa_compliant'] : 0),
            'TCPAText' => $request->tcpa_consent_text,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $request['sub_id'],
            'google_ts' => $request['sub_id'],
            'is_multi_service' => 0,
            'is_sec_service' => 0,
            'dataMassageForBuyers' => $questions['data_arr']['dataMassageForBuyers'],
            'Leaddatadetails' => $questions['data_arr']['Leaddatadetails'],
            'LeaddataIDs' => $questions['data_arr']['LeaddataIDs'],
            'dataMassageForDB' => $questions['data_arr']['dataMassageForDB'],
            'appointment_date' => '',
            'appointment_type' => '',
            "is_lead_review" => 0,
            'oldNumber' => $request['phone_number'],
            'newNumber' => "",
            'seller_id' => $is_valid_vendor_id->user_id
        );
        //Lead Info =====================================================================================================================

        //Shearing regarding budget ====================================================================================
        $period_campaign_count_lead_id =  $if_campaign_is_set->period_campaign_count_lead_id_exclusive;
        $numberOfLeadCampaign = filter_var($if_campaign_is_set->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

        $leadsCampaignsDailies = DB::table('campaigns_leads_users_affs')
            ->where('is_returned', '<>', 1)
            ->where('vendor_id_aff', $request['vendor_id']);

        $exceeded_allowed_number = 0;
        if( $period_campaign_count_lead_id == 1 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->where('date', date("Y-m-d"))->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        } else if( $period_campaign_count_lead_id == 2 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        } else if( $period_campaign_count_lead_id == 3 ){
            $leadsCampaignsDailies = $leadsCampaignsDailies->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])->count();
            if( $leadsCampaignsDailies >= $numberOfLeadCampaign ){
                $exceeded_allowed_number = 1;
            }
        }

        if( $exceeded_allowed_number == 1 || $numberOfLeadCampaign == 0 || empty($numberOfLeadCampaign) ){
            LeadsCustomer::where('lead_id', $postLeads_id)->update([
                "response_data" => "you've exceeded the allowed number of leads"
            ]);

            $response_code['error'] = "you've exceeded the allowed number of leads";
            return response()->json($response_code);
        }
        //Shearing regarding budget ====================================================================================

        //Select List Of Campaign
        $service_queries = new ServiceQueries();
        $questions['data_arr']['LeaddataIDs']['if_seller_api'] = 1;
        $questions['data_arr']['LeaddataIDs']['seller_id'] = $is_valid_vendor_id->user_id;
        $questions['data_arr']['LeaddataIDs']['hash_legs_sold'] = (!empty($hash_legs_sold) ? json_decode($hash_legs_sold, true) : "" );
        $listOFCampain_sharedDB = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 2, 0, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
        $campaigns_list_direct_sh = $listOFCampain_sharedDB->pluck('campaign_id')->toArray();

        $listOFCampain_pingDB_sh = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 2, 1, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
        $campaigns_list_ping_sh = $listOFCampain_pingDB_sh->pluck('campaign_id')->toArray();

        $campaigns_list_sh = array_merge($campaigns_list_direct_sh, $campaigns_list_ping_sh);
        if( $request->is_shared != 1 ){
            $listOFCampain_exclusiveDB = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 1, 0, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
            $campaigns_list_direct_ex = $listOFCampain_exclusiveDB->pluck('campaign_id')->toArray();

            $listOFCampain_pingDB_ex = $service_queries->service_queries_new_way($service, $questions['data_arr']['LeaddataIDs'], 1, 1, $address, $lead_source, 0, $request['sub_id'], $request['OriginalURL']);
            $campaigns_list_ping_ex = $listOFCampain_pingDB_ex->pluck('campaign_id')->toArray();

            $campaigns_list_ex = array_merge($campaigns_list_direct_ex, $campaigns_list_ping_ex);
        } else {
            $listOFCampain_exclusiveDB['campaigns'] = array();
            $listOFCampain_pingDB_ex['campaigns'] = array();
            $campaigns_list_ex = array();
        }

        //Filtration
        $leadsCampaignsDailiesExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->where('date', date("Y-m-d"))
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsWeeklyExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsMonthlyExclusive = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])
            ->where('campaigns_leads_users_type_bid','Exclusive')
            ->whereIn('campaign_id', $campaigns_list_ex)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsDailiesShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->where('date', date("Y-m-d"))
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsWeeklyShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsMonthlyShared = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m'). '-1', date('Y-m-t')])
            ->where('campaigns_leads_users_type_bid','Shared')
            ->whereIn('campaign_id', $campaigns_list_sh)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] = json_decode($leadsCampaignsDailiesExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive'] = json_decode($leadsCampaignsWeeklyExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] = json_decode($leadsCampaignsMonthlyExclusive,true);

        $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] = json_decode($leadsCampaignsDailiesShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] = json_decode($leadsCampaignsWeeklyShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] = json_decode($leadsCampaignsMonthlyShared,true);

        $listOFCampainDB_array_shared = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_sharedDB, $data_msg, 10, 2, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        $listOFCampainDB_array_ping_sh = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_sh, $data_msg, 2, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        if( $request['is_shared'] != 1 ){
            $listOFCampainDB_array_exclusive = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_exclusiveDB, $data_msg, 5, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
            $listOFCampainDB_array_ping_ex = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_ex, $data_msg, 1, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        } else {
            $listOFCampainDB_array_exclusive['campaigns'] = array();
            $listOFCampainDB_array_ping_ex['campaigns'] = array();
            $listOFCampainDB_array_ping_ex['response'] = array();
        }

        //multi pings api responses
        $crm_api_file = new CrmApi();
        $multi_pings_api_responses_sh = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_sh['response']);
        if( $request['is_shared'] != 1 ){
            $multi_pings_api_responses_ex = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_ex['response']);
        }
        else {
            $multi_pings_api_responses_ex['campaigns'] = array();
            $multi_pings_api_responses_ex['response'] = array();
        }

        $campaigns_sh = array_merge($listOFCampainDB_array_shared['campaigns'],$multi_pings_api_responses_sh['campaigns']);
        $campaigns_ex = array_merge($listOFCampainDB_array_exclusive['campaigns'],$multi_pings_api_responses_ex['campaigns']);
        $ping_post_arr = array_merge($multi_pings_api_responses_ex['response'],$multi_pings_api_responses_sh['response']);

        //Sort Campaign By Bid
        $campaigns_sh = collect($campaigns_sh);
        $campaigns_sh_sorted = $campaigns_sh->sortByDesc('campaign_budget_bid_shared');
        $campaigns_ex = collect($campaigns_ex);
        $campaigns_ex_sorted = $campaigns_ex->sortByDesc('campaign_budget_bid_exclusive');

        while (1){ //infinite loop
            $data_from_post_lead = $main_api_file->post_and_pay_direct($campaigns_sh_sorted, $campaigns_ex_sorted, $data_msg, $ping_post_arr, $request['vendor_id']);
            if( !empty($data_from_post_lead) ){
                if( $data_from_post_lead['success'] == "false" ){
                    $campaigns_sh_sorted = $data_from_post_lead['campaigns_sh_sorted'];
                    $campaigns_ex_sorted = $data_from_post_lead['campaigns_ex_sorted'];
                    $data_msg = $data_from_post_lead['data_msg'];
                    $lead_total_pid = $data_from_post_lead['lead_total_pid'];
                    $data_msg['lead_total_pid'] = $lead_total_pid;
                } else {
                    $data_msg = $data_from_post_lead['data_msg'];
                    $lead_total_pid = $data_from_post_lead['lead_total_pid'];
                    $data_msg['lead_total_pid'] = $lead_total_pid;

                    $is_special = 0;
                    if( !empty($is_valid_vendor_id->special_state) && $is_valid_vendor_id->special_state != "[]" ){
                        if( in_array($address['state_id'], json_decode($is_valid_vendor_id->special_state, true)) ){
                            $is_special = 1;
                        }
                    }

                    // for special source
                    // checks if there's a special source in campaign settings to set a specific price for the lead
                    $is_special_source = 0;
                    if( !empty($is_valid_vendor_id->special_source) && $is_valid_vendor_id->special_source != "[]" ){
                        if( in_array($data_msg['google_ts'], json_decode($is_valid_vendor_id->special_source, true)) ){
                            $is_special_source = 1;
                        }
                    }

                    if ($is_special_source == 1){
                        $price_return = $is_valid_vendor_id->special_source_price;
                    }
                    else if( $is_special == 1 ){
                        $price_return = $is_valid_vendor_id->special_budget_bid_exclusive;
                    }
                    else {
                        $price_return = $is_valid_vendor_id->campaign_budget_bid_exclusive;
                    }
                    $price_return = number_format($price_return,2);

                    //Check if Total Pid Less than return price or not
                    if($lead_total_pid >= $price_return){
                        LeadsCustomer::where('lead_id', $postLeads_id)->update([
                            "response_data" => 'Lead Accepted',
                            "ping_price" => $price_return,
                            "ping_original_price" => $lead_total_pid
                        ]);

                        $response_code = array(
                            'response_code' => 'true',
                            'message' => 'Lead Accepted',
                            'error' => '',
                            'transaction_id' => $transaction_id,
                            'price' => $price_return
                        );

                        return response()->json($response_code);
                    }

                    break;
                }
            } else {
                break;
            }
        }

        LeadsCustomer::where('lead_id', $postLeads_id)->update([
            "response_data" => 'All buyers have rejected this lead'
        ]);

        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => 'No Buyer Found',
            'transaction_id' => '',
            'price' => '0.00'
        );

        return response()->json($response_code);
    }

    //Ping
    public function check_if_match_campaign($LeaddataIDs, $service, $address, $vendor_id, $sub_id = "", $is_ping_post = 1){
        //Add With Campaign
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        }
        else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        }
        else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        }
        else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
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
        }
        else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $sub_id = preg_replace('/\s+/', '', strtolower($sub_id));

        $campaigns = DB::table('campaigns')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id');

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
            $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
            $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
            $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
            $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
        })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where(function ($query) use($sub_id){
                $query->whereNull('campaigns.exclude_sources');
                $query->OrWhereJsonDoesntContain('campaigns.exclude_sources', "$sub_id");
            })
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('campaigns.service_campaign_id', $service)
            ->where('campaigns.is_seller', 1)
            ->where('campaigns.is_ping_account', $is_ping_post)
            ->where('campaigns.vendor_id', $vendor_id)
            ->where('campaigns.campaign_budget_bid_exclusive', '!=', 0)
            ->where('campaigns.is_branded_camp', 0)
            ->first(['campaigns.*','campaign_target_area.*', 'service__campaigns.service_campaign_name']);

        return $campaigns;
    }

    //Ping
    public function check_ping_if_sold($listOFCampainDB, $listOFCampainDB_type, $if_campaign_is_set, $pingLeads_id, $transaction_id, $ping_post_arr, $state_id, $count_of_lead, $google_ts){
        //payment And Send Msg/email/crm
        if( !empty($listOFCampainDB) ){
            $campaign_id_arr = array();
            $users_id_arr = array();
            $total_Lead_Bid = 0;
            $price_return = 0;
            $number_of_lead = 1;
            foreach( $listOFCampainDB as $campaign ) {
                if( $number_of_lead >= $count_of_lead ){
                    break;
                }

//                echo "<pre>";
//                print_r($campaign); die();
                $campaign_id_curent = $campaign['campaign_id'];
                $user_id = $campaign['user_id'];
                $virtual_price = ($campaign['is_ping_account'] != 1 ? $campaign['virtual_price'] : 0);

                if( $listOFCampainDB_type == 'Exclusive' ){
                    $budget_bid = $campaign['campaign_budget_bid_exclusive'] - $virtual_price;
                    $chrck_excluse_buyers = false;
                } else {
                    $budget_bid = $campaign['campaign_budget_bid_shared'] - $virtual_price;
                    $chrck_excluse_buyers = true;
                }

                //Check Exclude Buyers On Shared Only ==================================================================
                if( $chrck_excluse_buyers == true ){
                    $exclude_buyersA = DB::table('exclude_buyers')->where('user_idB', $user_id)->pluck('user_idA')->toArray();
                    $exclude_buyersB = DB::table('exclude_buyers')->where('user_idA', $user_id)->pluck('user_idB')->toArray();

                    $exclude_buyers = array_merge($exclude_buyersA, $exclude_buyersB);
                    $exclude_buyers = array_unique($exclude_buyers);

                    $check_exclude_buyers = array_intersect($exclude_buyers, $users_id_arr);
                    if( !empty($check_exclude_buyers) ){
                        continue;
                    }
                }
                //======================================================================================================

                $payment_type_method_status = $campaign['payment_type_method_status'];
                $payment_type_method_id = $campaign['payment_type_method_id'];

                //=========================Payment Here==========================
                $totalAmmountUser = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
                $totalAmmountUser_value = (!empty($totalAmmountUser) ? $totalAmmountUser['total_amounts_value'] : 0);

                if (($totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0)
                    || ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {

                    $campaign_id_arr[] = $campaign_id_curent;
                    $users_id_arr[] = $user_id;
                    $total_Lead_Bid += $budget_bid;

                    $is_special = 0;
                    if (!empty($if_campaign_is_set->special_state) && $if_campaign_is_set->special_state != "[]") {
                        if (in_array($state_id, json_decode($if_campaign_is_set->special_state, true))) {
                            $is_special = 1;
                        }
                    }

                    // for special source
                    // checks if there's a special source in campaign settings to set a specific price for the lead
                    $is_special_source = 0;
                    if (!empty($if_campaign_is_set->special_source) && $if_campaign_is_set->special_source != "[]") {
                        if (in_array($google_ts, json_decode($if_campaign_is_set->special_source, true))) {
                            $is_special_source = 1;
                        }
                    }

                    if ($if_campaign_is_set->if_static_cost == 1) {
                        if ($is_special_source == 1) {
                            $price_return = $if_campaign_is_set->special_source_price;
                        } else if ($is_special == 1) {
                            $price_return = $if_campaign_is_set->special_budget_bid_exclusive;
                        } else {
                            $price_return = $if_campaign_is_set->campaign_budget_bid_exclusive;
                        }
                    } else {
                        if ($is_special_source == 1) {
                            $price_return += $budget_bid - ($budget_bid * ($if_campaign_is_set->special_source_price / 100));
                        } else if ($is_special == 1) {
                            $price_return += $budget_bid - ($budget_bid * ($if_campaign_is_set->special_budget_bid_exclusive / 100));
                        } else {
                            $price_return += $budget_bid - ($budget_bid * ($if_campaign_is_set->campaign_budget_bid_exclusive / 100));
                        }
                    }
                    $number_of_lead += 1;
                }
                //==============================================================
            }
            if( !empty($campaign_id_arr) ){
                $no_buyer_bid = 0;
                if( $if_campaign_is_set->if_static_cost == 1 ){
                    $price_check = $total_Lead_Bid - $price_return;
                    if( $price_check < 5 ){
                        $no_buyer_bid = 1;
                    }
                } else {
                    if( $price_return <= 0 ){
                        Log::info('Campaign Error price',
                            array(
                                'price' => $price_return,
                                'if_campaign_is_set' => $if_campaign_is_set,
                                'Campaign' => $campaign_id_arr,
                                'budget_bid' =>$budget_bid,
                                'if_campaign_is_set->campaign_budget_bid_exclusive' =>$if_campaign_is_set->campaign_budget_bid_exclusive,
                                'campaign_budget_bid_exclusive'=>( $if_campaign_is_set->campaign_budget_bid_exclusive / 100 )
                            )
                        );
                        $no_buyer_bid = 1;
                    }
                }

                if( $no_buyer_bid == 1 ){
                    PingLeads::where('lead_id', $pingLeads_id)->update([
                        "status" => 'No buyer found To bid on static price'
                    ]);

                    $response_code = array(
                        'response_code' => 'false',
                        'message' => 'Reject',
                        'error' => 'No Buyer Found',
                        'transaction_id' => '',
                        'price' => '0.00'
                    );

                    return $response_code;
                }

                $price = number_format($price_return,2);

                PingLeads::where('lead_id', $pingLeads_id)->update([
                    "status" => 'Lead Accepted',
                    "price" => $price,
                    "original_price" => number_format($total_Lead_Bid,2),
                    "ping_post_data_arr" => json_encode($ping_post_arr),
                    "campaign_id_arr" => json_encode($campaign_id_arr),
                    "lead_bid_type" => $listOFCampainDB_type
                ]);

                $response_code = array(
                    'response_code' => 'true',
                    'message' => 'Lead Accepted',
                    'error' => "",
                    'transaction_id' => $transaction_id,
                    'price' => $price
                );

                return $response_code;
            }

        }

        PingLeads::where('lead_id', $pingLeads_id)->update([
            "status" => 'No Buyer Found'
        ]);

        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => 'No Buyer Found',
            'transaction_id' => '',
            'price' => '0.00'
        );

        return $response_code;
    }
}
