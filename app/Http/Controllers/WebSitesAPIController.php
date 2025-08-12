<?php

namespace App\Http\Controllers;

use App\LeadReview;
use App\LeadsCustomer;
use App\Models\LostLeadReport;
use App\Services\AllServicesQuestions;
use App\Services\APIValidations;
use App\Services\CrmApi;
use App\Services\ServiceQueries;
use App\State;
use App\TestLeadsCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ApiMain;
use Illuminate\Support\Facades\Log;

class WebSitesAPIController extends Controller
{
    public function __construct(Request $request)
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function listOFState( Request $request ){
        $states['states'] = State::All();
        return $states;
    }

    public function all_zipcodes_cities_states(Request $request){
        $addressDetails['address'] = DB::table('zip_codes_lists')
            ->join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->get([
                'zip_codes_lists.zip_code_list_id', 'zip_codes_lists.zip_code_list',
                'zip_codes_lists.state_id', 'states.state_name',
                'zip_codes_lists.city_id', 'cities.city_name'
            ]);

        return $addressDetails;
    }

    public function addressDetails(Request $request){
        $zipcode = $request->zipcode;

        $addressDetails['address'] = DB::table('zip_codes_lists')
            ->join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->join('counties', 'counties.county_id', '=', 'zip_codes_lists.county_id')
            ->where('zip_codes_lists.zip_code_list', $zipcode)
            ->first([
                'zip_codes_lists.zip_code_list_id', 'zip_codes_lists.zip_code_list',
                'zip_codes_lists.state_id', 'zip_codes_lists.county_id', 'states.state_name',
                'zip_codes_lists.city_id', 'cities.city_name', 'counties.county_name', 'states.state_code'
            ]);

        return $addressDetails;
    }

    public function lead_verified_phone(Request $request){
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Invalid campaign_id or campaign_key value'
            );

            return json_encode($response_code);die();
        }

        sleep(10);
        $LeadsCustomer_exist = LeadsCustomer::where('status', 0)
            ->where('is_verified_phone', 1)
            ->where('created_at', '>=', date('Y-m-d', strtotime("-7 day")) . ' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
            ->where(function ($query) use($request) {
                $query->where('lead_phone_number', $request['phone_number']);
                $query->OrWhere('lead_email', $request->email);
            })->first();

        if(empty($LeadsCustomer_exist)){
            LeadsCustomer::where('status', 0)
                ->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
                ->where(function ($query) use($request) {
                    $query->where('lead_phone_number', $request['phone_number']);
                    $query->OrWhere('lead_email', $request->email);
                })->update(['is_verified_phone' => 1]);

            //Server to server Conversion =================================================================
            //For Roy (Popunder)
            if( strtolower(substr($request['tc'], 0, 2)) == 'vr' ){
                if( !empty($request['k']) ){
                    $token_data_conv = $request['k'];
                    $url_conv = "http://ad.propellerads.com/conversion.php?aid=874155&pid=&tid=93273&visitor_id=$token_data_conv";

                    $main_api_file = new ApiMain();
                    $main_api_file->server_to_server_conv($url_conv);
                }
            }
            //=============================================================================================
        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Success',
            'error' => ''
        );

        return json_encode($response_code);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addLeadCustomer(Request $request){
        //validate
        $this->validate($request, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'street_name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'string', 'max:255'],
            'zipcode_id' => ['required', 'string', 'max:255'],
            'service_id' => ['required'],
            'lead_website' => ['required', 'string', 'max:255'],
            'serverDomain' => ['required'],
            'timeInBrowseData' => ['required'],
            'ipaddress' => ['required'],
            'FullUrl' => ['required'],
            'browser_name' => ['required'],
            'aboutUserBrowser',
            'tc',
            'c',
            'g',
            'k',
            'token',
            'visitor_id',
            'fl', //for bad traffic (flag)
            's1',
            's2',
            's3',
            'gclid'
        ]);

        $main_api_file = new ApiMain();
        $response_code = array();
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
//            $response_code = array(
//                'response_code' => 'false',
//                'message' => 'Reject',
//                'error' => 'Invalid campaign_id or campaign_key value'
//            );
//
//            return json_encode($response_code);die();
        }

        try {
            //Lead Address ==================================================================
            if(empty($request['county_id'])){
                $county_id_list = DB::table('zip_codes_lists')
                    ->join('counties', 'counties.county_id', '=', 'zip_codes_lists.county_id')
                    ->where('zip_codes_lists.zip_code_list_id', $request['zipcode_id'])
                    ->first(['zip_codes_lists.county_id', 'counties.county_name']);

                $address['county_id'] = $county_id_list->county_id;
                $address['county_name'] = $county_id_list->county_name;
            } else {
                $address['county_id'] = $request['county_id'];
                $address['county_name'] = $request['county_name'];
            }

            $address['zipcode_id'] = $request['zipcode_id'];
            $address['zip_code_list'] = $request['zipcode_name'];
            $address['city_id'] = $request['city_id'];
            $address['city_name'] = $request['city_name'];
            $address['state_id'] = $request['state_id'];
            $address['state_name'] = $request['state_name'];
            $address['state_code'] = $request['state_code'];
            //Lead Address ==================================================================

            //Update Questions =======================================================================================
            if( $request['ownership'] == 2 ){
                $request['ownership'] = 0;
            }

            //Kitchen
            if( $request['removing_adding_walls'] == 2 ){
                $request['removing_adding_walls'] = 0;
            }

            if ($request['service_id'] == 4) {
                $request['projectnature'] = $request['nature_flooring_project'];
            } else if ($request['service_id'] == 6) {
                $request['projectnature'] = $request['nature_of_roofing'];
            } else if ($request['service_id'] == 7) {
                if ($request['nature_of_siding'] == 1 || $request['nature_of_siding'] == 2) {
                    $request['projectnature'] = 1;
                } else if ($request['nature_of_siding'] == 3) {
                    $request['projectnature'] = 2;
                } else if ($request['nature_of_siding'] == 4) {
                    $request['projectnature'] = 3;
                }
            }
            //Update Questions =======================================================================================

            //start window questions ==========================================================================
            $api_validations = new APIValidations();
            $questions = $api_validations->check_questions_ids_array($request);
            $dataMassageForBuyers = $questions['dataMassageForBuyers'];
            $Leaddatadetails = $questions['Leaddatadetails'];
            $LeaddataIDs = $questions['LeaddataIDs'];
            $dataMassageForDB = $questions['dataMassageForDB'];
            //end window questions ==========================================================================

            //To Get Lead Source ===========================================================================
            $lead_source = "SEO";
            $lead_source2 = "SEO";
            $lead_source_api = "ADMS20";
            $lead_source_id = 1;
            $is_lead_review = 0;
            if( !empty($request['tc']) ){
                $ts_data_arr = explode("-",$request['tc']);
                $marketing_ts = DB::table('lead_traffic_sources')->where('name', strtolower($ts_data_arr[0]))->first(['marketing_platform_id']);
                if( !empty($marketing_ts) ){
                    $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source', 'name')
                        ->where('id', $marketing_ts->marketing_platform_id)->first();
                    if( !empty($marketing_platform) ){
                        $lead_source = $marketing_platform->name;
                        $lead_source2 = $marketing_platform->name;
                        $lead_source_id = $marketing_platform->id;
                        $lead_source_api = $marketing_platform->lead_source;
                        if($lead_source2 == "ReAffiliate"){
                            $request['traffic_source'] = "ReAffiliate";
                        }
                    }
                }

                if( !empty($ts_data_arr[1]) ){
                    $ts_data_arr1_data = str_split($ts_data_arr[1]);
                    if( !empty($ts_data_arr1_data[0])){
                        if( strtolower($ts_data_arr1_data[0]) == "r" ){
                            $lead_source2 .= " > R";
                            $is_lead_review = 1;
                        }
                    }
                }
            }
            //To Get Lead Source ===========================================================================

            //Change Phone Structure ====================================================
            $request['phone_number'] = trim(str_replace([' ', '(', ')', '-'], '', $request['phone_number']));
            //Change Phone Structure ====================================================

            //To check If Duplicated Lead =================================================================
            $is_unsold_duplicate = LeadsCustomer::where('lead_type_service_id', $request->service_id)
                ->where('status', 0)
                ->where(function ($query) use($request) {
                    $query->where('lead_phone_number', $request['phone_number']);
                    $query->OrWhere('lead_email', $request->email);
                })
                ->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
                ->first();

            $is_sold_duplicate = LeadsCustomer::where('leads_customers.lead_type_service_id', $request->service_id)
                ->join('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->where('leads_customers.status', 0)
                ->where(function ($query) use($request) {
                    $query->where('leads_customers.lead_phone_number', $request['phone_number']);
                    $query->OrWhere('leads_customers.lead_email', $request->email);
                })
                ->where('leads_customers.created_at', '>=', date('Y-m-d', strtotime("-30 day")) . ' 00:00:00')
                ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                ->first();
            //To check If Duplicated Lead =================================================================

            //Checked Blocked Info =================================================================
            $is_blocked_phone_number = DB::table('block_phone_number_lists')->where('value', $request['phone_number'])->first();
            $is_blocked_email = DB::table('block_email_lists')->where('value', $request['email'])->first();
            $is_blocked_ip_address = DB::table('block_ip_address_lists')->where('value', $request['ipaddress'])->first();
            $is_blocked_first_name = DB::table('block_first_name_lists')->where('value', $request['fname'])->first();
            $is_blocked_last_name = DB::table('block_last_name_lists')->where('value', $request['lname'])->first();

            $is_blocked_lead_info = 0;
            if( !empty($is_blocked_phone_number) || !empty($is_blocked_email) || !empty($is_blocked_ip_address)
                || !empty($is_blocked_first_name) || !empty($is_blocked_last_name) ){
                $is_blocked_lead_info = 1;
            }
            //Checked Blocked Info =================================================================

            //TCPA ==============================================================================================
            $tcpa_compliant = 1;
            if( !empty($request->tcpa_consent_text) ){
                $tcpa_consent_text = $request->tcpa_consent_text;
            } else {
                $tcpa_consent_text = "By clicking the finish button and submitting this form, you are providing your electronic signature in which you consent, acknowledge, and agree to this website's Privacy Policy and Terms And Conditions. You also hereby consent to receive marketing communications via automated telephone dialing systems and/or pre-recorded calls, text messages, and/or emails from our Premiere Partners and marketing partners at the phone number, physical address and email address provided above, with offers regarding the requested Home service. This is also a consent to receive communications even if you are on any State and/or Federal Do Not Call list. Consent is not a condition of purchase and may be revoked at any time. Message and data rates may apply. California Residents Privacy Notice.";
            }
            //TCPA ==============================================================================================

            //For Restructure the Domain name ==========================================================================
            $request['serverDomain'] = trim(str_replace(['https://', 'http://', 'www.'], '', $request['serverDomain']));
            //For Restructure the Domain name ==========================================================================

            //Add LeadsCustomer ==============================================================================================
            $leadCustomerStore = new LeadsCustomer();

            $allservicesQues = new AllServicesQuestions();

            $leadCustomerStore = $allservicesQues->websitesAPIControllerAddLeadCustomer($leadCustomerStore, $request, $lead_source_id, $lead_source2, $dataMassageForDB, $tcpa_compliant, $tcpa_consent_text, $is_blocked_lead_info);

            $leadCustomerStore->save();
            $leadCustomer_id = DB::getPdo()->lastInsertId();
            //Add LeadsCustomer ==============================================================================================

            //Delete Lead from Lead Review =================================================================
            LeadReview::where('universal_leadid', $request['universal_leadid'])->delete();
            //Delete Lead from Lead Review =================================================================

//            if(strtolower(substr($request['tc'], 0, 1)) == 'o' || strtolower(substr($request['tc'], 0, 2)) == 'tl'){
//                if($is_lead_review != 1){
//                    //IPQS IP Validation
//                    $lead_ip_validation = $api_validations->lead_ip_validation_ipqs($request->ipaddress);
//                    if ($lead_ip_validation != "true") {
//                        LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
//                            "response_data" => $lead_ip_validation,
//                            "status" => 4,
//                            "flag" => 1
//                        ]);
//
//                        $response_code = array(
//                            'response_code' => 'false',
//                            'message' => 'Reject',
//                            'error' => $lead_ip_validation,
//                            'responce_code' => 'false'
//                        );
//
//                        return json_encode($response_code);die();
//                    }
//                }
//            }

//        if(!empty($request->ipaddress) && $is_lead_review == 0){
//            $ip_address = $request->ipaddress;
//            $apiToken = "xvS204UnNAuBABLERblpdHHXEvx50t8X";
//            $user_language = "en-US";
//            $strictness = "1";
//
//            $urlRequest = "https://ipqualityscore.com/api/json/ip/$apiToken/$ip_address?strictness=$strictness&user_language=$user_language";
//
//            $init = curl_init();
//            curl_setopt($init, CURLOPT_URL, $urlRequest);//connect the server
//            curl_setopt($init, CURLOPT_POST, 1);
//            curl_setopt($init, CURLOPT_RETURNTRANSFER, true); //the result of connection
//            curl_setopt($init, CURLOPT_HEADER, false); //get back the header
//            curl_setopt($init, CURLOPT_TIMEOUT, 3); //Time Out 3s
//            $output = curl_exec($init);
//            curl_close($init);
//
//            $result = json_decode($output, true);
//            if(!empty($result['success'])){
//                //if($result['vpn'] || $result['country_code'] != "US"){
//                if($result['country_code'] != "US"){
//                    LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
//                        "response_data" => "Visitor is suspicious!",
//                        "status" => 4,
//                        "flag" => 1
//                    ]);
//
//                    $response_code = array(
//                        'response_code' => 'false',
//                        'message' => 'Reject',
//                        'error' => "Visitor is suspicious!",
//                        'responce_code' => 'false'
//                    );
//
//                    return json_encode($response_code);die();
//                }
//            }
//        }

            //ipregistry IP Validation
//            if(strtolower(substr($request['tc'], 0, 1)) == 'o') {
//                //ipregistry IP Validation
//                $ipregistry_validation = $api_validations->ipregistry_validation($request['ipaddress']);
//
//                if ($ipregistry_validation != "true") {
//                    LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
//                        "response_data" => $ipregistry_validation,
//                        "status" => 4,
//                        "flag" => 1
//                    ]);
//
//                    $response_code = array(
//                        'response_code' => 'false',
//                        'message' => 'Reject',
//                        'error' => $ipregistry_validation,
//                        'responce_code' => 'false'
//                    );
//
//                    return json_encode($response_code);
//                    die();
//                }
//            }


//            if(strtolower(substr($request['tc'], 0, 1)) != 's') {
//                //trestleiq Validation
//                $trestleiq_validation = $api_validations->trestleiq_validation($request['phone_number'], $request['email'], $request['fname'], $request['lname']);
//                if ($trestleiq_validation != "true") {
//                    LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
//                        "response_data" => $trestleiq_validation,
//                        "status" => 4,
//                        "flag" => 1
//                    ]);
//
//                    $response_code = array(
//                        'response_code' => 'false',
//                        'message' => 'Reject',
//                        'error' => $trestleiq_validation,
//                        'responce_code' => 'false'
//                    );
//
//                    return json_encode($response_code);
//                    die();
//                }
//            }

            //Server to server Conversion =================================================================
            if(!empty($request['token'])){
                $token_data_conv = $request['token'];
                $url_conv = "";
                if( strtolower(substr($request['tc'], 0, 2)) == 'pz' ) {
                    //For Roy (Popunder)
                    $id = "1GB9D5GA7C3G5922DCAA";
                    $url_conv = "https://www.conversionpx.com/?id=$id&value=0&token=$token_data_conv";
                    //$url_conv = "https://tracking.propelmedia.com/?id=$id&value=0&token=$token_data_conv";
                } elseif( strtolower(substr($request['tc'], 0, 1)) == 'p' ) {
                    //For Roy (Popunder)
                    $id = "1GB3F8GA096GAFA46C6A";
                    $url_conv = "https://www.conversionpx.com/?id=$id&value=0&token=$token_data_conv";
                    //$url_conv = "https://tracking.propelmedia.com/?id=$id&value=0&token=$token_data_conv";
                } elseif( strtolower(substr($request['tc'], 0, 2)) == 'ra' ) {
                    $url_conv = "https://eu.rollerads.com/conversion/$token_data_conv/aid/17460/8d65c8bef45410b4";
                } elseif( strtolower(substr($request['tc'], 0, 3)) == 'htf' ) {
                    //Has Traffic S2S
                    //https://discounthomeremodeling.com?w=htf1&x={revenue}&y=1040&z={target}&token={clickId}&s={cpv}
                    $revenue = (!empty($request['c']) ? $request['c'] : "");
                    $url_conv = "https://postback.hastraffic.com/?clickid=$token_data_conv&revenue=$revenue&aid=9689";
                } elseif( strtolower(substr($request['tc'], 0, 4)) == 'rlad' ) {
                    //RollerAds S2S
                    //https://discounthomeremodeling.com?w=rlad1&y=1052&token={network_token}
                    $url_conv = "https://trckprofit.com/click.php?cnv_id=$token_data_conv&payout=0";
                } elseif( strtolower(substr($request['tc'], 0, 2)) == 'jn' ) {
                    //Jeeng S2S
                    //https://cs-external.powerinbox.com/postback/notify?pi_clickid={network_token}
                    $url_conv = "https://cs-external.powerinbox.com/postback/notify?pi_clickid=$token_data_conv";
                } elseif( strtolower(substr($request['tc'], 0, 2)) == 'tb' ) {
                    //Taboola S2S
                    $url_conv = "https://trc.taboola.com/actions-handler/log/3/s2s-action?click-id=$token_data_conv&name=lead";
                }
                elseif( strtolower(substr($request['tc'], 0, 2)) == 'zt' ) {
                    //zeeto S2S
                    $url_conv = "https://monetize.zeeto.io/postback/$token_data_conv?ze=e4";

                }
//                elseif( strtolower(substr($request['tc'], 0, 1)) == 'o' ) {
//                    //One Pride Group S2S
//                    $url_conv = "https://offers-onepride-group.affise.com/postback?clickid=$token_data_conv&sum=0";
//                }
                $main_api_file->server_to_server_conv($url_conv);
            }
            if(!empty($request['k'])){
                $token_data_conv = $request['k'];
                $url_conv = "";
                if( strtolower(substr($request['tc'], 0, 2)) == 'pa' ){
                    //For Roy (propeller ads)
                    $url_conv = "http://ad.propellerads.com/conversion.php?aid=3654511&pid=&tid=123703&visitor_id=$token_data_conv";
                } elseif( strtolower(substr($request['tc'], 0, 2)) == 'zp' ){
                    //For Yanal (ZeroPark)
                    $url_conv = "http://zp-postback.com/zppostback/40ecd931-4a26-11ed-93e6-12beee04f19b?cid=$token_data_conv";
                }
//                elseif( strtolower(substr($request['tc'], 0, 2)) == 'dm' ) {
//                    //Dynuin Media S2S
//                    $price_g = (!empty($request['g']) ? $request['g'] : '12');
//                    $url_conv = "https://dynuinmedia.go2cloud.org/aff_lsr?transaction_id=$token_data_conv&amount=$price_g";
//                }
                $main_api_file->server_to_server_conv($url_conv);
            }
            //Travis Pushnami server to server
            if( strtolower($request['tc']) == "push1" ){
                $transid = "";
                if( !empty($request['s1']) ){
                    $transid = $request['s1'];
                }

                $hid = "";
                if( !empty($request['s2']) ){
                    $hid = $request['s2'];
                }

                $ate = 6;

                $url_conv = "https://www.groovast.com/rd/apx.php?id=517type=4&hid=$hid&transid=$transid&ate=$ate";
                $main_api_file->server_to_server_conv($url_conv);
            }
            //Server to server Conversion =================================================================
        } catch (Exception $e) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Something went wrong',
                'responce_code' => 'false'
            );

            return json_encode($response_code);die();
        }

        if( $leadCustomer_id >= 1 ){
            $response_code = array(
                'response_code' => 'true',
                'message' => 'Lead Accepted',
                'error' => '',
                'responce_code' => 'true'
            );

            //Check if test lead ===============================================================================
            if( strtolower($request['fname']) == 'test' || strtolower($request['lname']) == 'test'
                || strtolower($request['fname']) == 'testing' || strtolower($request['lname']) == 'testing'){
                return json_encode($response_code);die();
            }
            //Check if test lead ===============================================================================

            //Check if ReAffiliate Lead ===============================================================================
            if( strtolower($request['tc']) == 'raf1' || strtolower($request['tc']) == 'raf2' ){
                return json_encode($response_code);die();
            }
            //Check if ReAffiliate Lead ===============================================================================

            //Check if Flag Lead ===============================================================================
            if(!empty($request['fl']) && in_array(strtolower($request['fl']), ['1', '2'])){
                return json_encode($response_code);die();
            }
            //Check if Flag Lead ===============================================================================

            //Check if Duplicated Lead ===============================================================================
            if( !empty($is_sold_duplicate) || !empty($is_unsold_duplicate)) {
                $response_code = array(
                    'response_code' => 'false',
                    'message' => 'Reject',
                    'error' => 'Duplicated Lead',
                    'responce_code' => 'false'
                );

                return json_encode($response_code);die();
            }
            //Check if Duplicated Lead ===============================================================================

            //Check if Blocked Lead ===============================================================================
            if( $is_blocked_lead_info == 1 ){
                $response_code = array(
                    'response_code' => 'false',
                    'message' => 'Reject',
                    'error' => 'Blocked Lead',
                    'responce_code' => 'false'
                );

                return json_encode($response_code);die();
            }
            //Check if Blocked Lead ===============================================================================

            if (strtolower($request['tc']) == 'push1') {
                return json_encode($response_code);die();
            }
        } else {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Something went wrong',
                'responce_code' => 'false'
            );

            return json_encode($response_code);die();
        }

        //Claim TrustedForm
//        if( !empty($request['trusted_form']) ) {
//            $main_api_file->claim_trusted_form($request['trusted_form']);
//        }
        //Claim Jornaya LeadId
        if( !empty($request['universal_leadid']) ) {
            $main_api_file->claim_jornaya_id($request['universal_leadid']);
        }

        //Add Seconds Service to $LeaddataIDs Array
        if(!empty($request['is_sec_service'])){
            $LeaddataIDs['is_sec_service'] = $request['is_sec_service'];
        }

        $service_info = DB::table('service__campaigns')
            ->where('service_campaign_id', $request['service_id'])
            ->first(['service_campaign_name']);

        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $request['city_name']);
        $county_arr = explode('=>', $request['county_name']);
        $data_msg = array(
            'leadCustomer_id' => $leadCustomer_id,
            'leadName' => $request['fname'] . ' ' . $request['lname'],
            'LeadEmail' => $request['email'],
            'LeadPhone' => $request['phone_number'],
            'Address' => 'Address: ' . $request['street_name'] . ', City: ' . $city_arr[0] . ', State: ' . $request['state_name'] . ', Zipcode: ' . $request['zipcode_name'],
            'LeadService' => $service_info->service_campaign_name,
            'service_id' => $request->service_id,
            'data' => $dataMassageForBuyers,
            'trusted_form' => $request['trusted_form'],
            'street' => $request['street_name'],
            'City' => $city_arr[0],
            'State' => $request['state_name'],
            'state_code' => $request['state_code'],
            'Zipcode' => $request['zipcode_name'],
            'county' => $county_arr[0],
            'first_name' => $request['fname'],
            'last_name' => $request['lname'],
            'UserAgent' => $request['aboutUserBrowser'],
            'OriginalURL' => $request['serverDomain'],
            'OriginalURL2' => "https://www.".$request['serverDomain'],
            'SessionLength' => $request['timeInBrowseData'],
            'IPAddress' => $request['ipaddress'],
            'LeadId' => $request['universal_leadid'],
            'browser_name' => $request['browser_name'],
            'tcpa_compliant' => $tcpa_compliant,
            'TCPAText' => $tcpa_consent_text,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $request['traffic_source'],
            'google_ts' => $request['tc'],
            'is_multi_service' => $request['is_multi_service'],
            'is_sec_service' => $request['is_sec_service'],
            'dataMassageForBuyers' => $dataMassageForBuyers,
            'Leaddatadetails' => $Leaddatadetails,
            'LeaddataIDs' => $LeaddataIDs,
            'dataMassageForDB' => $dataMassageForDB,
            'appointment_date' => '',
            'appointment_type' => '',
            "is_lead_review" => $is_lead_review,
            'oldNumber' => $request['phone_number'],
            'newNumber' => ""
        );
        //Lead Info =====================================================================================================================

        //Check if Brand Leads===========================================================
        $LeaddataIDs['is_brand_lead']  = ( !empty($request->brand_buyer_id) ? 1 : 0 );
        $LeaddataIDs['brand_buyer_id'] = ( !empty($request->brand_buyer_id) ? $request->brand_buyer_id : 0 );
        //================================================================================

        //Select List Of Campaign
        $service_queries = new ServiceQueries();
        $listOFCampain_exclusiveDB = $service_queries->service_queries_new_way($request->service_id, $LeaddataIDs,  1, 0, $address, $lead_source, 0,strtolower($request['tc']), $request['serverDomain']);
        $listOFCampain_sharedDB = $service_queries->service_queries_new_way($request->service_id, $LeaddataIDs,  2, 0, $address, $lead_source, 0,strtolower($request['tc']), $request['serverDomain']);
        $listOFCampain_pingDB_ex = $service_queries->service_queries_new_way($request->service_id, $LeaddataIDs,  1, 1, $address, $lead_source, 0,strtolower($request['tc']), $request['serverDomain']);
        $listOFCampain_pingDB_sh = $service_queries->service_queries_new_way($request->service_id, $LeaddataIDs,  2, 1, $address, $lead_source, 0,strtolower($request['tc']), $request['serverDomain']);

        $campaigns_list_direct_sh = $listOFCampain_sharedDB->pluck('campaign_id')->toArray();
        $campaigns_list_ping_sh = $listOFCampain_pingDB_sh->pluck('campaign_id')->toArray();
        $campaigns_list_direct_ex = $listOFCampain_exclusiveDB->pluck('campaign_id')->toArray();
        $campaigns_list_ping_ex = $listOFCampain_pingDB_ex->pluck('campaign_id')->toArray();

        $campaigns_list_sh = array_merge($campaigns_list_direct_sh, $campaigns_list_ping_sh);
        $campaigns_list_ex = array_merge($campaigns_list_direct_ex, $campaigns_list_ping_ex);

        //Filtration For cap Ex & Shared
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

        $leadsCampaignsWeeklyExclusive   = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
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

        $leadsCampaignsWeeklyShared   = DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
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

        $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] =  json_decode($leadsCampaignsDailiesExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive']  =  json_decode($leadsCampaignsWeeklyExclusive,true);
        $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] =  json_decode($leadsCampaignsMonthlyExclusive,true);

        $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] =  json_decode($leadsCampaignsDailiesShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared']  =  json_decode($leadsCampaignsWeeklyShared,true);
        $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared']  =  json_decode($leadsCampaignsMonthlyShared,true);

        //Filtaration
        $listOFCampainDB_array_exclusive = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_exclusiveDB, $data_msg, 5, 1, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        $listOFCampainDB_array_shared = $main_api_file->filterCampaign_exclusive_sheared_new_way($listOFCampain_sharedDB, $data_msg, 10, 2, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        $listOFCampainDB_array_ping_ex = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_ex, $data_msg, 1, 0, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);
        $listOFCampainDB_array_ping_sh = $main_api_file->filterCampaign_ping_post_new_way2($listOFCampain_pingDB_sh, $data_msg, 2, 0, $leadsCampaignsCapsExclusive, $leadsCampaignsCapsShared);

        //multi pings api responses
        $crm_api_file = new CrmApi();
        $multi_pings_api_responses_sh = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_sh['response']);
        $multi_pings_api_responses_ex = $crm_api_file->send_multi_ping_apis($listOFCampainDB_array_ping_ex['response']);

        $campaigns_sh = array_merge($listOFCampainDB_array_shared['campaigns'],$multi_pings_api_responses_sh['campaigns']);
        $campaigns_ex = array_merge($listOFCampainDB_array_exclusive['campaigns'],$multi_pings_api_responses_ex['campaigns']);
        $ping_post_arr = array_merge($multi_pings_api_responses_ex['response'],$multi_pings_api_responses_sh['response']);

        //Sort Campaign By Bid
        $campaigns_sh = collect($campaigns_sh);
        $campaigns_sh_sorted = $campaigns_sh->sortByDesc('campaign_budget_bid_shared');
        $campaigns_ex = collect($campaigns_ex);
        $campaigns_ex_sorted = $campaigns_ex->sortByDesc('campaign_budget_bid_exclusive');

        //Add Response To Test =========================================================================================
        $TestLeadsCustomer = new TestLeadsCustomer();

        $TestLeadsCustomer->lead_id = $leadCustomer_id;

        $TestLeadsCustomer->lastCampainInArea = json_encode(array());

        $TestLeadsCustomer->listOFCampain_exclusiveDB = json_encode($listOFCampain_exclusiveDB);
        $TestLeadsCustomer->listOFCampain_sharedDB = json_encode($listOFCampain_sharedDB);
        $TestLeadsCustomer->listOFCampain_pingDB = json_encode($listOFCampain_pingDB_ex);
        $TestLeadsCustomer->listOFCampainDB_array_ping = json_encode($listOFCampain_pingDB_sh);

        $TestLeadsCustomer->listOFCampainDB_array_shared = json_encode($listOFCampainDB_array_shared);
        $TestLeadsCustomer->listOFCampainDB_array_exclusive = json_encode($listOFCampainDB_array_exclusive);
        $TestLeadsCustomer->campaigns_sh_col = json_encode($listOFCampainDB_array_ping_ex);
        $TestLeadsCustomer->campaigns_ex_col = json_encode($listOFCampainDB_array_ping_sh);

        $TestLeadsCustomer->campaigns_sh = json_encode($campaigns_sh_sorted);
        $TestLeadsCustomer->campaigns_ex = json_encode($campaigns_ex_sorted);

        $TestLeadsCustomer->save();
        $TestLeadsCustomer_id = DB::getPdo()->lastInsertId();
        //==============================================================================================================

        $first_one = 1;
        while (1){ //infinite loop
            $data_from_post_lead = $main_api_file->post_and_pay($campaigns_sh_sorted, $campaigns_ex_sorted, $data_msg, $ping_post_arr, $TestLeadsCustomer_id, $first_one);

            if( !empty($data_from_post_lead) ){
                if( $data_from_post_lead['success'] == "false" ){
                    $first_one = $data_from_post_lead['first_one'];
                    $campaigns_sh_sorted = $data_from_post_lead['campaigns_sh_sorted'];
                    $campaigns_ex_sorted = $data_from_post_lead['campaigns_ex_sorted'];
                    $data_msg = $data_from_post_lead['data_msg'];
                } else {
                    $data_msg = $data_from_post_lead['data_msg'];
                    break;
                }
            } else {
                break;
            }
        }

        if(!empty($request['token']) && $request['is_sec_service'] != 1){
            if ($data_from_post_lead['price_shared'] > $data_from_post_lead['price_exclusive']){
                $response_code['price'] = $data_from_post_lead['price_shared'];
            }
            else {
                $response_code['price'] = $data_from_post_lead['price_exclusive'];
            }
            $finel_price = $response_code['price']/2;
            if(strtolower(substr($request['tc'], 0, 2)) == 'sm' ){
                $finel_price = $response_code['price']*0.7;
            }
            $token_data_conv = $request['token'];
            $url_conv = "";

            if ($finel_price != 0){
                if( strtolower(substr($request['tc'], 0, 2)) == 'tl' ) {
                    //Turtle Leads S2S
                    $url_conv = "https://www.dpvyw6trk.com/?nid=2167&transaction_id=$token_data_conv&amount=".$finel_price;
                    $r = array($url_conv);
                    Log::info('Turtle Leads', $r);
                }
                elseif( strtolower(substr($request['tc'], 0, 1)) == 'o' ) {
                    //One Pride Group S2S
                    $url_conv = "https://offers-onepride-group.affise.com/postback?clickid=$token_data_conv&publisher_id=".$request["c"]."&sum=$finel_price";
                }
                elseif( strtolower(substr($request['tc'], 0, 2)) == 'dm' ) {
                    //Dynuin Media S2S
                    $url_conv = "https://script.google.com/macros/s/AKfycbzZInhdAno6v-Cza0ccQeQEU7UfZh1qd720ELB63CMrA4Rpv5AoSYgZQpZcWZqA0vL-/exec?transaction_id=$token_data_conv&payout=$finel_price&affiliate_id=".$request["c"]."&ref_id=6516&adv=allied";
//                    $url_conv = "https://dynuinmedia.go2cloud.org/aff_lsr?transaction_id=$token_data_conv&amount=$finel_price";
                    $r = array($url_conv);
                    Log::info('Dynuin Media Leads', $r);

                }elseif( strtolower(substr($request['tc'], 0, 2)) == 'sm' ) {
                    //scoremobi sm
                    $url_conv = "http://scoremobi.fuse-cloud.com/pb?tid=$token_data_conv&adv_cvalue=".$finel_price;
                    $r = array($url_conv);
                    Log::info('scoremobi', $r);
                }

                $main_api_file->server_to_server_conv($url_conv);
            }
        }

        return json_encode($response_code);
    }

    public function add_multi_service_lead(Request $request){
        //validate
        $this->validate($request, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'street_name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'string', 'max:255'],
            'zipcode_id' => ['required', 'string', 'max:255'],
            'service_id' => ['required'],
            'lead_website' => ['required', 'string', 'max:255'],
            'serverDomain' => ['required'],
            'timeInBrowseData' => ['required'],
            'ipaddress' => ['required'],
            'FullUrl' => ['required'],
            'browser_name' => ['required'],
            'aboutUserBrowser',
            'tc',
            'c',
            'g',
            'k',
            'token',
            'visitor_id'
        ]);

        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Invalid campaign_id or campaign_key value'
            );

            return json_encode($response_code);die();
        }

        if( $request['ownership'] == 2 ){
            $request['ownership'] = 0;
        }

        //Kitchen
        if( $request['removing_adding_walls'] == 2 ){
            $request['removing_adding_walls'] = 0;
        }

        if( $request['is_multi_service'] == 1){
            $request['property_type_roofing_r'] = 1;
            $request['property_type_roofing_s'] = 1;
        }
        if( $request['is_multi_service'] == 1 || $request['is_sec_service'] == 1 ){
            if( $request['ownership'] == 0 ){
                $request['property_type_c'] = 2;
            } else {
                $request['property_type_c'] = 1;
            }
        }

        $service_id = $request['service_id'];
        $project_nature = json_decode($request['projectnature'], true);
        foreach ( $service_id as $val ){
            $request['service_id'] = $val;
            if( !empty($project_nature[$val]) ){
                $request['projectnature'] = $project_nature[$val];
            } else {
                $request['projectnature'] = null;
            }

            if( $val == 6 ){
                $request['property_type_roofing'] = $request['property_type_roofing_r'];
            } else if( $val == 11 ){
                $request['furnance_type'] = $request['furnance_type_f'];
            } else if( $val == 12 ){
                $request['furnance_type'] = $request['furnance_type_b'];
            } else if( $val == 17 ){
                $request['property_type_roofing'] = $request['property_type_roofing_s'];
            }

            $this->addLeadCustomer($request);
        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => ''
        );

        return json_encode($response_code);
    }
}
