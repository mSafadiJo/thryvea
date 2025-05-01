<?php
namespace App\Services;

use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use App\PingLeads;
use App\WordsValidations\BadWordsFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class APIValidations extends Controller {

    // check zipcode by cache Ping & Post
    protected $zipcode_indeixing = [
        1 => [501, 4941],
        2 => [4942, 8888],
        3 => [8889, 13493],
        4 => [13494, 16646],
        5 => [16647, 20090],
        6 => [20091, 23488],
        7 => [23489, 27316],
        8 => [27317, 30015],
        9 => [30016, 33013],
        10 => [33014, 36504],
        11 => [36505, 39767],
        12 => [39769, 44061],
        13 => [44062, 47270],
        14 => [47272, 49948],
        15 => [49950, 54121],
        16 => [54123, 57075],
        17 => [57076, 60643],
        18 => [60644, 63735],
        19 => [63736, 67643],
        20 => [67644, 71753],
        21 => [71754, 75116],
        22 => [75117, 78045],
        23 => [78046, 81419],
        24 => [81420, 87060],
        25 => [87061, 92328],
        26 => [92329, 95685],
        27 => [95686, 99105],
        28 => [99107, 99950],
    ];

    public function check_zipcode_cache($zipcode){
        try {
            set_time_limit(0);
            foreach ($this->zipcode_indeixing as $key => $item) {
                if ((int)$zipcode >= $item[0] && (int)$zipcode <= $item[1]) {
                    return $this->get_element_from_cache('zipcode_' . $key, $zipcode);
                }
            }
        } catch (\Exception $ex){
            return 0;
        }
    }

    public function get_element_from_cache($cacheName, $zipcode_list){
        try {
            $data = Cache::get($cacheName);
            if(Cache::has($cacheName)){
                return response()->json(collect($data)->where('zip_code_list', $zipcode_list)->values()[0]);
            } else {
                return 0;
            }
        } catch (\Exception $EX){
            return 0;
        }
    }
    // End check zipcode by cache Ping & Post

    //Ping & Post
    public function check_zipcode($zipcode){
        $length_zipcode = strlen($zipcode);
        if( $length_zipcode == 3 ){
            $zipcode = '00' . $zipcode;
        } else if( $length_zipcode == 4 ){
            $zipcode = '0' . $zipcode;
        }

        $zipcode_arr = DB::table('zip_codes_lists')->where('zip_code_list', $zipcode)->first();
        return $zipcode_arr;
    }

    //Ping & Post
    public function check_county($county, $state_id){
        $county_arr = DB::table('counties')
            ->where('county_name', 'like', '%' . $county . '%')
            ->where('state_id', $state_id)
            ->first(['county_id']);

        return $county_arr;
    }

    //Ping & Post
    public function check_city($city, $state_id){
        $city_arr = DB::table('cities')
            ->where('city_name', 'like', '%' . $city . '%')
            ->where('state_id', $state_id)
            ->first();

        return $city_arr;
    }

    //Ping & Post
    public function check_state($state){
        $state_arr = DB::table('states')->where('state_code', strtoupper($state))->first();
        return $state_arr;
    }

    // start check state by array Ping & Post
    public function check_state_array($state){
        $arrayState = array(
            "AL" => array("state_name" => "ALABAMA" ,"state_id" => "1"),
            "AK" => array("state_name" => "ALASKA" ,"state_id" => "2"),
            "AZ" => array("state_name" => "ARIZONA" ,"state_id" => "3"),
            "AR" => array("state_name" => "ARKANSAS" ,"state_id" => "4"),
            "CA" => array("state_name" => "CALIFORNIA" ,"state_id" => "5"),
            "CO" => array("state_name" => "COLORADO" ,"state_id" => "6"),
            "CT" => array("state_name" => "CONNECTICUT" ,"state_id" => "7"),
            "DE" => array("state_name" => "DELAWARE" ,"state_id" => "8"),
            "FL" => array("state_name" => "FLORIDA" ,"state_id" => "9"),
            "GA" => array("state_name" => "GEORGIA" ,"state_id" => "10"),
            "HI" => array("state_name" => "HAWAII" ,"state_id" => "11"),
            "ID" => array("state_name" => "IDAHO" ,"state_id" => "12"),
            "IL" => array("state_name" => "ILLINOIS" ,"state_id" => "13"),
            "IN" => array("state_name" => "INDIANA" ,"state_id" => "14"),
            "IA" => array("state_name" => "IOWA" ,"state_id" => "15"),
            "KS" => array("state_name" => "KANSAS" ,"state_id" => "16"),
            "KY" => array("state_name" => "KENTUCKY" ,"state_id" => "17"),
            "LA" => array("state_name" => "LOUISIANA" ,"state_id" => "18"),
            "ME" => array("state_name" => "MAINE" ,"state_id" => "19"),
            "MD" => array("state_name" => "MARYLAND" ,"state_id" => "20"),
            "MA" => array("state_name" => "MASSACHUSETTS" ,"state_id" => "21"),
            "MI" => array("state_name" => "MICHIGAN" ,"state_id" => "22"),
            "MN" => array("state_name" => "MINNESOTA" ,"state_id" => "23"),
            "MS" => array("state_name" => "MISSISSIPPI" ,"state_id" => "24"),
            "MO" => array("state_name" => "MISSOURI" ,"state_id" => "25"),
            "MT" => array("state_name" => "MONTANA" ,"state_id" => "26"),
            "NE" => array("state_name" => "NEBRASKA" ,"state_id" => "27"),
            "NV" => array("state_name" => "NEVADA" ,"state_id" => "28"),
            "NH" => array("state_name" => "NEW HAMPSHIRE" ,"state_id" => "29"),
            "NJ" => array("state_name" => "NEW JERSEY" ,"state_id" => "30"),
            "NM" => array("state_name" => "NEW MEXICO" ,"state_id" => "31"),
            "NY" => array("state_name" => "NEW YORK" ,"state_id" => "32"),
            "NC" => array("state_name" => "NORTH CAROLINA" ,"state_id" => "33"),
            "ND" => array("state_name" => "NORTH DAKOTA" ,"state_id" => "34"),
            "OH" => array("state_name" => "OHIO" ,"state_id" => "35"),
            "OK" => array("state_name" => "OKLAHOMA" ,"state_id" => "36"),
            "OR" => array("state_name" => "OREGON" ,"state_id" => "37"),
            "PA" => array("state_name" => "PENNSYLVANIA" ,"state_id" => "38"),
            "RI" => array("state_name" => "RHODE ISLAND" ,"state_id" => "39"),
            "SC" => array("state_name" => "SOUTH CAROLINA" ,"state_id" => "40"),
            "SD" => array("state_name" => "SOUTH DAKOTA" ,"state_id" => "41"),
            "TN" => array("state_name" => "TENNESSEE" ,"state_id" => "42"),
            "TX" => array("state_name" => "TEXAS" ,"state_id" => "43"),
            "UT" => array("state_name" => "UTAH" ,"state_id" => "44"),
            "VT" => array("state_name" => "VERMONT" ,"state_id" => "45"),
            "VA" => array("state_name" => "VIRGINIA" ,"state_id" => "46"),
            "WA" => array("state_name" => "WASHINGTON" ,"state_id" => "47"),
            "WV" => array("state_name" => "WEST VIRGINIA" ,"state_id" => "48"),
            "WI" => array("state_name" => "WISCONSIN" ,"state_id" => "49"),
            "WY" => array("state_name" => "WYOMING" ,"state_id" => "50"),
            "DC" => array("state_name" => "DC" ,"state_id" => "51"),
        );

        $address = array();
        if( array_key_exists($state , $arrayState) ){
            $address['state_id'] = $arrayState[$state]['state_id'];
            $address['state_name'] = $arrayState[$state]['state_name'];
            $address['state_code'] = $state;
        }

        return $address;
    }
    // End check state by array Ping & Post

    //PING & POST
    public function check_ip_address($ip_address, $state_code, $state_name){
        if( !empty($ip_address) ) {
            $ip_details = \Location::get($ip_address);
            if (!empty($ip_details->countryCode)) {
                //if ($ip_details->countryCode == "US" && ($ip_details->regionCode == $state_code || $ip_details->regionName == $state_name)) {
                if ($ip_details->countryCode == "US") {
                    return "true";
                }
            }
            return "false";
        }
        return "true";
    }

    //PING & POST
    public function check_lead_id($lead_id){
        if(!empty($lead_id)){
            //For Check Jornaya LeadId
            $curl = curl_init();

            $JORNAYA_lac = config('services.JORNAYA.JORNAYA_lac', '');
            $jornaya_leadid_url = "https://api.leadid.com/Authenticate?lac=$JORNAYA_lac&id=$lead_id";

            curl_setopt_array($curl, array(
                CURLOPT_URL => $jornaya_leadid_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(),
                CURLOPT_TIMEOUT => 3 //Time Out 3s
            ));

            $response = curl_exec($curl);
            $response= json_decode($response, true);

            curl_close($curl);

            if(!empty($response['error'])){
                return "false";
            }
        }
        return "true";
    }

    //PING
    public function check_lead_id_dup($lead_id){
        if( !empty($lead_id) ) {
            $is_exist1 = LeadsCustomer::where('universal_leadid', $lead_id)->first();
            //$is_exist2 = PingLeads::where('universal_leadid', $lead_id)->first();

            //if (!empty($is_exist1) || !empty($is_exist2)) {
            if (!empty($is_exist1)) {
                return "false";
            }
        }
        return "true";
    }

    //Ping & Post
    public function check_vendor_id($vendor_id, $service = ''){
        $is_valid_vendor_id = DB::table('campaigns')
            ->where('vendor_id', $vendor_id)
            ->where('campaign_visibility', 1)
            ->where('campaign_status_id', "<>", 2)
            ->where('is_seller', 1);

        if(!empty($service)){
            $is_valid_vendor_id->where('service_campaign_id', $service);
        }

        $is_valid_vendor_id = $is_valid_vendor_id->first();

        return $is_valid_vendor_id;
    }

    //POST
//    public function trusted_form_audit($trusted_form){
//        if( !empty($trusted_form) ) {
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $trusted_form,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_HTTPHEADER => array(
//                    "Content-Type: application/json",
//                    "Accept: application/json",
//                    "charset: utf-8",
//                    "Authorization: Basic " . config('services.Trusted_Form_Auth', '')
//                ),
//                CURLOPT_TIMEOUT => 3 //Time Out 3s
//            ));
//
//            $response = curl_exec($curl);
//            $response= json_decode($response, true);
//
//            curl_close($curl);
//
//            if(!empty($response['outcome'])){
//                if($response['outcome'] == "success"){
//                    if( !empty($response['cert']['approx_ip_geo']['country_code'])
//                        && !empty($response['cert']['age_seconds'])
//                        && !empty($response['cert']['event_duration_ms']) ){
//
//                        if( $response['cert']['approx_ip_geo']['country_code'] == "US"
//                            && (($response['cert']['age_seconds'] / 60) / 60) <= 24
//                            && ($response['cert']['event_duration_ms'] / 1000) >= 30 ){
//
//                            return "true";
//                        }
//                    }
//                }
//            }
//            return "Invalid TrustedForm Certificate!";
//        }
//        return "true";
//    }

    //Post
    public function phone_validations($phone){
        if(strlen($phone) == 10){
            //Numverify
//            $access_key = 'c868fe32bff89218d8ade16664237004';
//            $urlRequest = 'https://apilayer.net/api/validate?access_key='.$access_key.'&number='.$phone.'&country_code=US';
//            $init = curl_init();
//            curl_setopt($init, CURLOPT_URL, $urlRequest);//connect the server
//            curl_setopt($init, CURLOPT_POST, 1);
//            curl_setopt($init, CURLOPT_RETURNTRANSFER, true); //the result of connection
//            curl_setopt($init, CURLOPT_HEADER, false); //get back the header
//            $output = curl_exec($init);
//            curl_close($init);
//            $result = json_decode($output, true);
//            if(!empty($result['valid'])){
//                if ($result['valid'] != 'true') {
//                    return "Invalid Phone Number!";
//                }
//            }

            //RealValidation
            $access_key = '387BFA33-ABFA-4A41-9FCB-E2854CEC6E72';
            $urlRequest = "https://api.realvalidation.com/rpvWebService/RealPhoneValidationScrub.php?phone=$phone&token=$access_key&output=json";
            $init = curl_init();
            curl_setopt($init, CURLOPT_URL, $urlRequest);//connect the server
            curl_setopt($init, CURLOPT_POST, 1);
            curl_setopt($init, CURLOPT_RETURNTRANSFER, true); //the result of connection
            curl_setopt($init, CURLOPT_HEADER, false); //get back the header
            curl_setopt($init, CURLOPT_TIMEOUT, 3); //Time Out 3s
            $output = curl_exec($init);
            curl_close($init);
            $result = json_decode($output, true);
            $success_respnse = array("connected", "connected-75", "busy", "pending", "unknown", "unauthorized", "server-unavailable", "ERROR");
            if(!empty($result['status'])){
                if (!in_array($result['status'], $success_respnse)){
                    return "Invalid/disconnected Phone Number!";
                }
            }
        } else {
            return 'Invalid phone_number value; it has to be 10 characters';
        }
        return "true";
    }

    public function name_validations($first_name, $last_name){
        $full_name = strtolower($first_name . " " . $last_name);

        // check if name contains letters only
        if (preg_match('/^[a-zA-Z\s]+$/', $full_name) == 0) {
            return "Invalid Name!";
        }

        $nameParts = explode(" ", $full_name);
        $count_words = count($nameParts);
        $count_words -= 1;
        $validateNameWords = new BadWordsFilter();

        foreach ($nameParts as $key => $name){
            // check if name contains sequential letters
            $countSequentialChar = preg_match_all('/(\w)\1{2,}/', $name);
            if ($countSequentialChar > 0) {
                return "Invalid Name!";
            }
            // Check Bad Words
            $validateNameWordsResult = $validateNameWords->validateWords($name);
            if ($validateNameWordsResult == "badWord"){
                return "Invalid Name!";
            }
            if($key == 0 || $key == $count_words){
                // check if name contains more than 15 characters or fewer than 2
                if (strlen($name) > 16 || strlen($name) < 2) {
                    return "Invalid Name!";
                }
                // count unique char inside name
                $countUniquechar = count(array_unique(str_split($name)));
                if ($countUniquechar < 2) {
                    return "Invalid Name!";
                }
                // Count vowels inside the name
                $countVowelsChar = preg_match_all('/[aeiouwy]/i', $name);
                if ($countVowelsChar < 1) {
                    return "Invalid Name!";
                }
            }
        }
        return "true";
    }

    public function email_validation($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid Email Address!";
        } else {
            $emailSplit = explode("@", $email);
            $emailBeforeAt = $emailSplit[0];
            $emailafterAt = $emailSplit[1];
            $emailAfterDot = explode(".", $emailafterAt);
            $domain = $emailAfterDot[0];
            $domain2 = $emailAfterDot[1];
            if (strlen($domain) < 2 || strlen($domain2) < 2) {
                return "Invalid Email Address!";
            }
        }
        return "true";
    }

    public function lead_ip_validation_ipqs($ip_address){
        if(!empty($ip_address)){
            $apiToken = "xvS204UnNAuBABLERblpdHHXEvx50t8X";
            $user_language = "en-US";
            $strictness = "0";

            $urlRequest = "https://ipqualityscore.com/api/json/ip/$apiToken/$ip_address?strictness=$strictness&user_language=$user_language";

            $init = curl_init();
            curl_setopt($init, CURLOPT_URL, $urlRequest);//connect the server
            curl_setopt($init, CURLOPT_POST, 1);
            curl_setopt($init, CURLOPT_RETURNTRANSFER, true); //the result of connection
            curl_setopt($init, CURLOPT_HEADER, false); //get back the header
            curl_setopt($init, CURLOPT_TIMEOUT, 3); //Time Out 3s
            $output = curl_exec($init);
            curl_close($init);

            $result = json_decode($output, true);
            if(!empty($result['success'])){
                if($result['fraud_score'] >= 90 || $result['country_code'] != "US"){
                    return "Visitor is suspicious!";
                }
            } else {
                if(!empty($result['message'])){
                    if(str_contains($result['message'], 'Invalid IPv4 address')){
                        return "Invalid IP Address!";
                    }
                }
            }
        }
        return "true";
    }

    public function lead_details_validation_ipqs ($leadDataArray){
        $fname = $leadDataArray['first_name'];
        $lname = $leadDataArray['last_name'];
        $phone = $leadDataArray['phone_number'];
        $email = $leadDataArray['email'];
        $street = $leadDataArray['street'];
        $ip_address = $leadDataArray['ip_address'];
        $UserAgent = $leadDataArray['UserAgent'];
        $state_name = $leadDataArray['state_name'];
        $zip_code_list = $leadDataArray['zip_code_list'];
        $billing_country = $leadDataArray['billing_country'];
        $fastResponse = $leadDataArray['fast'];
        $searchStrictness = $leadDataArray['strictness'];
        $lighter_penalties = $leadDataArray['lighter_penalties'];
        $allow_public_access_points = $leadDataArray['allow_public_access_points'];
        $api_token = $leadDataArray['apiToken'];

        $urlRequest = "https://ipqualityscore.com/api/json/ip/$api_token/$ip_address";
        $urlRequest .= "?user_agent=$UserAgent&billing_first_name=$fname&billing_last_name=$lname&billing_country=$billing_country&billing_address_1=$street&billing_state=$state_name";
        $urlRequest .= "&billing_zipcode=$zip_code_list&billing_phone=$phone&billing_email=$email&fast=$fastResponse&strictness=$searchStrictness&lighter_penalties=$lighter_penalties&allow_public_access_points=$allow_public_access_points";
        $urlRequest = str_replace(" ", "%20", $urlRequest);

        $init = curl_init();
        curl_setopt($init, CURLOPT_URL, $urlRequest);//connect the server
        curl_setopt($init, CURLOPT_POST, 1);
        curl_setopt($init, CURLOPT_RETURNTRANSFER, true); //the result of connection
        curl_setopt($init, CURLOPT_HEADER, false); //get back the header
        curl_setopt($init, CURLOPT_TIMEOUT, 3); //Time Out 3s
        $output = curl_exec($init);
        curl_close($init);

        $result = json_decode($output, true);
        if(isset($result['success']) && $result['success'] === false){
            if (str_contains($result['message'], 'Invalid IPv4 address, IPv6 address or hostname')){
                return "Invalid IP Address!";
            }
        } else {
            if(isset($result['success']) && $result['success'] === true){
                if ($result['fraud_score'] >= 90 || $result['transaction_details']['risk_score'] >= 90){
                    return "Visitor is suspicious!";
                } else if ($result['fraud_score'] >= 90 && $result['abuse_velocity'] == "high"){
                    return "Visitor is suspicious!";
                } else if ($result['country_code'] != "US"){
                    return "The lead was filled outside The United States Of America";
                } else if (!empty($result['transaction_details']['risk_factors'])){
                    //return "Visitor is suspicious!";
                }
            }
        }
        return "true";
    }

    public function trestleiq_validation($phone,$email,$fname,$lname,$validation_phone_with_name=0){
        $name = $fname."%20".$lname;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.trestleiq.com/1.1/real_contact?name=$name&phone=$phone&email=$email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: UgxbVYQWMY8fnLBQvEN2h9yTkUXz6ILp3SbRcnfx'
            ),
        ));

        $result = curl_exec($curl);
        $result = json_decode($result , true);

        curl_close($curl);
        if(empty($result)){
            $rr = array($result);
            Log::info('trestle Leads', $rr);
            return "true";
        }

            if($result['phone.is_valid'] == true && $result['email.is_valid'] == true){
                if($result['phone.contact_grade'] == "A" || $result['phone.contact_grade'] == "B"){
                    if($validation_phone_with_name=1){
                            if($result['phone.name_match'] == true){
                                return "true";
                            }else{
                                return "failed validation!";
                            }
                    }else{
                        return "true";
                    }

                }else{
                    return "failed validation!";
                }
            }else{
                return "failed validation!";
            }



    }

    //Ping & Post
    public function check_questions_array($request, $service){

        $questions = array();
        $questions['valid'] = 1;
        $is_set_error = 0;

        if( !empty($request['start_time']) ){
            $lead_priority_array = array(
                "Immediately" => "1",
                "Within 6 months" => "2",
                "Not Sure" => "3",
            );

            $request['start_time'] = ucfirst($request['start_time']);
            if( array_key_exists($request['start_time'], $lead_priority_array) ){
                $lead_priority_id = $lead_priority_array[$request['start_time']];
                $lead_priority_name = $request['start_time'];
            } else {
                $lead_priority_id = 3;
                $lead_priority_name = "Not Sure";
            }
        }
        else {
            $lead_priority_id = 3;
            $lead_priority_name = "Not Sure";
        }

        switch ($service){
            case 1:
                //Window Service
                $this->validate($request, [
                    'windows_number' => ['required'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $windows_number_array = array(
                    "1" => "1",
                    "2" => "2" ,
                    "3-5" => "3",
                    "6-9" => "4",
                    "10+" => "5",
                );

                $number_of_windows_id = $number_of_windows_name = '';
                if( array_key_exists($request['windows_number'], $windows_number_array) ){
                    $number_of_windows_id = $windows_number_array[$request['windows_number']];
                    $number_of_windows_name = $request['windows_number'];
                } else {
                    $questions['error'][] = 'Invalid number_of_windows value';
                    $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'How many windows are involved? ' . $number_of_windows_name,
                    'four' => 'Type of the project? ' . $project_nature_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'number_of_window' => $number_of_windows_name,
                    'project_nature' => $project_nature_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'number_of_window' => $number_of_windows_id,
                    'project_nature' => $project_nature_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$homeOwn, $lead_priority_name, $number_of_windows_name, $project_nature_name]";
                break;
            case 2:
                //Solar Service
                $this->validate($request, [
                    'power_solution' => ['required', 'string', 'max:255'],
                    'roof_shade' => ['required', 'string', 'max:255'],
                    'monthly_electric_bill' => ['required', 'string', 'max:255'],
                    'property_type' => ['required', 'string', 'max:255']
                ]);

                switch ($request['monthly_electric_bill']){
                    case "$0 - $100":
                        $request['monthly_electric_bill'] = "$51 - $100";
                        break;
                    case "$100 - $200":
                        $request['monthly_electric_bill'] = "$151 - $200";
                        break;
                    case "$200 - $300":
                        $request['monthly_electric_bill'] = "$201 - $300";
                        break;
                    case "$400 - $500":
                        $request['monthly_electric_bill'] = "$401 - $500";
                        break;
                    case "$+500":
                        $request['monthly_electric_bill'] = "$500+";
                        break;
                }

                $solar_solution_array = array(
                    "Solar Electricity for my Home" => "1",
                    "Solar Water Heating for my Home" => "2",
                    "Solar Electricity & Water Heating for my Home" => "3",
                    "Solar for my Business" => "4",
                );

                $power_solution_id = $power_solution_name = '';
                if( array_key_exists($request['power_solution'], $solar_solution_array) ){
                    $power_solution_id = $solar_solution_array[$request['power_solution']];
                    $power_solution_name = $request['power_solution'];
                } else {
                    $questions['error'][] = 'Invalid power_solution value';
                    $is_set_error = 1;
                }

                $solar_sun_array = array(
                    "Full Sun" => "1",
                    "Partial Sun" => "2",
                    "Mostly Shaded" => "3",
                    "Not Sure" => "4",
                );

                $roof_shade_id = $roof_shade_name = '';
                $request['roof_shade'] = ucwords($request['roof_shade']);
                if( array_key_exists($request['roof_shade'], $solar_sun_array) ){
                    $roof_shade_id = $solar_sun_array[$request['roof_shade']];
                    $roof_shade_name = $request['roof_shade'];
                } else {
                    $questions['error'][] = 'Invalid roof_shade value';
                    $is_set_error = 1;
                }

                $avg_money_array = array(
                    "$51 - $100" => "1",
                    "$151 - $200" => "2",
                    "$201 - $300" => "3",
                    "$401 - $500" => "4",
                    "$500+" => "5",
                    "$0 - $50" => "6",
                    "$101 - $150" => "7",
                    "$301 - $400" => "8",
                );

                $monthly_electric_bill_id = $monthly_electric_bill_name = '';
                if( array_key_exists($request['monthly_electric_bill'], $avg_money_array) ){
                    $monthly_electric_bill_id = $avg_money_array[$request['monthly_electric_bill']];
                    $monthly_electric_bill_name = $request['monthly_electric_bill'];
                } else {
                    $questions['error'][] = 'Invalid monthly_electric_bill value';
                    $is_set_error = 1;
                }

                $property_type_array = array(
                    "Owned" => "1",
                    "Rented" => "2",
                    "Business" => "3",
                );

                $property_type_id = $property_type_name = '';
                $request['property_type'] = ucwords($request['property_type']);
                if( array_key_exists($request['property_type'], $property_type_array) ){
                    $property_type_id = $property_type_array[$request['property_type']];
                    $property_type_name = $request['property_type'];
                } else {
                    $questions['error'][] = 'Invalid property_type value';
                    $is_set_error = 1;
                }

                $utility_provider_id = (!empty($request['utility_provider']) ? $request['utility_provider'] : 'Other');
                $utility_provider_name = (!empty($request['utility_provider']) ? $request['utility_provider'] : 'Other');

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of the project? ' . $power_solution_name,
                    'two' => "Property sun exposure " . $roof_shade_name,
                    'three' => 'What is the current utility provider for the customer? ' . $utility_provider_name,
                    'four' => 'What is the average monthly electricity bill for the customer? ' . $monthly_electric_bill_name,
                    'five' => 'Property Type: ' . $property_type_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'power_solution' => $power_solution_name,
                    'roof_shade' => $roof_shade_name,
                    'utility_provider' => $utility_provider_name,
                    'monthly_electric_bill' => $monthly_electric_bill_name,
                    'property_type' => $property_type_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'power_solution' => $power_solution_id,
                    'roof_shade' => $roof_shade_id,
                    'utility_provider' => $utility_provider_id,
                    'monthly_electric_bill' => $monthly_electric_bill_id,
                    'property_type' => $property_type_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$power_solution_name, $roof_shade_name, $utility_provider_name, $monthly_electric_bill_name, $property_type_name]";
                break;
            case 3:
                //Home Security Service
                $this->validate($request, [
                    'installation_preferences' => ['required', 'string', 'max:255'],
                    'lead_have_item_before_it' => ['required', 'string', 'max:255'],
                    'property_type' => ['required', 'string', 'max:255'],
                ]);

                $installation_preferences_array = array(
                    "Professional installation" => "1",
                    "Self installation" => "2",
                    "No preference" => "3",
                );

                $installation_preferences_id = $installation_preferences_name = '';
                $request['installation_preferences'] = ucfirst($request['installation_preferences']);
                if( array_key_exists($request['installation_preferences'], $installation_preferences_array) ){
                    $installation_preferences_id = $installation_preferences_array[$request['installation_preferences']];
                    $installation_preferences_name = $request['installation_preferences'];
                } else {
                    $questions['error'][] = 'Invalid installation_preferences value';
                    $is_set_error = 1;
                }

                $lead_have_item_before_it = '';
                $request['lead_have_item_before_it'] = ucfirst($request['lead_have_item_before_it']);
                if ($request['lead_have_item_before_it'] == 'Yes') {
                    $lead_have_item_before_it_id = '1';
                    $lead_have_item_before_it = $request['lead_have_item_before_it'];
                } elseif ($request['lead_have_item_before_it'] == 'No') {
                    $lead_have_item_before_it_id = '2';
                    $lead_have_item_before_it = $request['lead_have_item_before_it'];
                } else {
                    $questions['error'][] = 'Invalid lead_have_item_before_it value';
                    $is_set_error = 1;
                }

                $property_type_array = array(
                    "Owned" => "1",
                    "Rented" => "2",
                    "Business" => "3",
                );

                $property_type_id = $property_type_name = '';
                $request['property_type'] = ucwords($request['property_type']);
                if( array_key_exists($request['property_type'], $property_type_array) ){
                    $property_type_id = $property_type_array[$request['property_type']];
                    $property_type_name = $request['property_type'];
                } else {
                    $questions['error'][] = 'Invalid property_type value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Installation Preferences: ' . $installation_preferences_name,
                    'two' => 'Does the customer have An Existing Alarm And/ Or Monitoring System? ' . $lead_have_item_before_it,
                    'three' => 'Property Type: ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'Installation_Preferences' => $installation_preferences_name,
                    'lead_have_item_before_it' => $lead_have_item_before_it,
                    'start_time' => $lead_priority_name,
                    'property_type' => $property_type_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'Installation_Preferences' => $installation_preferences_id,
                    'lead_have_item_before_it' => $lead_have_item_before_it_id,
                    'start_time' => $lead_priority_id,
                    'property_type' => $property_type_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$installation_preferences_name, $lead_have_item_before_it, $property_type_name, $lead_priority_name]";
                break;
            case 4:
                //Flooring
                $this->validate($request, [
                    'type_of_flooring' => ['required', 'string', 'max:255'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                //Flooring Service
                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $type_of_flooring_array = array(
                    "Vinyl Linoleum Flooring" => "1",
                    "Tile Flooring" => "2",
                    "Hardwood Flooring" => "3",
                    "Laminate Flooring" => "4",
                    "Carpet" => "5",
                );

                $type_of_flooring_id = $type_of_flooring_name = '';
                $request['type_of_flooring'] = ucwords($request['type_of_flooring']);
                if( array_key_exists($request['type_of_flooring'], $type_of_flooring_array) ){
                    $type_of_flooring_id = $type_of_flooring_array[$request['type_of_flooring']];
                    $type_of_flooring_name = $request['type_of_flooring'];
                } else {
                    $questions['error'][] = 'Invalid type_of_flooring value';
                    $is_set_error = 1;
                }

                $request['project_nature'] = ucwords($request['project_nature']);
                $nature_flooring_project_id = $nature_flooring_project_name = '';
                switch($request['project_nature']){
                    case "Install":
                    case "Install New Flooring":
                        $request['project_nature'] = "Install New Flooring";
                        $nature_flooring_project_id = 1;
                        $nature_flooring_project_name = "Install New Flooring";
                        break;
                    case "Replace":
                    case "Refinish Existing Flooring":
                        $request['project_nature'] = "Refinish Existing Flooring";
                        $nature_flooring_project_id = 2;
                        $nature_flooring_project_name = "Refinish Existing Flooring";
                        break;
                    case "Repair":
                    case "Repair Existing Flooring":
                        $request['project_nature'] = "Repair Existing Flooring";
                        $nature_flooring_project_id = 3;
                        $nature_flooring_project_name = "Repair Existing Flooring";
                        break;
                    default:
                        $questions['error'][] = 'Invalid project_nature value';
                        $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of flooring? ' . $type_of_flooring_name,
                    'two' => 'Type of the project? ' . $nature_flooring_project_name,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'flooring_type' => $type_of_flooring_name,
                    'project_nature' => $nature_flooring_project_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'flooring_type' => $type_of_flooring_id,
                    'project_nature' => $nature_flooring_project_id,
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$type_of_flooring_name, $nature_flooring_project_name, $lead_priority_name, $homeOwn]";
                break;
            case 5:
                //Walk In Tubs
                $this->validate($request, [
                    'reason' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                //Walk-in-Tops Service
                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $reason_array = array(
                    "Safety" => "1",
                    "Therapeutic" => "2",
                    "Others" => "3",
                );

                $reason_id = $reason_name = '';
                $request['reason'] = ucwords($request['reason']);
                if( array_key_exists($request['reason'], $reason_array) ){
                    $reason_id = $reason_array[$request['reason']];
                    $reason_name = $request['reason'];
                } else {
                    $questions['error'][] = 'Invalid reason value';
                    $is_set_error = 1;
                }

                $desired_feature_array = array(
                    "Whirlpool" => "1",
                    "Quick Water Release" => "2",
                    "Soaking" => "3",
                    "Air/Hydro Massager" => "4",
                );

                $request['features'] = ucwords($request['features']);
                if( array_key_exists($request['features'], $desired_feature_array) ){
                    $desired_featuers_id = $desired_feature_array[$request['features']];
                    $desired_featuers_name = $request['features'];
                } else {
                    $desired_featuers_id = 1;
                    $desired_featuers_name = "Whirlpool";
                }

                $desired_featuers_arr = array($desired_featuers_id);
                $desired_featuers_json = json_encode($desired_featuers_arr);

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of Walk-In Tub? ' . $reason_name,
                    'two' => 'Desired Features? ' . $desired_featuers_name,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] =array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'reason' => $reason_name,
                    'features' => $desired_featuers_name
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'reason' => $reason_id,
                    'features' => $desired_featuers_json
                );

                $questions['data_arr']['dataMassageForDB'] = "[$reason_name, ($desired_featuers_name), $lead_priority_name, $homeOwn]";
                break;
            case 6:
                //Roofing
                $this->validate($request, [
                    'roof_type' => ['required', 'string', 'max:255'],
                    'property_type' => ['required', 'string', 'max:255'],
                    'project_nature' => ['required', 'string', 'max:255']
                ]);

                $type_of_roofing_array = array(
                    "Asphalt Roofing" => "1",
                    "Wood Shake/Composite Roofing" => "2",
                    "Metal Roofing" => "3",
                    "Natural Slate Roofing" => "4",
                    "Tile Roofing" => "5",
                );

                $type_of_roofing_id = $type_of_roofing_name = '';
                $request['roof_type'] = ucwords($request['roof_type']);
                if( array_key_exists($request['roof_type'], $type_of_roofing_array) ){
                    $type_of_roofing_id = $type_of_roofing_array[$request['roof_type']];
                    $type_of_roofing_name = $request['roof_type'];
                } else {
                    $questions['error'][] = 'Invalid roof_type value';
                    $is_set_error = 1;
                }

                $request['project_nature'] = ucfirst($request['project_nature']);
                $nature_of_roofing_id = $nature_of_roofing_name = '';
                switch($request['project_nature']){
                    case "Install":
                    case "Install roof on new construction":
                        $request['project_nature'] = "Install roof on new construction";
                        $nature_of_roofing_id = 1;
                        $nature_of_roofing_name = "Install roof on new construction";
                        break;
                    case "Replace":
                    case "Completely replace roof":
                        $request['project_nature'] = "Completely replace roof";
                        $nature_of_roofing_id = 2;
                        $nature_of_roofing_name = "Completely replace roof";
                        break;
                    case "Repair":
                    case "Repair existing roof":
                        $request['project_nature'] = "Repair existing roof";
                        $nature_of_roofing_id = 3;
                        $nature_of_roofing_name = "Repair existing roof";
                        break;
                    default:
                        $questions['error'][] = 'Invalid project_nature value';
                        $is_set_error = 1;
                }

                $property_type_roofing_array = array(
                    "Residential" => "1",
                    "Commercial" => "2",
                );

                $property_type_id = $property_type_name = '';
                $request['property_type'] = ucwords($request['property_type']);
                if( array_key_exists($request['property_type'], $property_type_roofing_array) ){
                    $property_type_id = $property_type_roofing_array[$request['property_type']];
                    $property_type_name = $request['property_type'];
                } else {
                    $questions['error'][] = 'Invalid property_type value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of roofing? ' . $type_of_roofing_name,
                    'two' => 'Type of the project? ' . $nature_of_roofing_name,
                    'three' => 'Property Type ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] =array(
                    'roof_type' => $type_of_roofing_name,
                    'project_nature' => $nature_of_roofing_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'roof_type' => $type_of_roofing_id,
                    'project_nature' => $nature_of_roofing_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$type_of_roofing_name, $nature_of_roofing_name, $property_type_name, $lead_priority_name]";
                break;
            case 7:
                //Home Siding
                $this->validate($request, [
                    'type_of_siding' => ['required'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                //Home Siding Service
                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $request['project_nature'] = ucfirst($request['project_nature']);
                $project_nature_id = $project_nature_name = '';
                switch($request['project_nature']){
                    case "Install":
                    case "Siding for a new home":
                        $request['project_nature'] = "Siding for a new home";
                        $project_nature_id = 1;
                        $project_nature_name = "Siding for a new home";
                        break;
                    case "Siding for a new addition":
                        $request['project_nature'] = "Siding for a new addition";
                        $project_nature_id = 2;
                        $project_nature_name = "Siding for a new addition";
                        break;
                    case "Replace":
                    case "Replace existing siding":
                        $request['project_nature'] = "Replace existing siding";
                        $project_nature_id = 3;
                        $project_nature_name = "Replace existing siding";
                        break;
                    case "Repair":
                    case "Repair section(s) of siding":
                        $request['project_nature'] = "Repair section(s) of siding";
                        $project_nature_id = 4;
                        $project_nature_name = "Repair section(s) of siding";
                        break;
                    default:
                        $questions['error'][] = 'Invalid project_nature value';
                        $is_set_error = 1;
                }

                $project_nature_siding_id = 1;
                if ($project_nature_id == 3) {
                    $project_nature_siding_id = 2;
                } else if ($project_nature_id == 4) {
                    $project_nature_siding_id = 3;
                }

                $type_of_siding_array = array(
                    "Vinyl Siding" => "1",
                    "Brickface Siding" => "2",
                    "Composite Wood Siding" => "3",
                    "Aluminum Siding" => "4",
                    "Stoneface Siding" => "5",
                    "Fiber Cement Siding" => "6",
                );

                $type_of_siding_id = $type_of_siding_name = '';
                $request['type_of_siding'] = ucwords($request['type_of_siding']);
                if( array_key_exists($request['type_of_siding'], $type_of_siding_array) ){
                    $type_of_siding_id = $type_of_siding_array[$request['type_of_siding']];
                    $type_of_siding_name = ($request['type_of_siding'] == "Composite Wood Siding" ? "Composite wood Siding" : $request['type_of_siding']);
                } else {
                    $questions['error'][] = 'Invalid type_of_siding value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of siding? ' . $type_of_siding_name,
                    'two' => 'Type of the project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'type_of_siding' => $type_of_siding_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'type_of_siding' => $type_of_siding_id,
                    'project_nature' => $project_nature_siding_id,
                    'project_nature_siding' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$type_of_siding_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 8:
                //Kitchen
                $this->validate($request, [
                    'kitchen_type' => ['required'],
                    'removing_adding_walls' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                //Kitchen Service
                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $service_kitchen_array = array(
                    "Full Kitchen Remodeling" => "1",
                    "Cabinet Refacing" => "2",
                    "Cabinet Install" => "3",
                );

                $service_kitchen_id = $service_kitchen_name = '';
                $request['kitchen_type'] = ucwords($request['kitchen_type']);
                if( array_key_exists($request['kitchen_type'], $service_kitchen_array) ){
                    $service_kitchen_id = $service_kitchen_array[$request['kitchen_type']];
                    $service_kitchen_name = $request['kitchen_type'];
                } else {
                    $questions['error'][] = 'Invalid kitchen_type value';
                    $is_set_error = 1;
                }

                $removing_adding_walls = $removing_adding_walls_id = '';
                $request['removing_adding_walls'] = ucfirst($request['removing_adding_walls']);
                if($request['removing_adding_walls'] == 'Yes') {
                    $removing_adding_walls_id = '1';
                    $removing_adding_walls=$request['removing_adding_walls'];
                } elseif($request['removing_adding_walls'] == 'No') {
                    $removing_adding_walls_id = '0';
                    $removing_adding_walls=$request['removing_adding_walls'];
                } else {
                    $questions['error'][] = 'Invalid removing_adding_walls value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Services required? ' . $service_kitchen_name,
                    'two' => 'Demolishing/building walls? ' . $removing_adding_walls,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] = array(
                    'services' => $service_kitchen_name,
                    'demolishing_walls' => $removing_adding_walls,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'services' => $service_kitchen_id,
                    'demolishing_walls' => $removing_adding_walls_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id,
                );

                $questions['data_arr']['dataMassageForDB'] = "[$service_kitchen_name, $removing_adding_walls, $homeOwn, $lead_priority_name]";
                break;
            case 9:
                //Bathroom
                $this->validate($request, [
                    'bathroom_type' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                //Bathroom Service
                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $bathroom_type_array = array(
                    "Full Remodel" => "1",
                    "Cabinets / Vanity" => "2",
                    "Countertops" => "3",
                    "Flooring" => "4",
                    "Shower / Bath" => "5",
                    "Sinks" => "6",
                    "Toilet" => "7",
                );

                $bathroom_type_id = $bathroom_type_name = '';
                $request['bathroom_type'] = ucwords($request['bathroom_type']);
                if( array_key_exists($request['bathroom_type'], $bathroom_type_array) ){
                    $bathroom_type_id = $bathroom_type_array[$request['bathroom_type']];
                    $bathroom_type_name = $request['bathroom_type'];
                } else {
                    $questions['error'][] = 'Invalid bathroom_type value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Services required? ' . $bathroom_type_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'services' => $bathroom_type_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'services' => $bathroom_type_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$bathroom_type_name, $homeOwn, $lead_priority_name]";
                break;
            case 10:
                // stairs Service
                $this->validate($request, [
                    'stairs_type' => ['required'],
                    'stairs_reason' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $stairs_type_array = array(
                    "Straight" => "1",
                    "Curved" => "2"
                );

                $stairs_type_id = $stairs_type_name = '';
                $request['stairs_type'] = ucwords($request['stairs_type']);
                if( array_key_exists($request['stairs_type'], $stairs_type_array) ){
                    $stairs_type_id = $stairs_type_array[$request['stairs_type']];
                    $stairs_type_name = $request['stairs_type'];
                } else {
                    $questions['error'][] = 'Invalid stairs_type value';
                    $is_set_error = 1;
                }

                $stairs_reason_array = array(
                    "Mobility" => "1",
                    "Safety" => "2",
                    "Other" => "3"
                );

                $stairs_reason_id = $stairs_reason_name = '';
                $request['stairs_reason'] = ucwords($request['stairs_reason']);
                if( array_key_exists($request['stairs_reason'], $stairs_reason_array) ){
                    $stairs_reason_id = $stairs_reason_array[$request['stairs_reason']];
                    $stairs_reason_name = $request['stairs_reason'];
                } else {
                    $questions['error'][] = 'Invalid stairs_reason value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of stairs? ' . $stairs_type_name,
                    'two' => 'The reason for installing the Stairlift ' . $stairs_reason_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'stairs_type' => $stairs_type_name,
                    'reason' => $stairs_reason_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'stairs_type' => $stairs_type_id,
                    'reason' => $stairs_reason_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$stairs_type_name, $stairs_reason_name, $homeOwn, $lead_priority_name]";
                break;
            case 11:
                //Furnace Service
                $this->validate($request, [
                    'furnace_type' => ['required'],
                    'project_nature' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $furnace_type_array = array(
                    "Do Not Know" => "1",
                    "Electric" => "2",
                    "Natural Gas" => "3",
                    "Oil" => "4",
                    "Propane Gas" => "5"
                );

                $furnace_type_id = $furnace_type_name = '';
                $request['furnace_type'] = ucwords($request['furnace_type']);
                if( array_key_exists($request['furnace_type'], $furnace_type_array) ){
                    $furnace_type_id = $furnace_type_array[$request['furnace_type']];
                    $furnace_type_name = $request['furnace_type'];
                } else {
                    $questions['error'][] = 'Invalid furnace_type value';
                    $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of central heating system required? ' . $furnace_type_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'type_of_heating' => $furnace_type_name,
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'type_of_heating' => $furnace_type_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$homeOwn, $lead_priority_name, $project_nature_name, $furnace_type_name]";
                break;
            case 12:
                //Boiler Service
                $this->validate($request, [
                    'boiler_type' => ['required'],
                    'project_nature' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $boiler_type_array = array(
                    "Do Not Know" => "1",
                    "Electric" => "2",
                    "Natural Gas" => "3",
                    "Oil" => "4",
                    "Propane Gas" => "5"
                );

                $boiler_type_id = $boiler_type_name = '';
                $request['boiler_type'] = ucwords($request['boiler_type']);
                if( array_key_exists($request['boiler_type'], $boiler_type_array) ){
                    $boiler_type_id = $boiler_type_array[$request['boiler_type']];
                    $boiler_type_name = $request['boiler_type'];
                } else {
                    $questions['error'][] = 'Invalid boiler_type value';
                    $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of boiler system required? ' . $boiler_type_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'project_nature' =>$project_nature_name,
                    'type_of_heating' => $boiler_type_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'project_nature' => $project_nature_id,
                    'type_of_heating' => $boiler_type_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$homeOwn, $lead_priority_name, $project_nature_name, $boiler_type_name]";
                break;
            case 13:
                //Central A/C Service
                $this->validate($request, [
                    'project_nature' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$homeOwn, $lead_priority_name, $project_nature_name]";
                break;
            case 14:
                //Cabinet Service
                $this->validate($request, [
                    'project_nature' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                switch($request['project_nature']){
                    case "Install":
                    case "Cabinet Install":
                        $project_nature_name = 'Cabinet Install';
                        $project_nature_id = 1;
                        break;
                    case "Replace":
                    case "Cabinet Refacing":
                        $project_nature_name = 'Cabinet Refacing';
                        $project_nature_id = 3;
                        break;
                    default:
                        $questions['error'][] = 'Invalid project_nature value';
                        $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 15:
                //Plumbing Service
                $this->validate($request, [
                    'plumbing_service' => ['required'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $plumbing_service_array = array(
                    "Faucet/ Fixture Services" => "1",
                    "Pipe Services" => "2" ,
                    "Leak Repair" => "3",
                    "Remodeling/ Construction" => "4" ,
                    "Septic Systems" => "5",
                    "Drain/ Sewer Services" => "6" ,
                    "Shower Services" => "7",
                    "Sump Pump Services" => "8" ,
                    "Toilet Services" => "9",
                    "Water Heater Services" => "10" ,
                    "Water/ Fuel Tank" => "11",
                    "Water Treatment And Purification" => "12" ,
                    "Well Pump Services" => "13",
                    "Backflow Services" => "14" ,
                    "Bathroom Plumbing" => "15",
                    "Camera Line Inspection" => "16" ,
                    "Clogged Sink Repair" => "17",
                    "Disposal Services" => "18" ,
                    "Excavation" => "19",
                    "Grease Trap Services" => "20" ,
                    "Kitchen Plumbing" => "21",
                    "Storm Drain Cleaning" => "22" ,
                    "Trenchless Repairs" => "23",
                    "Water Damage Restoration" => "24" ,
                    "Water Jetting" => "25",
                    "Water Leak Services" => "26",
                    "Basement Plumbing" => "27",
                );

                $plumbing_service_id = $plumbing_service_name = '';
                $request['plumbing_service'] = ucwords($request['plumbing_service']);
                if( array_key_exists($request['plumbing_service'], $plumbing_service_array) ){
                    $plumbing_service_id = $plumbing_service_array[$request['plumbing_service']];
                    $plumbing_service_name = $request['plumbing_service'];
                } else {
                    $questions['error'][] = 'Invalid plumbing_service value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of services required? ' . $plumbing_service_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'services' => $plumbing_service_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'services' => $plumbing_service_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$plumbing_service_name, $homeOwn, $lead_priority_name]";
                break;
            case 16:
                //Bathtubs Service
                $this->validate($request, [
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$homeOwn, $lead_priority_name]";
                break;
            case 17:
                //SunRooms Service
                $this->validate($request, [
                    'sunroom_service' => ['required', 'string', 'max:255'],
                    'property_type' => ['required', 'string', 'max:255']
                ]);

                $sunroom_service_array = array(
                    "Build a new sunroom or patio enclosure" => "1",
                    "Enclose existing porch with roof, walls or windows" => "2",
                    "Screen in existing porch or patio" => "3",
                    "Add a metal awning or cover" => "4",
                    "Add a fabric awning or cover" => "5",
                    "Repair existing sunroom, porch or patio" => "6"
                );

                $sunroom_service_id = $sunroom_service_name = '';
                $request['sunroom_service'] = ucfirst($request['sunroom_service']);
                if( array_key_exists($request['sunroom_service'], $sunroom_service_array) ){
                    $sunroom_service_id = $sunroom_service_array[$request['sunroom_service']];
                    $sunroom_service_name = $request['sunroom_service'];
                } else {
                    $questions['error'][] = 'Invalid sunroom_service value';
                    $is_set_error = 1;
                }

                $property_type_roofing_array = array(
                    "Residential" => "1",
                    "Commercial" => "2",
                );

                $property_type_id = $property_type_name = '';
                $request['property_type'] = ucwords($request['property_type']);
                if( array_key_exists($request['property_type'], $property_type_roofing_array) ){
                    $property_type_id = $property_type_roofing_array[$request['property_type']];
                    $property_type_name = $request['property_type'];
                } else {
                    $questions['error'][] = 'Invalid property_type value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of project/services required? ' . $sunroom_service_name,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the property? ' . $property_type_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'services' => $sunroom_service_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'services' => $sunroom_service_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$sunroom_service_name, $lead_priority_name, $property_type_name]";
                break;
            case 18:
                //Handyman service
                $this->validate($request, [
                    'amount_work' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $amount_work_array = array(
                    "A variety of projects" => "1",
                    "A single project" => "2",
                );

                $amount_work_id = $amount_work_name = '';
                $request['amount_work'] = ucfirst($request['amount_work']);
                if( array_key_exists($request['amount_work'], $amount_work_array) ){
                    $amount_work_id = $amount_work_array[$request['amount_work']];
                    $amount_work_name = $request['amount_work'];
                } else {
                    $questions['error'][] = 'Invalid amount_work value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //Send Request
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Type of project? ' . $amount_work_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $questions['data_arr']['Leaddatadetails'] = array(
                    'services' => $amount_work_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'services' => $amount_work_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$amount_work_name, $homeOwn, $lead_priority_name]";
                break;
            case 19:
                //CounterTops Service
                $this->validate($request, [
                    'countertops_material' => ['required'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                $countertops_service_array = array(
                    "Granite" => "1",
                    "Solid Surface (e.g corian)" => "2",
                    "Marble" => "3",
                    "Wood (e.g butcher block)" => "4",
                    "Stainless Steel" => "5",
                    "Laminate" => "6",
                    "Concrete" => "7",
                    "Other Solid Stone (e.g Quartz)" => "8",
                );

                $countertops_service_id = $countertops_service_name = '';
                $request['countertops_material'] = ucfirst($request['countertops_material']);
                if( array_key_exists($request['countertops_material'], $countertops_service_array) ){
                    $countertops_service_id = $countertops_service_array[$request['countertops_material']];
                    $countertops_service_name = $request['countertops_material'];
                } else {
                    $questions['error'][] = 'Invalid countertops_material value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'CounterTop material: ' . $countertops_service_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] =array(
                    'service' => $countertops_service_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'service' => $countertops_service_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$countertops_service_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 20:
                //Doors Service
                $this->validate($request, [
                    'project_type' => ['required'],
                    'number_of_doors' => ['required'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                $project_type_array = array(
                    "Exterior" => "1",
                    "Interior" => "2"
                );

                $project_type_id = $project_type_name = '';
                $request['project_type'] = ucwords($request['project_type']);
                if( array_key_exists($request['project_type'], $project_type_array) ){
                    $project_type_id = $project_type_array[$request['project_type']];
                    $project_type_name = $request['project_type'];
                } else {
                    $questions['error'][] = 'Invalid project_type value';
                    $is_set_error = 1;
                }

                $number_of_doors_array = array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4+" => "4",
                );

                $number_of_doors_id = $number_of_doors_name = '';
                if( array_key_exists($request['number_of_doors'], $number_of_doors_array) ){
                    $number_of_doors_id = $number_of_doors_array[$request['number_of_doors']];
                    $number_of_doors_name = $request['number_of_doors'];
                } else {
                    $questions['error'][] = 'Invalid number_of_doors value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Interior/Exterior? ' . $project_type_name,
                    'two' => 'Number of doors involved? ' . $number_of_doors_name,
                    'three' => 'Type of project? ' . $project_nature_name,
                    'four' => 'Owner of the Property? ' . $homeOwn,
                    'five' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] =array(
                    'door_type' => $project_type_name,
                    'number_of_door' => $number_of_doors_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'homeOwn' =>$homeOwn_id,
                    'start_time' => $lead_priority_id,
                    'door_type' => $project_type_id,
                    'number_of_door' => $number_of_doors_id,
                    'project_nature' => $project_nature_id,
                );

                $questions['data_arr']['dataMassageForDB'] = "[$project_type_name, $number_of_doors_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 21:
                //Gutter Service
                $this->validate($request, [
                    'gutter_material' => ['required'],
                    'project_nature' => ['required', 'string', 'max:255'],
                    'ownership' => ['required', 'string', 'max:255']
                ]);

                $homeOwn = $homeOwn_id = '';
                $request['ownership'] = ucfirst($request['ownership']);
                switch ($request['ownership']){
                    case 'Yes':
                        $homeOwn_id = '1';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No':
                        $homeOwn_id = '0';
                        $homeOwn=$request['ownership'];
                        break;
                    case 'No, But Authorized to Make Changes':
                        $homeOwn_id = '3';
                        $homeOwn=$request['ownership'];
                        break;
                    default:
                        $questions['error'][] = 'Invalid ownership value';
                        $is_set_error = 1;
                }

                $project_nature_array = array(
                    "Install" => "1",
                    "Replace" => "2",
                    "Repair" => "3",
                );

                $project_nature_id = $project_nature_name = '';
                $request['project_nature'] = ucwords($request['project_nature']);
                if( array_key_exists($request['project_nature'], $project_nature_array) ){
                    $project_nature_id = $project_nature_array[$request['project_nature']];
                    $project_nature_name = $request['project_nature'];
                } else {
                    $questions['error'][] = 'Invalid project_nature value';
                    $is_set_error = 1;
                }

                $gutter_material_array = array(
                    "Copper" => "1",
                    "Galvanized Steel" => "2",
                    "PVC" => "3",
                    "Seamless Aluminum" => "4",
                    "Wood" => "5",
                    "Not Sure" => "6"
                );

                $gutter_material_id = $gutter_material_name = '';
                $request['gutter_material'] = ucwords($request['gutter_material']);
                if( array_key_exists($request['gutter_material'], $gutter_material_array) ){
                    $gutter_material_id = $gutter_material_array[$request['gutter_material']];
                    $gutter_material_name = $request['gutter_material'];
                } else {
                    $questions['error'][] = 'Invalid gutter_material value';
                    $is_set_error = 1;
                }

                if( $is_set_error == 1 ){
                    $questions['valid'] = 3;
                    return $questions;
                }

                //data msg array
                $questions['data_arr']['dataMassageForBuyers'] = array(
                    'one' => 'Gutter material: ' . $gutter_material_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                //Send Request
                $questions['data_arr']['Leaddatadetails'] =array(
                    'service' => $gutter_material_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $questions['data_arr']['LeaddataIDs'] = array(
                    'service' => $gutter_material_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $questions['data_arr']['dataMassageForDB'] = "[$gutter_material_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            default:
                $questions['valid'] = 2;
        }

        return $questions;
    }

    //Post Websites
    public function check_questions_ids_array($request){
        //==============================================================================================================
        //Get Questions Value and validated
        $dataMassageForBuyers = array();
        $Leaddatadetails = array();
        $LeaddataIDs = array();
        $dataMassageForDB = "";

        if( !empty($request['priority']) ){
            $lead_priority_array = array(
                "1" => "Immediately",
                "2" => "Within 6 months",
                "3" => "Not Sure",
            );

            if( array_key_exists($request['priority'], $lead_priority_array) ){
                $lead_priority_id = $request['priority'];
                $lead_priority_name = $lead_priority_array[$request['priority']];
            } else {
                $lead_priority_id = 3;
                $lead_priority_name = "Not Sure";
            }
        } else {
            $lead_priority_id = 3;
            $lead_priority_name = "Not Sure";
        }

        switch ($request['service_id']){
            case 1:
                //Wendows
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $request['ownership'];
                }

                $windows_number_array = array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3-5",
                    "4" => "6-9",
                    "5" => "10+",
                );

                $number_of_windows_id = $number_of_windows_name = '';
                if( array_key_exists($request['numberofwindows'], $windows_number_array) ){
                    $number_of_windows_id = $request['numberofwindows'];
                    $number_of_windows_name = $windows_number_array[$request['numberofwindows']];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_id = $request['projectnature'];
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'How many windows are involved? ' . $number_of_windows_name,
                    'four' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'number_of_window' => $number_of_windows_id,
                    'project_nature' => $project_nature_id
                );

                $Leaddatadetails = array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'number_of_window' => $number_of_windows_name,
                    'project_nature' => $project_nature_name,
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $number_of_windows_name, $project_nature_name]";
                break;
            case 2:
                //Solar
                $solar_solution_array = array(
                    "1" => "Solar Electricity for my Home",
                    "2" => "Solar Water Heating for my Home",
                    "3" => "Solar Electricity & Water Heating for my Home",
                    "4" => "Solar for my Business",
                );

                $power_solution_id = $power_solution_name = '';
                if( array_key_exists($request['solor_solution'], $solar_solution_array) ){
                    $power_solution_name = $solar_solution_array[$request['solor_solution']];
                    $power_solution_id = $request['solor_solution'];
                }

                $solar_sun_array = array(
                    "1" => "Full Sun",
                    "2" => "Partial Sun",
                    "3" => "Mostly Shaded",
                    "4" => "Not Sure",
                );

                $roof_shade_id = $roof_shade_name = '';
                if( array_key_exists($request['solor_sun'], $solar_sun_array) ){
                    $roof_shade_name = $solar_sun_array[$request['solor_sun']];
                    $roof_shade_id = $request['solor_sun'];
                }

                $avg_money_array = array(
                    "1" => "$51 - $100",
                    "2" => "$151 - $200",
                    "3" => "$201 - $300",
                    "4" => "$401 - $500",
                    "5" => "$500+",
                    "6" => "$0 - $50",
                    "7" => "$101 - $150",
                    "8" => "$301 - $400",
                );

                $monthly_electric_bill_id = $monthly_electric_bill_name = '';
                if( array_key_exists($request['avg_money'], $avg_money_array) ){
                    $monthly_electric_bill_name = $avg_money_array[$request['avg_money']];
                    $monthly_electric_bill_id = $request['avg_money'];
                }

                $property_type_array = array(
                    "1" => "Owned",
                    "2" => "Rented",
                    "3" => "Business",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($request['property_type_c'], $property_type_array) ){
                    $property_type_name = $property_type_array[$request['property_type_c']];
                    $property_type_id = $request['property_type_c'];
                }

//                if(!empty($request['utility_provider'])){
//                    $utility_providerMsg = DB::table('lead_current_utility_provider')->where('lead_current_utility_provider_id', $request['utility_provider'])->first(['lead_current_utility_provider_name']);
//                    $utility_provider_id = $request['utility_provider'];
//                    $utility_provider_name = $utility_providerMsg->lead_current_utility_provider_name;
//                } else {
//                    $utility_provider_id = '752';
//                    $utility_provider_name = 'Other';
//                }

                $utility_provider_id = (!empty($request['utility_provider']) ? $request['utility_provider'] : "Other");
                $utility_provider_name = (!empty($request['utility_provider']) ? $request['utility_provider'] : "Other");

                $dataMassageForBuyers = array(
                    'one' => 'Type of the project? ' . $power_solution_name,
                    'two' => "Property sun exposure " . $roof_shade_name,
                    'three' => 'What is the current utility provider for the customer? ' . $utility_provider_name,
                    'four' => 'What is the average monthly electricity bill for the customer? ' . $monthly_electric_bill_name,
                    'five' => 'Property Type: ' . $property_type_name
                );

                $LeaddataIDs = array(
                    'power_solution' => $power_solution_id,
                    'roof_shade' => $roof_shade_id,
                    'utility_provider' => $utility_provider_id,
                    'monthly_electric_bill' => $monthly_electric_bill_id,
                    'property_type' => $property_type_id
                );

                $Leaddatadetails = array(
                    'power_solution' => $power_solution_name,
                    'roof_shade' => $roof_shade_name,
                    'utility_provider' => $utility_provider_name,
                    'monthly_electric_bill' => $monthly_electric_bill_name,
                    'property_type' => $property_type_name,
                );

                $dataMassageForDB = "[$power_solution_name, $roof_shade_name, $utility_provider_name, $monthly_electric_bill_name, $property_type_name]";
                break;
            case 3:
                //HomeSecurity
                $installation_preferences_array = array(
                    "1" => "Professional installation",
                    "2" => "Self installation",
                    "3" => "No preference",
                );

                $installation_preferences_id = $installation_preferences_name = '';
                if( array_key_exists($request['installation_preferences'], $installation_preferences_array) ){
                    $installation_preferences_name = $installation_preferences_array[$request['installation_preferences']];
                    $installation_preferences_id = $request['installation_preferences'];
                }

                if ($request['lead_have_item_before_it'] == '1') {
                    $lead_have_item_before_it_id = $request['lead_have_item_before_it'];
                    $lead_have_item_before_it = 'Yes';
                } else {
                    $lead_have_item_before_it_id = $request['lead_have_item_before_it'];
                    $lead_have_item_before_it = 'No';
                }

                $property_type_array = array(
                    "1"=> "Owned" ,
                    "2"=> "Rented",
                    "3"=>"Business",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($request['property_type_c'], $property_type_array) ){
                    $property_type_name = $property_type_array[$request['property_type_c']];
                    $property_type_id = $request['property_type_c'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Installation Preferences: ' . $installation_preferences_name,
                    'two' => 'Does the customer have An Existing Alarm And/ Or Monitoring System? ' . $lead_have_item_before_it,
                    'three' => 'Property Type: ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name,
                );

                $LeaddataIDs = array(
                    'Installation_Preferences' => $installation_preferences_id,
                    'lead_have_item_before_it' => $lead_have_item_before_it_id,
                    'start_time' => $lead_priority_id,
                    'property_type' => $property_type_id
                );

                $Leaddatadetails = array(
                    'Installation_Preferences' => $installation_preferences_name,
                    'lead_have_item_before_it' => $lead_have_item_before_it,
                    'start_time' => $lead_priority_name,
                    'property_type' => $property_type_name,
                );

                $dataMassageForDB = "[$installation_preferences_name, $lead_have_item_before_it, $property_type_name, $lead_priority_name]";
                break;
            case 4:
                //Flooring
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $request['ownership'];
                }

                $type_of_flooring_array = array(
                    "1" => "Vinyl Linoleum Flooring",
                    "2" => "Tile Flooring",
                    "3" => "Hardwood Flooring",
                    "4" => "Laminate Flooring",
                    "5" => "Carpet",
                );

                $type_of_flooring_id = $type_of_flooring_name = '';
                if( array_key_exists($request['type_of_flooring'], $type_of_flooring_array) ){
                    $type_of_flooring_name = $type_of_flooring_array[$request['type_of_flooring']];
                    $type_of_flooring_id = $request['type_of_flooring'];
                }

                $nature_flooring_project_array = array(
                    "1" => "Install New Flooring",
                    "2" => "Refinish Existing Flooring",
                    "3" => "Repair Existing Flooring",
                );

                $nature_flooring_project_id = $nature_flooring_project_name = '';
                if( array_key_exists($request['nature_flooring_project'], $nature_flooring_project_array) ){
                    $nature_flooring_project_name = $nature_flooring_project_array[$request['nature_flooring_project']];
                    $nature_flooring_project_id = $request['nature_flooring_project'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of flooring? ' . $type_of_flooring_name,
                    'two' => 'Type of the project? ' . $nature_flooring_project_name,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn,
                );

                $LeaddataIDs = array(
                    'flooring_type' => $type_of_flooring_id,
                    'project_nature' => $nature_flooring_project_id,
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id
                );

                $Leaddatadetails = array(
                    'flooring_type' => $type_of_flooring_name,
                    'project_nature' => $nature_flooring_project_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_flooring_name, $nature_flooring_project_name, $lead_priority_name, $homeOwn]";
                break;
            case 5:
                //Walk In tubs
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $request['ownership'];
                }

                $reason_array = array(
                    "1" => "Safety",
                    "2" => "Therapeutic",
                    "3" => "Others",
                );

                $reason_id = $reason_name = '';
                if( array_key_exists($request['walk_in_tub'], $reason_array) ){
                    $reason_name = $reason_array[$request['walk_in_tub']];
                    $reason_id = $request['walk_in_tub'];
                }

                $desired_feature_array_stitac = array(
                    "1" => "Whirlpool" ,
                    "2" => "Quick Water Release",
                    "3" => "Soaking",
                    "4" => "Air/Hydro Massager",
                );

                $desired_featuersMsg = '';
                $desired_featuers_array = array();
                if (!empty($request['desired_featuers'])) {
                    if (is_array($request['desired_featuers'])) {
                        $desired_featuers_array = $request['desired_featuers'];
                    } else {
                        $desired_featuers_array = json_decode($request['desired_featuers'], true);
                    }

                    foreach ($desired_featuers_array as $val) {
                        $desired_featuers_name = "";
                        if( array_key_exists($val, $desired_feature_array_stitac) ){
                            $desired_featuers_name = $desired_feature_array_stitac[$val];
                        }

                        $desired_featuersMsg .= $desired_featuers_name . ', ';
                    }
                    $desired_featuersMsg = rtrim($desired_featuersMsg, ', ');
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of Walk-In Tub? ' . $reason_name,
                    'two' => 'Desired Features? ' . $desired_featuersMsg,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn
                );

                $LeaddataIDs = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'reason' => $reason_id,
                    'features' => json_encode($desired_featuers_array)
                );

                $Leaddatadetails = array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'reason' => $reason_name,
                    'features' => $desired_featuersMsg

                );

                $dataMassageForDB = "[$reason_name, ($desired_featuersMsg), $lead_priority_name, $homeOwn]";
                break;
            case 6:
                //Roofing
                $type_of_roofing_array = array(
                    "1" => "Asphalt Roofing",
                    "2" => "Wood Shake/Composite Roofing",
                    "3" => "Metal Roofing",
                    "4" => "Natural Slate Roofing",
                    "5" => "Tile Roofing",
                );

                $type_of_roofing_id = $type_of_roofing_name = '';
                if( array_key_exists($request['type_of_roofing'], $type_of_roofing_array) ){
                    $type_of_roofing_name = $type_of_roofing_array[$request['type_of_roofing']];
                    $type_of_roofing_id = $request['type_of_roofing'];
                }

                $nature_roofing_project_array = array(
                    "1" => "Install roof on new construction",
                    "2" => "Completely replace roof",
                    "3" => "Repair existing roof",
                );

                $nature_of_roofing_id = $nature_of_roofing_name = '';
                if( array_key_exists($request['nature_of_roofing'], $nature_roofing_project_array) ){
                    $nature_of_roofing_name = $nature_roofing_project_array[$request['nature_of_roofing']];
                    $nature_of_roofing_id = $request['nature_of_roofing'];
                }

                $property_type_roofing_array = array(
                    "1" => "Residential",
                    "2" => "Commercial",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($request['property_type_roofing'], $property_type_roofing_array) ){
                    $property_type_name = $property_type_roofing_array[$request['property_type_roofing']];
                    $property_type_id = $request['property_type_roofing'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of roofing? ' . $type_of_roofing_name,
                    'two' => 'Type of the project? ' . $nature_of_roofing_name,
                    'three' => 'Property Type ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'roof_type' => $type_of_roofing_id,
                    'project_nature' => $nature_of_roofing_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'roof_type' => $type_of_roofing_name,
                    'project_nature' => $nature_of_roofing_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_roofing_name, $nature_of_roofing_name, $property_type_name, $lead_priority_name]";
                break;
            case 7:
                //Home Siding
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $nature_siding_project_array = array(
                    "1" => "Siding for a new home",
                    "2" => "Siding for a new addition",
                    "3" => "Replace existing siding",
                    "4" => "Repair section(s) of siding",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['nature_of_siding'], $nature_siding_project_array) ){
                    $project_nature_name = $nature_siding_project_array[$request['nature_of_siding']];
                    $project_nature_id = $request['nature_of_siding'];
                }

                $project_nature_siding_id = 1;
                if ($project_nature_id == 3) {
                    $project_nature_siding_id = 2;
                } else if ($project_nature_id == 4) {
                    $project_nature_siding_id = 3;
                }

                $type_of_siding_array = array(
                    "1" => "Vinyl Siding",
                    "2" => "Brickface Siding",
                    "3" => "Composite wood Siding",
                    "4" => "Aluminum Siding",
                    "5" => "Stoneface Siding",
                    "6" => "Fiber Cement Siding",
                );

                $type_of_siding_id = $type_of_siding_name = '';
                if( array_key_exists($request['type_of_siding'], $type_of_siding_array) ){
                    $type_of_siding_name = $type_of_siding_array[$request['type_of_siding']];
                    $type_of_siding_id = $request['type_of_siding'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of siding? ' . $type_of_siding_name,
                    'two' => 'Type of the project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'type_of_siding' => $type_of_siding_id,
                    'project_nature' => $project_nature_siding_id,
                    'project_nature_siding' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'type_of_siding' => $type_of_siding_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_siding_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 8:
                //Kitchen
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $service_kitchen_array = array(
                    "1" => "Full Kitchen Remodeling",
                    "2" => "Cabinet Refacing",
                    "3" => "Cabinet Install",
                );

                $service_kitchen_id = $service_kitchen_name = '';
                if( array_key_exists($request['service_kitchen'], $service_kitchen_array) ){
                    $service_kitchen_name = $service_kitchen_array[$request['service_kitchen']];
                    $service_kitchen_id = $request['service_kitchen'];
                }

                if($request['removing_adding_walls'] == '1') {
                    $removing_adding_walls_id = $request['removing_adding_walls'];
                    $removing_adding_walls = 'Yes';
                } else {
                    $removing_adding_walls_id = $request['removing_adding_walls'];
                    $removing_adding_walls = 'No';
                }

                $dataMassageForBuyers = array(
                    'one' => 'Services required? ' . $service_kitchen_name,
                    'two' => 'Demolishing/building walls? ' . $removing_adding_walls,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $service_kitchen_id,
                    'demolishing_walls' => $removing_adding_walls_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id,
                );

                $Leaddatadetails = array(
                    'services' => $service_kitchen_name,
                    'demolishing_walls' => $removing_adding_walls,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$service_kitchen_name, $removing_adding_walls, $homeOwn, $lead_priority_name]";
                break;
            case 9:
                //BathRoom
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $bathroom_type_array = array(
                    "1" => "Full Remodel",
                    "2" => "Cabinets / Vanity",
                    "3" => "Countertops",
                    "4" => "Flooring",
                    "5" => "Shower / Bath",
                    "6" => "Sinks",
                    "7" => "Toilet",
                );

                $bathroom_type_id = $bathroom_type_name = '';
                if( array_key_exists($request['bathroom_type'], $bathroom_type_array) ){
                    $bathroom_type_name = $bathroom_type_array[$request['bathroom_type']];
                    $bathroom_type_id = $request['bathroom_type'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Services required? ' . $bathroom_type_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $bathroom_type_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $bathroom_type_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$bathroom_type_name, $homeOwn, $lead_priority_name]";
                break;
            case 10:
                //Stairs
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $stairs_type_array = array(
                    "1" => "Straight",
                    "2" => "Curved"
                );

                $stairs_type_id = $stairs_type_name = '';
                if( array_key_exists($request['stairs_type'], $stairs_type_array) ){
                    $stairs_type_name = $stairs_type_array[$request['stairs_type']];
                    $stairs_type_id = $request['stairs_type'];
                }

                $stairs_reason_array = array(
                    "1" => "Mobility",
                    "2" => "Safety",
                    "3" => "Other"
                );

                $stairs_reason_id = $stairs_reason_name = '';
                if( array_key_exists($request['stairs_reason'], $stairs_reason_array) ){
                    $stairs_reason_name = $stairs_reason_array[$request['stairs_reason']];
                    $stairs_reason_id = $request['stairs_reason'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of stairs? ' . $stairs_type_name,
                    'two' => 'The reason for installing the Stairlift ' . $stairs_reason_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'stairs_type' => $stairs_type_id,
                    'reason' => $stairs_reason_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'stairs_type' => $stairs_type_name,
                    'reason' => $stairs_reason_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$stairs_type_name, $stairs_reason_name, $homeOwn, $lead_priority_name]";
                break;
            case 11:
                //Furnace
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $furnace_type_array = array(
                    "1" => "Do Not Know",
                    "2" => "Electric",
                    "3" => "Natural Gas",
                    "4" => "Oil",
                    "5" => "Propane Gas"
                );

                $furnace_type_id = $furnace_type_name = '';
                if( array_key_exists($request['furnance_type'], $furnace_type_array) ){
                    $furnace_type_name = $furnace_type_array[$request['furnance_type']];
                    $furnace_type_id = $request['furnance_type'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of central heating system required? ' . $furnace_type_name
                );

                $LeaddataIDs = array(
                    'type_of_heating' => $furnace_type_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'type_of_heating' => $furnace_type_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name, $furnace_type_name]";
                break;
            case 12:
                //Boiler
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $furnace_type_array = array(
                    "1" => "Do Not Know",
                    "2" => "Electric",
                    "3" => "Natural Gas",
                    "4" => "Oil",
                    "5" => "Propane Gas"
                );

                $furnace_type_id = $furnace_type_name = '';
                if( array_key_exists($request['furnance_type'], $furnace_type_array) ){
                    $furnace_type_name = $furnace_type_array[$request['furnance_type']];
                    $furnace_type_id = $request['furnance_type'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                //Send Request
                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of central heating system required? ' . $furnace_type_name
                );

                $Leaddatadetails = array(
                    'type_of_heating' => $furnace_type_name,
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $LeaddataIDs = array(
                    'type_of_heating' => $furnace_type_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name, $furnace_type_name]";
                break;
            case 13:
                //Central A/C
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name]";
                break;
            case 14:
                //Cabinet
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $project_nature_array = array(
                    "1" => "Cabinet Install",
                    "2" => "Cabinet Refacing",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 15:
                //Plumbing
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $plumbing_service_array = array(
                    "1" => "Faucet/ Fixture Services",
                    "2" => "Pipe Services",
                    "3" => "Leak Repair",
                    "4" => "Remodeling/ Construction",
                    "5" => "Septic Systems",
                    "6" => "Drain/ Sewer Services",
                    "7" => "Shower Services",
                    "8" => "Sump Pump Services",
                    "9" => "Toilet Services",
                    "10" => "Water Heater Services",
                    "11" => "Water/ Fuel Tank",
                    "12" => "Water Treatment And Purification",
                    "13" => "Well Pump Services",
                    "14" => "Backflow Services",
                    "15" => "Bathroom Plumbing",
                    "16" => "Camera Line Inspection",
                    "17" => "Clogged Sink Repair",
                    "18" => "Disposal Services",
                    "19" => "Excavation",
                    "20" => "Grease Trap Services",
                    "21" => "Kitchen Plumbing",
                    "22" => "Storm Drain Cleaning",
                    "23" => "Trenchless Repairs",
                    "24" => "Water Damage Restoration",
                    "25" => "Water Jetting",
                    "26" => "Water Leak Services",
                    "27" => "Basement Plumbing",
                );

                $plumbing_service_id = $plumbing_service_name = '';
                if( array_key_exists($request['plumbing_service'], $plumbing_service_array) ){
                    $plumbing_service_name = $plumbing_service_array[$request['plumbing_service']];
                    $plumbing_service_id = $request['plumbing_service'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of services required? ' . $plumbing_service_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $plumbing_service_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $plumbing_service_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$plumbing_service_name, $homeOwn, $lead_priority_name]";
                break;
            case 16:
                //Bathtubs
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name]";
                break;
            case 17:
                //Sunrooms
                $sunroom_service_array = array(
                    "1" => "Build a new sunroom or patio enclosure",
                    "2" => "Enclose existing porch with roof, walls or windows",
                    "3" => "Screen in existing porch or patio",
                    "4" => "Add a metal awning or cover",
                    "5" => "Add a fabric awning or cover",
                    "6" => "Repair existing sunroom, porch or patio"
                );

                $sunroom_service_id = $sunroom_service_name = '';
                if( array_key_exists($request['sunroom_service'], $sunroom_service_array) ){
                    $sunroom_service_name = $sunroom_service_array[$request['sunroom_service']];
                    $sunroom_service_id = $request['sunroom_service'];
                }

                $property_type_roofing_array = array(
                    "1" => "Residential",
                    "2" => "Commercial",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($request['property_type_roofing'], $property_type_roofing_array) ){
                    $property_type_name = $property_type_roofing_array[$request['property_type_roofing']];
                    $property_type_id = $request['property_type_roofing'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of project/services required? ' . $sunroom_service_name,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the property? ' . $property_type_name
                );

                $LeaddataIDs = array(
                    'services' => $sunroom_service_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $sunroom_service_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$sunroom_service_name, $lead_priority_name, $property_type_name]";
                break;
            case 18:
                //Handyman
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $amount_work_array = array(
                    "1" => "A variety of projects",
                    "2" => "A single project",
                );

                $amount_work_id = $amount_work_name = '';
                if( array_key_exists($request['handyman_ammount'], $amount_work_array) ){
                    $amount_work_name = $amount_work_array[$request['handyman_ammount']];
                    $amount_work_id = $request['handyman_ammount'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of project? ' . $amount_work_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $amount_work_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $amount_work_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$amount_work_name, $homeOwn, $lead_priority_name]";
                break;
            case 19:
                //Countertops
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $countertops_service_array = array(
                    "1" => "Granite",
                    "2" => "Solid Surface (e.g corian)",
                    "3" => "Marble",
                    "4" => "Wood (e.g butcher block)",
                    "5" => "Stainless Steel",
                    "6" => "Laminate",
                    "7" => "Concrete",
                    "8" => "Other Solid Stone (e.g Quartz)",
                );

                $countertops_service_id = $countertops_service_name = '';
                if( array_key_exists($request['countertops_service'], $countertops_service_array) ){
                    $countertops_service_name = $countertops_service_array[$request['countertops_service']];
                    $countertops_service_id = $request['countertops_service'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'CounterTop material: ' . $countertops_service_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'service' => $countertops_service_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'service' => $countertops_service_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$countertops_service_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 20:
                //Doors
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $project_type_array = array(
                    "1" => "Exterior",
                    "2" => "Interior"
                );

                $project_type_id = $project_type_name = '';
                if( array_key_exists($request['door_typeproject'], $project_type_array) ){
                    $project_type_name = $project_type_array[$request['door_typeproject']];
                    $project_type_id = $request['door_typeproject'];
                }

                $number_of_doors_array = array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4+",
                );

                $number_of_doors_id = $number_of_doors_name = '';
                if( array_key_exists($request['number_of_door'], $number_of_doors_array) ){
                    $number_of_doors_name = $number_of_doors_array[$request['number_of_door']];
                    $number_of_doors_id = $request['number_of_door'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Interior/Exterior? ' . $project_type_name,
                    'two' => 'Number of doors involved? ' . $number_of_doors_name,
                    'three' => 'Type of project? ' . $project_nature_name,
                    'four' => 'Owner of the Property? ' . $homeOwn,
                    'five' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'homeOwn' =>$homeOwn_id,
                    'start_time' => $lead_priority_id,
                    'door_type' => $project_type_id,
                    'number_of_door' => $number_of_doors_id,
                    'project_nature' => $project_nature_id,
                );

                $Leaddatadetails = array(
                    'door_type' => $project_type_name,
                    'number_of_door' => $number_of_doors_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$project_type_name, $number_of_doors_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 21:
                //Gutter
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($request['projectnature'], $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$request['projectnature']];
                    $project_nature_id = $request['projectnature'];
                }

                $gutter_material_array = array(
                    "1" => "Copper",
                    "2" => "Galvanized Steel",
                    "3" => "PVC",
                    "4" => "Seamless Aluminum",
                    "5" => "Wood",
                    "6" => "not sure"
                );

                $gutter_material_id = $gutter_material_name = '';
                if( array_key_exists($request['gutters_meterial'], $gutter_material_array) ){
                    $gutter_material_name = $gutter_material_array[$request['gutters_meterial']];
                    $gutter_material_id = $request['gutters_meterial'];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Gutter material: ' . $gutter_material_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'service' => $gutter_material_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'service' => $gutter_material_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$gutter_material_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 22:
                //Paving
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $paving_service_array = array(
                    "1" => "Asphalt Paving - Install",
                    "2" => "Asphalt Sealing",
                    "3" => "Gravel or Loose Fill Paving - Install, Spread or Scrape",
                    "4" => "Asphalt Paving - Repair or Patch",
                );

                $paving_service_id = $paving_service_name = '';
                if( array_key_exists($request['paving_service'], $paving_service_array) ){
                    $paving_service_name = $paving_service_array[$request['paving_service']];
                    $paving_service_id = $request['paving_service'];
                }

                $paving_best_describes_priject_array = array(
                    "1" => "New Layout",
                    "2" => "Restripe",
                );

                $paving_best_describes_priject_id = $paving_best_describes_priject_name = '';
                if( array_key_exists($request['paving_best_describes_priject'], $paving_best_describes_priject_array) ){
                    $paving_best_describes_priject_name = $paving_best_describes_priject_array[$request['paving_best_describes_priject']];
                    $paving_best_describes_priject_id = $request['paving_best_describes_priject'];
                }

                switch ($paving_service_id) {
                    case "1":
                        $paving_asphalt_type = array(
                            "1" => "Driveway",
                            "2" => "Road",
                            "3" => "Walkway or sidewalk",
                            "4" => "Patio",
                            "5" => "Sports court (tennis, basketball, etc.)",
                            "6" => "Parking Lot",
                        );

                        $paving_asphalt_type_id = $paving_asphalt_type_name = '';
                        if( array_key_exists($request['paving_asphalt_type'], $paving_asphalt_type) ){
                            $paving_asphalt_type_name = $paving_asphalt_type[$request['paving_asphalt_type']];
                            $paving_asphalt_type_id = $request['paving_asphalt_type'];
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'The area needing asphalt ' . $paving_asphalt_type_name,
                            'three' => 'Type of project ' . $paving_best_describes_priject_name,
                            'four' => 'Owner of the Property? ' . $homeOwn,
                            'five' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'asphalt_needing' => $paving_asphalt_type_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'asphalt_needing' => $paving_asphalt_type_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_asphalt_type_name, $paving_best_describes_priject_name, $homeOwn, $lead_priority_name]";
                        break;
                    case 3:
                        $paving_loose_fill_type_array = array(
                            "1" => "Driveway",
                            "2" => "Road",
                            "3" => "Walkway or sidewalk",
                            "4" => "Patio",
                            "5" => "Sports court (tennis, basketball, etc.)",
                            "6" => "Parking Lot",
                        );

                        $paving_loose_fill_type_id = $paving_loose_fill_type_name = '';
                        if( array_key_exists($request['paving_loose_fill_type'], $paving_loose_fill_type_array) ){
                            $paving_loose_fill_type_name = $paving_loose_fill_type_array[$request['paving_loose_fill_type']];
                            $paving_loose_fill_type_id = $request['paving_loose_fill_type'];
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'Material of loose fill required ' . $paving_loose_fill_type_name,
                            'three' => 'Type of project ' . $paving_best_describes_priject_name,
                            'four' => 'Owner of the Property? ' . $homeOwn,
                            'five' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'material_loose' => $paving_loose_fill_type_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'material_loose' => $paving_loose_fill_type_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_loose_fill_type_name, $paving_best_describes_priject_name, $homeOwn, $lead_priority_name]";
                        break;
                    default:
                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'Type of project ' . $paving_best_describes_priject_name,
                            'three' => 'Owner of the Property? ' . $homeOwn,
                            'four' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_best_describes_priject_name, $homeOwn, $lead_priority_name]";
                }
                break;
            case 23:
                //Painting
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $painting_service_array = array(
                    "1" => "Exterior Home or Structure - Paint or Stain",
                    "2" => "Interior Home or Surfaces - Paint or Stain",
                    "3" => "Painting or Staining - Small Projects",
                    "4" => "Metal Roofing - Paint",
                    "5" => "Specialty Painting - Textures",
                );

                $painting_service_id = $painting_service_name = '';
                if( array_key_exists($request['painting_service'], $painting_service_array) ){
                    $painting_service_name = $painting_service_array[$request['painting_service']];
                    $painting_service_id = $request['painting_service'];
                }

                switch ($painting_service_id) {
                    case "1":
                        $painting1_typeof_project_array = array(
                            "1" => "New Construction",
                            "2" => "Repaint",
                            "3" => "Restain",
                        );

                        $painting1_typeof_project_id = $painting1_typeof_project_name = '';
                        if( array_key_exists($request['painting1_typeof_project'], $painting1_typeof_project_array) ){
                            $painting1_typeof_project_name = $painting1_typeof_project_array[$request['painting1_typeof_project']];
                            $painting1_typeof_project_id = $request['painting1_typeof_project'];
                        }

                        $painting1_stories_array = array(
                            "1" => "One Story",
                            "2" => "Two Stories",
                            "3" => "Three Stories or more",
                        );

                        $painting1_stories_id = $painting1_stories_name = '';
                        if( array_key_exists($request['painting1_stories'], $painting1_stories_array) ){
                            $painting1_stories_name = $painting1_stories_array[$request['painting1_stories']];
                            $painting1_stories_id = $request['painting1_stories'];
                        }

                        $painting1_kindsof_surfaces_array = array(
                            "1" => "New layout",
                            "2" => "Siding",
                            "3" => "Trim",
                            "4" => "Doors",
                            "5" => "Stucco",
                            "6" => "Shutters",
                            "7" => "Fence",
                            "8" => "Masonry (brick/stone)",
                            "9" => "Other",
                        );

                        $painting1_kindsof_surfaces_id = $painting1_kindsof_surfaces_name = '';
                        if( array_key_exists($request['painting1_kindsof_surfaces'], $painting1_kindsof_surfaces_array) ){
                            $painting1_kindsof_surfaces_name = $painting1_kindsof_surfaces_array[$request['painting1_kindsof_surfaces']];
                            $painting1_kindsof_surfaces_id = $request['painting1_kindsof_surfaces'];
                        }

                        if ($request['interior_historical'] == 1) {
                            $interior_historical_name = 'Yes';
                            $interior_historical_id = $request['interior_historical'];
                        } else {
                            $interior_historical_name = 'No';
                            $interior_historical_id = $request['interior_historical'];
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Type of project ' . $painting1_typeof_project_name,
                            'five' => 'Number of stories of the property ' . $painting1_stories_name,
                            'six' => 'What kinds of surfaces need to be painted and/or stained? ' . $painting1_kindsof_surfaces_name,
                            'seven' => 'Is the location a historical structure? ' . $interior_historical_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'project_type' => $painting1_typeof_project_id,
                            'historical_structure' => $interior_historical_id,
                            'stories_number' => $painting1_stories_id,
                            'surfaces_kinds' => $painting1_kindsof_surfaces_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'project_type' => $painting1_typeof_project_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'stories_number' => $painting1_stories_name,
                            'surfaces_kinds' => $painting1_kindsof_surfaces_name,
                            'historical_structure' => $interior_historical_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting1_typeof_project_name, $painting1_stories_name, $painting1_kindsof_surfaces_name, $interior_historical_name]";
                        break;
                    case 2:
                        $painting2_rooms_number_array = array(
                            "1" => "1-2",
                            "2" => "3-4",
                            "3" => "5-6",
                            "4" => "7-8",
                            "5" => "9 or more",
                        );

                        $painting2_rooms_number_id = $painting2_rooms_number_name = '';
                        if( array_key_exists($request['painting2_rooms_number'], $painting2_rooms_number_array) ){
                            $painting2_rooms_number_name = $painting2_rooms_number_array[$request['painting2_rooms_number']];
                            $painting2_rooms_number_id = $request['painting2_rooms_number'];
                        }

                        $painting2_typeof_paint_array = array(
                            "1" => "Walls",
                            "2" => "Walls And Ceilings",
                            "3" => "Ceilings",
                            "4" => "Others",
                        );

                        $painting2_typeof_paint_id = $painting2_typeof_paint_name = '';
                        if( array_key_exists($request['painting2_typeof_paint'], $painting2_typeof_paint_array) ){
                            $painting2_typeof_paint_name = $painting2_typeof_paint_array[$request['painting2_typeof_paint']];
                            $painting2_typeof_paint_id = $request['painting2_typeof_paint'];
                        }

                        if ($request['interior_historical'] == 1) {
                            $interior_historical_name = 'Yes';
                            $interior_historical_id = $request['interior_historical'];
                        } else {
                            $interior_historical_name = 'No';
                            $interior_historical_id = $request['interior_historical'];
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Number of rooms need to be painted ' . $painting2_rooms_number_name,
                            'six' => 'What needs to be painted ' . $painting2_typeof_paint_name,

                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $interior_historical_id,
                            'rooms_number' => $painting2_rooms_number_id,
                            'painted_needs' => $painting2_typeof_paint_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id,

                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'historical_structure' => $interior_historical_name,
                            'rooms_number' => $painting2_rooms_number_name,
                            'painted_needs' => $painting2_typeof_paint_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,

                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting2_rooms_number_name, $painting2_typeof_paint_name, $interior_historical_name]";
                        break;
                    case 3:
                        if ($request['interior_historical'] == 1) {
                            $interior_historical_name = 'Yes';
                            $interior_historical_id = $request['interior_historical'];
                        } else {
                            $interior_historical_name = 'No';
                            $interior_historical_id = $request['interior_historical'];
                        }

                        $painting3_each_feature_array_static = array(
                            "1" => "Exterior Door(s)",
                            "2" => "Exterior Siding",
                            "3" => "Exterior Wood Trim",
                            "4" => "Fencing / Gates",
                            "5" => "Interior Door(s)",
                            "6" => "Interior Walls",
                            "7" => "Interior Wood Trim",
                            "8" => "Ceiling",
                            "9" => "Cabinetry",
                            "10" => "Fireplace",
                            "11" => "Paneling",
                            "12" => "Others",
                        );

                        $painting3_each_feature = '';
                        $painting3_each_feature_array = array();
                        if (!empty($request['painting3_each_feature'])) {
                            if (is_array($request['painting3_each_feature'])) {
                                $painting3_each_feature_array = $request['painting3_each_feature'];
                            } else {
                                $painting3_each_feature_array = json_decode($request['painting3_each_feature'], true);
                            }

                            foreach ($painting3_each_feature_array as $key => $val) {
                                $painting3_each_feature_name = '';
                                if( array_key_exists($val, $painting3_each_feature_array_static) ){
                                    $painting3_each_feature_name = $painting3_each_feature_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting3_each_feature .= ', ';
                                }

                                $painting3_each_feature .= $painting3_each_feature_name;
                            }
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Areas need to be painted/stained ' . $painting3_each_feature,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $interior_historical_id,
                            'painted_feature' => $painting3_each_feature_array,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'painted_feature' => $painting3_each_feature,
                            'historical_structure' => $interior_historical_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $interior_historical_name, ($painting3_each_feature)]";
                        break;
                    case 4:
                        if ($request['interior_historical'] == 1) {
                            $interior_historical_name = 'Yes';
                            $interior_historical_id = $request['interior_historical'];
                        } else {
                            $interior_historical_name = 'No';
                            $interior_historical_id = $request['interior_historical'];
                        }

                        $painting1_stories_array = array(
                            "1" => "One Story",
                            "2" => "Two Stories",
                            "3" => "Three Stories or more",
                        );

                        $painting1_stories_id = $painting1_stories_name = '';
                        if( array_key_exists($request['painting1_stories'], $painting1_stories_array) ){
                            $painting1_stories_name = $painting1_stories_array[$request['painting1_stories']];
                            $painting1_stories_id = $request['painting1_stories'];
                        }

                        $painting4_existing_roof_array_static = array(
                            "1" => "Peeling or Blistering",
                            "2" => "Bleeding",
                            "3" => "Nail Stains",
                            "4" => "Mildew",
                            "5" => "Chalking",
                            "6" => "No Known Problems",
                            "7" => "Fair Condition",
                            "8" => "Never Been Painted Before",
                            "9" => "Others",
                            "10" => "Don't Know",
                        );

                        $painting4_existing_roof = '';
                        $painting4_existing_roof_array = array();
                        if (!empty($request['painting4_existing_roof'])) {
                            if (is_array($request['painting4_existing_roof'])) {
                                $painting4_existing_roof_array = $request['painting4_existing_roof'];
                            } else {
                                $painting4_existing_roof_array = json_decode($request['painting4_existing_roof'], true);
                            }

                            foreach ($painting4_existing_roof_array as $key => $val) {
                                $painting4_existing_roof_name = '';
                                if( array_key_exists($val, $painting4_existing_roof_array_static) ){
                                    $painting4_existing_roof_name = $painting4_existing_roof_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting4_existing_roof .= ', ';
                                }

                                $painting4_existing_roof .= $painting4_existing_roof_name;
                            }
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Number of stories of the property ' . $painting1_stories_name,
                            'six' => 'The condition of the existing roof ' . $painting4_existing_roof
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $interior_historical_id,
                            'stories_number' => $painting1_stories_id,
                            'existing_roof' => $painting4_existing_roof_array,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_name
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'stories_number' => $painting1_stories_name,
                            'historical_structure' => $interior_historical_name,
                            'existing_roof' => $painting4_existing_roof,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $interior_historical_name, $painting1_stories_name, ($painting4_existing_roof)]";
                        break;
                    case 5:
                        $painting5_kindof_texturing_array_static = array(
                            "1" => "Apply Texture To Unfinished Drywall for Paint",
                            "2" => "Match New Drywall To Exisiting Walls/Ceilings",
                            "3" => "Repair / Patch Drywall",
                            "4" => "Prepare For Wallpaper / Special Finish",
                            "5" => "Remove Popcorn Acoustic Ceiling Spray",
                            "6" => "Create Faux Effects",
                            "7" => "Paint Also Needed",
                            "8" => "Other",
                        );

                        $painting5_kindof_texturing = '';
                        $painting5_kindof_texturing_array = array();
                        if (!empty($request['painting5_kindof_texturing'])) {
                            if (is_array($request['painting5_kindof_texturing'])) {
                                $painting5_kindof_texturing_array = $request['painting5_kindof_texturing'];
                            } else {
                                $painting5_kindof_texturing_array = json_decode($request['painting5_kindof_texturing'], true);
                            }

                            foreach ($painting5_kindof_texturing_array as $key => $val) {
                                $painting5_kindof_texturing_name = '';
                                if( array_key_exists($val, $painting5_kindof_texturing_array_static) ){
                                    $painting5_kindof_texturing_name = $painting5_kindof_texturing_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting5_kindof_texturing .= ', ';
                                }

                                $painting5_kindof_texturing .= $painting5_kindof_texturing_name;
                            }
                        }

                        $painting5_surfaces_textured_array = array(
                            "1" => "Walls",
                            "2" => "Ceilings",
                            "3" => "Others",
                        );

                        $painting5_surfaces_textured_id = $painting5_surfaces_textured_name = '';
                        if( array_key_exists($request['painting5_surfaces_textured'], $painting5_surfaces_textured_array) ){
                            $painting5_surfaces_textured_name = $painting5_surfaces_textured_array[$request['painting5_surfaces_textured']];
                            $painting5_surfaces_textured_id = $request['painting5_surfaces_textured'];
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'What kind of texturing needed? ' . $painting5_kindof_texturing,
                            'five' => 'What surfaces need to be textured? ' . $painting5_surfaces_textured_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'surfaces_kinds' => $painting5_kindof_texturing_array,
                            'surfaces_textured' => $painting5_surfaces_textured_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'surfaces_kinds' => $painting5_kindof_texturing,
                            'surfaces_textured' => $painting5_surfaces_textured_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting5_surfaces_textured_name, ($painting5_kindof_texturing)]";
                        break;
                }
                break;
            case 24:
                //Auto Insurance
                if ($request['ownership'] == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $request['ownership'];
                } else if ($request['ownership'] == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $request['ownership'];
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $request['ownership'];
                }

                $VehicleYear = $request['VehicleYear'];
                $VehicleMake = $request['VehicleMake'];
                $car_model = $request['car_model'];
                $more_than_one_vehicle = $request['more_than_one_vehicle'];
                $driversNum = $request['driversNum'];
                $birthday = $request['birthday'];
                $genders = $request['genders'];
                $married = $request['married'];
                $license = $request['license'];
                $InsuranceCarrier = $request['InsuranceCarrier'];
                $driver_experience = $request['driver_experience'];
                $number_of_tickets = $request['number_of_tickets'];
                $DUI_charges = $request['DUI_charges'];
                $SR_22_need = $request['SR_22_need'];
                $submodel = $request['submodel'];
                $coverage_type = $request['coverage_type'];
                $license_status = $request['license_status'];
                $license_state = $request['license_state'];
                $ticket_date = $request['ticket_date'];
                $violation_date = $request['violation_date'];
                $accident_date = $request['accident_date'];
                $claim_date = $request['claim_date'];
                $expiration_date = $request['expiration_date'];

                $dataMassageForBuyers = array(
                    'one' => 'Vehicle Make Year: ' .  $VehicleYear,
                    'two' => 'Vehicle Brand: ' . $VehicleMake,
                    'three' => 'Car Model: ' . $car_model,
                    'four' => 'Is there more than one vehicle owned by the driver?' . $more_than_one_vehicle,
                    'five' => 'Is there more than one driver in the household?' . $driversNum,
                    'six' => 'Birthdate of the driver: ' . $birthday,
                    'seven' => "Driver's Gender: " . $genders,
                    'eight' => 'Is the driver married?' . $married,
                    'nine' => 'Owner of a valid driving license: ' . $license,
                    'ten' => 'Current insurance company: ' . $InsuranceCarrier,
                    'eleven' => 'Is the driver experienced? ' . $driver_experience,
                    'twelve' => 'Number of tickets or accidents: ' .$number_of_tickets,
                    'thirteen' => 'Are there any DUI charges?' . $DUI_charges,
                    'fourteen' => 'Is there a need for SR-22? ' . $SR_22_need,
                    'fifteen' => 'Are you a home owner? ' . $homeOwn,
                    'sixteen' => 'Car Submodel: ' . $submodel,
                    'seventeen' => 'Insurance Coverage Type: ' . $coverage_type,
                    'eighteen' => 'License Status: ' . $license_status,
                    'nineteen' => 'License State: ' . $license_state,
                    'twenty' => 'Ticket Date: ' . $ticket_date,
                    'twentyone' => 'Violation Date: ' . $violation_date,
                    'twentytwo' => 'Accident Date: ' . $accident_date,
                    'twentythree' => 'Insurance Claim Date: ' . $claim_date,
                    'twentyfour' => 'Insurance Expiration Date: ' . $expiration_date
                );

                $LeaddataIDs = array(
                    'VehicleYear' => $VehicleYear,
                    'VehicleMake' => $VehicleMake,
                    'car_model' => $car_model,
                    'more_than_one_vehicle' => $more_than_one_vehicle,
                    'driversNum' => $driversNum,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'married' => $married,
                    'license' => $license,
                    'InsuranceCarrier' => $InsuranceCarrier,
                    'driver_experience' => $driver_experience,
                    'number_of_tickets' => $number_of_tickets,
                    'DUI_charges' => $DUI_charges,
                    'SR_22_need' => $SR_22_need,
                    'submodel' => $submodel,
                    'coverage_type' => $coverage_type,
                    'license_status' => $license_status,
                    'license_state' => $license_state,
                    'ticket_date' => $ticket_date,
                    'violation_date' => $violation_date,
                    'accident_date' => $accident_date,
                    'claim_date' => $claim_date,
                    'expiration_date' => $expiration_date,
                    'homeOwn' => $request['ownership']
                );

                $Leaddatadetails = array(
                    'VehicleYear' => $VehicleYear,
                    'VehicleMake' => $VehicleMake,
                    'car_model' => $car_model,
                    'more_than_one_vehicle' => $more_than_one_vehicle,
                    'driversNum' => $driversNum,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'married' => $married,
                    'license' => $license,
                    'InsuranceCarrier' => $InsuranceCarrier,
                    'driver_experience' => $driver_experience,
                    'number_of_tickets' => $number_of_tickets,
                    'DUI_charges' => $DUI_charges,
                    'SR_22_need' => $SR_22_need,
                    'submodel' => $submodel,
                    'coverage_type' => $coverage_type,
                    'license_status' => $license_status,
                    'license_state' => $license_state,
                    'ticket_date' => $ticket_date,
                    'violation_date' => $violation_date,
                    'accident_date' => $accident_date,
                    'claim_date' => $claim_date,
                    'expiration_date' => $expiration_date,
                    'homeOwn' => $homeOwn
                );

                $dataMassageForDB = "[$submodel, $coverage_type, $license_status, $license_state, $ticket_date, $violation_date, $accident_date, $claim_date, $expiration_date, $homeOwn, $VehicleYear, $VehicleMake, $car_model, $more_than_one_vehicle, $driversNum, $birthday, $genders, $married, $license, $InsuranceCarrier, $driver_experience, $number_of_tickets, $DUI_charges, $SR_22_need]";
                break;
            case 25:
                //home insurance
                $house_type = $request['house_type'];
                $Year_Built = $request['Year_Built'];
                $primary_residence = $request['primary_residence'];
                $new_purchase = $request['new_purchase'];
                $previous_insurance_within_last30= $request['previous_insurance_within_last30'];
                $previous_insurance_claims_last3yrs = $request['previous_insurance_claims_last3yrs'];
                $married = $request['married'];
                $credit_rating = $request['credit_rating'];
                $birthday = $request['birthday'];

                $dataMassageForBuyers = array(
                    'one' => 'What kind of home Do You Live In: ' .  $house_type,
                    'two' => 'Year built: ' . $Year_Built,
                    'three' => 'Is this your primary residence: ' . $primary_residence,
                    'four' => 'Is this a new purchase' . $new_purchase,
                    'five' => 'Have you had insurance within the last 30 days' . $previous_insurance_within_last30,
                    'six' => 'Have you made any home insurance claims in the past 3 years: ' . $previous_insurance_claims_last3yrs,
                    'seven' => "Are you married: " . $married,
                    'eight' => 'Credit rating?' . $credit_rating,
                    'nine' => 'When is your birthday: ' . $birthday,
                );

                $LeaddataIDs = array(
                    'house_type' => $house_type,
                    'Year_Built' => $Year_Built,
                    'primary_residence' => $primary_residence,
                    'new_purchase' => $new_purchase,
                    'previous_insurance_within_last30' => $previous_insurance_within_last30,
                    'previous_insurance_claims_last3yrs' => $previous_insurance_claims_last3yrs,
                    'married' => $married,
                    'credit_rating' => $credit_rating,
                    'birthday' => $birthday,
                );

                $Leaddatadetails = array(
                    'house_type' => $house_type,
                    'Year_Built' => $Year_Built,
                    'primary_residence' => $primary_residence,
                    'new_purchase' => $new_purchase,
                    'previous_insurance_within_last30' => $previous_insurance_within_last30,
                    'previous_insurance_claims_last3yrs' => $previous_insurance_claims_last3yrs,
                    'married' => $married,
                    'credit_rating' => $credit_rating,
                    'birthday' => $birthday,
                );

                $dataMassageForDB = "[$house_type, $Year_Built, $primary_residence, $new_purchase, $previous_insurance_within_last30, $previous_insurance_claims_last3yrs, $married, $credit_rating, $birthday]";
                break;
            case 26:
            case 27:
                //Life Insurance & Disability insurance
                $Height = $request['Height'];
                $weight = $request['weight'];
                $birthday = $request['birthday'];
                $genders = $request['genders'];
                $amount_coverage = $request['amount_coverage'];
                $military_personnel_status = $request['military_personnel_status'];
                $military_status = $request['military_status'];
                $service_branch = $request['service_branch'];

                $dataMassageForBuyers = array(
                    'one' => 'Select Your Height? ' .  $Height,
                    'two' => 'Enter Your Weight? ' . $weight,
                    'three' => 'When is your birthday? ' . $birthday,
                    'four' => 'Whats your gender?' . $genders,
                    'five' => 'Amount of Coverage You are Considering ?' . $amount_coverage,
                    'six' => 'Are you active or retired military personnel? ' . $military_personnel_status,
                    'seven' => "Military status ? " . $military_status,
                    'eight' => 'Service branch ?' . $service_branch,
                );

                $LeaddataIDs = array(
                    'Height' => $Height,
                    'weight' => $weight,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'amount_coverage' => $amount_coverage,
                    'military_personnel_status' => $military_personnel_status,
                    'military_status' => $military_status,
                    'service_branch' => $service_branch
                );

                $Leaddatadetails = array(
                    'Height' => $Height,
                    'weight' => $weight,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'amount_coverage' => $amount_coverage,
                    'military_personnel_status' => $military_personnel_status,
                    'military_status' => $military_status,
                    'service_branch' => $service_branch
                );

                $dataMassageForDB = "[$Height, $weight, $birthday, $genders, $amount_coverage, $military_personnel_status, $military_status, $service_branch]";
                break;
            case 28:
                //Business insurance
                $CommercialCoverage = collect($request['CommercialCoverage'])->implode(',');
                $company_benefits_quote = $request['company_benefits_quote'];
                $business_start_date = $request['business_start_date'];
                $estimated_annual_payroll = $request['estimated_annual_payroll'];
                $number_of_employees = $request['number_of_employees'];
                $coverage_start_month = $request['coverage_start_month'];
                $business_name = $request['business_name'];

                $dataMassageForBuyers = array(
                    'one' => 'What coverage does your business need? ' .  $CommercialCoverage,
                    'two' => 'Would you also like to get quotes for your companies benefits? ' . $company_benefits_quote,
                    'three' => 'When did you start your business? ' . $business_start_date,
                    'four' => 'What is Your Estimated Annual Employee Payroll in the Next 12 Months?' . $estimated_annual_payroll,
                    'five' => 'Total # of Employees including Yourself ?' . $number_of_employees,
                    'six' => 'When would you like the coverage to begin? ' . $coverage_start_month,
                    'seven' => "Business Name ?" . $business_name,
                );

                $LeaddataIDs = array(
                    'CommercialCoverage' => $CommercialCoverage,
                    'company_benefits_quote' => $company_benefits_quote,
                    'business_start_date' => $business_start_date,
                    'estimated_annual_payroll' => $estimated_annual_payroll,
                    'number_of_employees' => $number_of_employees,
                    'coverage_start_month' => $coverage_start_month,
                    'business_name' => $business_name
                );

                $Leaddatadetails = array(
                    'CommercialCoverage' => $CommercialCoverage,
                    'company_benefits_quote' => $company_benefits_quote,
                    'business_start_date' => $business_start_date,
                    'estimated_annual_payroll' => $estimated_annual_payroll,
                    'number_of_employees' => $number_of_employees,
                    'coverage_start_month' => $coverage_start_month,
                    'business_name' => $business_name
                );

                $dataMassageForDB = "[$CommercialCoverage, $company_benefits_quote, $business_start_date, $estimated_annual_payroll, $number_of_employees, $coverage_start_month, $business_name]";
                break;
            case 29:
            case 30:
                //Health Insurance & long term insurance
                $gender = $request['genders'];
                $birthday = $request['birthday'];
                $pregnancy = $request['pregnancy'];
                $tobacco_usage = $request['tobacco_usage'];
                $health_conditions = $request['health_conditions'];
                $number_of_people_in_household = $request['number_of_people_in_household'];
                $addPeople = collect($request['addPeople'])->implode(',');
                $addPeople = str_replace(['[', ']', '"', '\\', "'"], '', $addPeople);
                $annual_income = $request['annual_income'];

                $dataMassageForBuyers = array(
                    'one' => 'Whats your gender?' .  $gender,
                    'two' => 'When is your birthday?' . $birthday,
                    'three' => 'Are you or your spouse pregnant right now, or adopting a child? ' . $pregnancy,
                    'four' => 'Do you use tobacco?' . $tobacco_usage,
                    'five' => 'Do you have any of these health conditions?' . $health_conditions,
                    'six' => 'How many people are in your household?' . $number_of_people_in_household,
                    'seven' => "Is your insurance just for you, or do you plan on covering others?" . $addPeople,
                    'eight' => 'Whats your annual household income?' . $annual_income,
                );

                $LeaddataIDs = array(
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'pregnancy' => $pregnancy,
                    'tobacco_usage' => $tobacco_usage,
                    'health_conditions' => $health_conditions,
                    'number_of_people_in_household' => $number_of_people_in_household,
                    'addPeople' => $addPeople,
                    'annual_income' => $annual_income,
                );

                $Leaddatadetails = array(
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'pregnancy' => $pregnancy,
                    'tobacco_usage' => $tobacco_usage,
                    'health_conditions' => $health_conditions,
                    'number_of_people_in_household' => $number_of_people_in_household,
                    'addPeople' => $addPeople,
                    'annual_income' => $annual_income,
                );

                $dataMassageForDB = "[$gender, $birthday, $pregnancy, $tobacco_usage, $health_conditions, $number_of_people_in_household,$addPeople, $annual_income]";
                break;
            case 31:
                //debt relief
                $debt_amount = $request['debt_amount'];
                $debt_type = json_encode($request['debt_type']);

                $dataMassageForBuyers = array(
                    'one' => 'Debt Amount? ' . $debt_amount,
                    'two' => 'Debt type? ' .  $debt_type,
                );

                $LeaddataIDs = array(
                    'debt_amount' => $debt_amount,
                    'debt_type' => $debt_type,
                );

                $Leaddatadetails = array(
                    'debt_amount' => $debt_amount,
                    'debt_type' => $debt_type,
                );

                $dataMassageForDB = "[$debt_amount, ($debt_type)]";
                break;
        }
        //==============================================================================================================

        return array(
            "dataMassageForBuyers" => $dataMassageForBuyers,
            "Leaddatadetails" => $Leaddatadetails,
            "LeaddataIDs" => $LeaddataIDs,
            "dataMassageForDB" => $dataMassageForDB
        );
    }

    //Post Push Leads
    public function check_questions_ids_push_array($leadcustomer_info){
        //==============================================================================================================
        //Get Questions Value and validated
        $dataMassageForBuyers = array();
        $Leaddatadetails = array();
        $LeaddataIDs = array();
        $dataMassageForDB = "";

        if( !empty($leadcustomer_info->lead_priority_id) ){
            $lead_priority_array = array(
                "1" => "Immediately",
                "2" => "Within 6 months",
                "3" => "Not Sure",
            );

            if( array_key_exists($leadcustomer_info->lead_priority_id, $lead_priority_array) ){
                $lead_priority_id = $leadcustomer_info->lead_priority_id;
                $lead_priority_name = $lead_priority_array[$leadcustomer_info->lead_priority_id];
            } else {
                $lead_priority_id = 3;
                $lead_priority_name = "Not Sure";
            }
        } else {
            $lead_priority_id = 3;
            $lead_priority_name = "Not Sure";
        }

        switch ($leadcustomer_info->lead_type_service_id){
            case 1:
                //Wendows
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                }

                $windows_number_array = array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3-5",
                    "4" => "6-9",
                    "5" => "10+",
                );

                $number_of_windows_id = $number_of_windows_name = '';
                if( array_key_exists($leadcustomer_info->lead_numberOfItem, $windows_number_array) ){
                    $number_of_windows_id = $leadcustomer_info->lead_numberOfItem;
                    $number_of_windows_name = $windows_number_array[$leadcustomer_info->lead_numberOfItem];
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'How many windows are involved? ' . $number_of_windows_name,
                    'four' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'number_of_window' => $number_of_windows_id,
                    'project_nature' => $project_nature_id
                );

                $Leaddatadetails = array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'number_of_window' => $number_of_windows_name,
                    'project_nature' => $project_nature_name,
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $number_of_windows_name, $project_nature_name]";
                break;
            case 2:
                //Solar
                $solar_solution_array = array(
                    "1" => "Solar Electricity for my Home",
                    "2" => "Solar Water Heating for my Home",
                    "3" => "Solar Electricity & Water Heating for my Home",
                    "4" => "Solar for my Business",
                );

                $power_solution_id = $power_solution_name = '';
                if( array_key_exists($leadcustomer_info->lead_solor_solution_list_id, $solar_solution_array) ){
                    $power_solution_name = $solar_solution_array[$leadcustomer_info->lead_solor_solution_list_id];
                    $power_solution_id = $leadcustomer_info->lead_solor_solution_list_id;
                }

                $solar_sun_array = array(
                    "1" => "Full Sun",
                    "2" => "Partial Sun",
                    "3" => "Mostly Shaded",
                    "4" => "Not Sure",
                );

                $roof_shade_id = $roof_shade_name = '';
                if( array_key_exists($leadcustomer_info->lead_solor_sun_expouser_list_id, $solar_sun_array) ){
                    $roof_shade_name = $solar_sun_array[$leadcustomer_info->lead_solor_sun_expouser_list_id];
                    $roof_shade_id = $leadcustomer_info->lead_solor_sun_expouser_list_id;
                }

                $avg_money_array = array(
                    "1" => "$51 - $100",
                    "2" => "$151 - $200",
                    "3" => "$201 - $300",
                    "4" => "$401 - $500",
                    "5" => "$500+",
                    "6" => "$0 - $50",
                    "7" => "$101 - $150",
                    "8" => "$301 - $400",
                );

                $monthly_electric_bill_id = $monthly_electric_bill_name = '';
                if( array_key_exists($leadcustomer_info->lead_avg_money_electicity_list_id, $avg_money_array) ){
                    $monthly_electric_bill_name = $avg_money_array[$leadcustomer_info->lead_avg_money_electicity_list_id];
                    $monthly_electric_bill_id = $leadcustomer_info->lead_avg_money_electicity_list_id;
                }

                $property_type_array = array(
                    "1" => "Owned",
                    "2" => "Rented",
                    "3" => "Business",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($leadcustomer_info->property_type_campaign_id, $property_type_array) ){
                    $property_type_name = $property_type_array[$leadcustomer_info->property_type_campaign_id];
                    $property_type_id = $leadcustomer_info->property_type_campaign_id;
                }

                if(!empty($leadcustomer_info->lead_current_utility_provider_id)){
//                    $utility_providerMsg = DB::table('lead_current_utility_provider')->where('lead_current_utility_provider_id', $leadcustomer_info->lead_current_utility_provider_id)->first(['lead_current_utility_provider_name']);
//                    $utility_provider_id = $leadcustomer_info->lead_current_utility_provider_id;
//                    $utility_provider_name = $utility_providerMsg->lead_current_utility_provider_name;

                    $utility_provider_id = $leadcustomer_info->lead_current_utility_provider_id;
                    $utility_provider_name = $leadcustomer_info->lead_current_utility_provider_id;
                } else {
//                    $utility_provider_id = '752';
                    $utility_provider_id = 'Other';
                    $utility_provider_name = 'Other';
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of the project? ' . $power_solution_name,
                    'two' => "Property sun exposure " . $roof_shade_name,
                    'three' => 'What is the current utility provider for the customer? ' . $utility_provider_name,
                    'four' => 'What is the average monthly electricity bill for the customer? ' . $monthly_electric_bill_name,
                    'five' => 'Property Type: ' . $property_type_name
                );

                $LeaddataIDs = array(
                    'power_solution' => $power_solution_id,
                    'roof_shade' => $roof_shade_id,
                    'utility_provider' => $utility_provider_id,
                    'monthly_electric_bill' => $monthly_electric_bill_id,
                    'property_type' => $property_type_id
                );

                $Leaddatadetails = array(
                    'power_solution' => $power_solution_name,
                    'roof_shade' => $roof_shade_name,
                    'utility_provider' => $utility_provider_name,
                    'monthly_electric_bill' => $monthly_electric_bill_name,
                    'property_type' => $property_type_name,
                );

                $dataMassageForDB = "[$power_solution_name, $roof_shade_name, $utility_provider_name, $monthly_electric_bill_name, $property_type_name]";
                break;
            case 3:
                //HomeSecurity
                $installation_preferences_array = array(
                    "1" => "Professional installation",
                    "2" => "Self installation",
                    "3" => "No preference",
                );

                $installation_preferences_id = $installation_preferences_name = '';
                if( array_key_exists($leadcustomer_info->lead_installation_preferences_id, $installation_preferences_array) ){
                    $installation_preferences_name = $installation_preferences_array[$leadcustomer_info->lead_installation_preferences_id];
                    $installation_preferences_id = $leadcustomer_info->lead_installation_preferences_id;
                }

                $lead_have_item_before_it = ($leadcustomer_info->lead_have_item_before_it == 1 ? "Yes" : "No");

                $property_type_array = array(
                    "1"=> "Owned" ,
                    "2"=> "Rented",
                    "3"=>"Business",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($leadcustomer_info->property_type_campaign_id, $property_type_array) ){
                    $property_type_name = $property_type_array[$leadcustomer_info->property_type_campaign_id];
                    $property_type_id = $leadcustomer_info->property_type_campaign_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Installation Preferences: ' . $installation_preferences_name,
                    'two' => 'Does the customer have An Existing Alarm And/ Or Monitoring System? ' . $lead_have_item_before_it,
                    'three' => 'Property Type: ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name,
                );

                $LeaddataIDs = array(
                    'Installation_Preferences' => $installation_preferences_id,
                    'lead_have_item_before_it' => $leadcustomer_info->lead_have_item_before_it,
                    'start_time' => $lead_priority_id,
                    'property_type' => $property_type_id
                );

                $Leaddatadetails = array(
                    'Installation_Preferences' => $installation_preferences_name,
                    'lead_have_item_before_it' => $lead_have_item_before_it,
                    'start_time' => $lead_priority_name,
                    'property_type' => $property_type_name,
                );

                $dataMassageForDB = "[$installation_preferences_name, $lead_have_item_before_it, $property_type_name, $lead_priority_name]";
                break;
            case 4:
                //Flooring
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                }

                $type_of_flooring_array = array(
                    "1" => "Vinyl Linoleum Flooring",
                    "2" => "Tile Flooring",
                    "3" => "Hardwood Flooring",
                    "4" => "Laminate Flooring",
                    "5" => "Carpet",
                );

                $type_of_flooring_id = $type_of_flooring_name = '';
                if( array_key_exists($leadcustomer_info->lead_type_of_flooring_id, $type_of_flooring_array) ){
                    $type_of_flooring_name = $type_of_flooring_array[$leadcustomer_info->lead_type_of_flooring_id];
                    $type_of_flooring_id = $leadcustomer_info->lead_type_of_flooring_id;
                }

                $nature_flooring_project_array = array(
                    "1" => "Install New Flooring",
                    "2" => "Refinish Existing Flooring",
                    "3" => "Repair Existing Flooring",
                );

                $nature_flooring_project_id = $nature_flooring_project_name = '';
                if( array_key_exists($leadcustomer_info->lead_nature_flooring_project_id, $nature_flooring_project_array) ){
                    $nature_flooring_project_name = $nature_flooring_project_array[$leadcustomer_info->lead_nature_flooring_project_id];
                    $nature_flooring_project_id = $leadcustomer_info->lead_nature_flooring_project_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of flooring? ' . $type_of_flooring_name,
                    'two' => 'Type of the project? ' . $nature_flooring_project_name,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn,
                );

                $LeaddataIDs = array(
                    'flooring_type' => $type_of_flooring_id,
                    'project_nature' => $nature_flooring_project_id,
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id
                );

                $Leaddatadetails = array(
                    'flooring_type' => $type_of_flooring_name,
                    'project_nature' => $nature_flooring_project_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_flooring_name, $nature_flooring_project_name, $lead_priority_name, $homeOwn]";
                break;
            case 5:
                //Walk In tubs
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id = $leadcustomer_info->lead_ownership;
                }

                $reason_array = array(
                    "1" => "Safety",
                    "2" => "Therapeutic",
                    "3" => "Others",
                );

                $reason_id = $reason_name = '';
                if( array_key_exists($leadcustomer_info->lead_walk_in_tub_id, $reason_array) ){
                    $reason_name = $reason_array[$leadcustomer_info->lead_walk_in_tub_id];
                    $reason_id = $leadcustomer_info->lead_walk_in_tub_id;
                }

                $desired_feature_array_stitac = array(
                    "1" => "Whirlpool" ,
                    "2" => "Quick Water Release",
                    "3" => "Soaking",
                    "4" => "Air/Hydro Massager",
                );

                $desired_featuersMsg = '';
                $desired_featuers_array = array();
                $lead_desired_featuers_id = $leadcustomer_info->lead_desired_featuers_id;
                if (!empty($lead_desired_featuers_id)) {
                    if (is_array($lead_desired_featuers_id)) {
                        $desired_featuers_array = $lead_desired_featuers_id;
                    } else {
                        $desired_featuers_array = json_decode($lead_desired_featuers_id, true);
                    }

                    foreach ($desired_featuers_array as $val) {
                        $desired_featuers_name = "";
                        if( array_key_exists($val, $desired_feature_array_stitac) ){
                            $desired_featuers_name = $desired_feature_array_stitac[$val];
                        }

                        $desired_featuersMsg .= $desired_featuers_name . ', ';
                    }
                    $desired_featuersMsg = rtrim($desired_featuersMsg, ', ');
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of Walk-In Tub? ' . $reason_name,
                    'two' => 'Desired Features? ' . $desired_featuersMsg,
                    'three' => 'The project is starting: ' . $lead_priority_name,
                    'four' => 'Owner of the Property? ' . $homeOwn
                );

                $LeaddataIDs = array(
                    'start_time' => $lead_priority_id,
                    'homeOwn' => $homeOwn_id,
                    'reason' => $reason_id,
                    'features' => json_encode($desired_featuers_array)
                );

                $Leaddatadetails = array(
                    'start_time' => $lead_priority_name,
                    'homeOwn' => $homeOwn,
                    'reason' => $reason_name,
                    'features' => $desired_featuersMsg

                );

                $dataMassageForDB = "[$reason_name, ($desired_featuersMsg), $lead_priority_name, $homeOwn]";
                break;
            case 6:
                //Roofing
                $type_of_roofing_array = array(
                    "1" => "Asphalt Roofing",
                    "2" => "Wood Shake/Composite Roofing",
                    "3" => "Metal Roofing",
                    "4" => "Natural Slate Roofing",
                    "5" => "Tile Roofing",
                );

                $type_of_roofing_id = $type_of_roofing_name = '';
                if( array_key_exists($leadcustomer_info->lead_type_of_roofing_id, $type_of_roofing_array) ){
                    $type_of_roofing_name = $type_of_roofing_array[$leadcustomer_info->lead_type_of_roofing_id];
                    $type_of_roofing_id = $leadcustomer_info->lead_type_of_roofing_id;
                }

                $nature_roofing_project_array = array(
                    "1" => "Install roof on new construction",
                    "2" => "Completely replace roof",
                    "3" => "Repair existing roof",
                );

                $nature_of_roofing_id = $nature_of_roofing_name = '';
                if( array_key_exists($leadcustomer_info->lead_nature_of_roofing_id, $nature_roofing_project_array) ){
                    $nature_of_roofing_name = $nature_roofing_project_array[$leadcustomer_info->lead_nature_of_roofing_id];
                    $nature_of_roofing_id = $leadcustomer_info->lead_nature_of_roofing_id;
                }

                $property_type_roofing_array = array(
                    "1" => "Residential",
                    "2" => "Commercial",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($leadcustomer_info->lead_property_type_roofing_id, $property_type_roofing_array) ){
                    $property_type_name = $property_type_roofing_array[$leadcustomer_info->lead_property_type_roofing_id];
                    $property_type_id = $leadcustomer_info->lead_property_type_roofing_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of roofing? ' . $type_of_roofing_name,
                    'two' => 'Type of the project? ' . $nature_of_roofing_name,
                    'three' => 'Property Type ' . $property_type_name,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'roof_type' => $type_of_roofing_id,
                    'project_nature' => $nature_of_roofing_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'roof_type' => $type_of_roofing_name,
                    'project_nature' => $nature_of_roofing_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_roofing_name, $nature_of_roofing_name, $property_type_name, $lead_priority_name]";
                break;
            case 7:
                //Home Siding
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $nature_siding_project_array = array(
                    "1" => "Siding for a new home",
                    "2" => "Siding for a new addition",
                    "3" => "Replace existing siding",
                    "4" => "Repair section(s) of siding",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->type_of_siding_lead_id, $nature_siding_project_array) ){
                    $project_nature_name = $nature_siding_project_array[$leadcustomer_info->type_of_siding_lead_id];
                    $project_nature_id = $leadcustomer_info->type_of_siding_lead_id;
                }

                $project_nature_siding_id = 1;
                if ($project_nature_id == 3) {
                    $project_nature_siding_id = 2;
                } else if ($project_nature_id == 4) {
                    $project_nature_siding_id = 3;
                }

                $type_of_siding_array = array(
                    "1" => "Vinyl Siding",
                    "2" => "Brickface Siding",
                    "3" => "Composite wood Siding",
                    "4" => "Aluminum Siding",
                    "5" => "Stoneface Siding",
                    "6" => "Fiber Cement Siding",
                );

                $type_of_siding_id = $type_of_siding_name = '';
                if( array_key_exists($leadcustomer_info->nature_of_siding_lead_id, $type_of_siding_array) ){
                    $type_of_siding_name = $type_of_siding_array[$leadcustomer_info->nature_of_siding_lead_id];
                    $type_of_siding_id = $leadcustomer_info->nature_of_siding_lead_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of siding? ' . $type_of_siding_name,
                    'two' => 'Type of the project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'type_of_siding' => $type_of_siding_id,
                    'project_nature' => $project_nature_siding_id,
                    'project_nature_siding' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'type_of_siding' => $type_of_siding_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$type_of_siding_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 8:
                //Kitchen
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $service_kitchen_array = array(
                    "1" => "Full Kitchen Remodeling",
                    "2" => "Cabinet Refacing",
                    "3" => "Cabinet Install",
                );

                $service_kitchen_id = $service_kitchen_name = '';
                if( array_key_exists($leadcustomer_info->service_kitchen_lead_id, $service_kitchen_array) ){
                    $service_kitchen_name = $service_kitchen_array[$leadcustomer_info->service_kitchen_lead_id];
                    $service_kitchen_id = $leadcustomer_info->service_kitchen_lead_id;
                }

                $removing_adding_walls = ($leadcustomer_info->campaign_kitchen_r_a_walls_status == 1 ? "Yes" : "No");

                $dataMassageForBuyers = array(
                    'one' => 'Services required? ' . $service_kitchen_name,
                    'two' => 'Demolishing/building walls? ' . $removing_adding_walls,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $service_kitchen_id,
                    'demolishing_walls' => $leadcustomer_info->campaign_kitchen_r_a_walls_status,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id,
                );

                $Leaddatadetails = array(
                    'services' => $service_kitchen_name,
                    'demolishing_walls' => $removing_adding_walls,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$service_kitchen_name, $removing_adding_walls, $homeOwn, $lead_priority_name]";
                break;
            case 9:
                //BathRoom
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $bathroom_type_array = array(
                    "1" => "Full Remodel",
                    "2" => "Cabinets / Vanity",
                    "3" => "Countertops",
                    "4" => "Flooring",
                    "5" => "Shower / Bath",
                    "6" => "Sinks",
                    "7" => "Toilet",
                );

                $bathroom_type_id = $bathroom_type_name = '';
                if( array_key_exists($leadcustomer_info->campaign_bathroomtype_id, $bathroom_type_array) ){
                    $bathroom_type_name = $bathroom_type_array[$leadcustomer_info->campaign_bathroomtype_id];
                    $bathroom_type_id = $leadcustomer_info->campaign_bathroomtype_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Services required? ' . $bathroom_type_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $bathroom_type_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $bathroom_type_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$bathroom_type_name, $homeOwn, $lead_priority_name]";
                break;
            case 10:
                //Stairs
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $stairs_type_array = array(
                    "1" => "Straight",
                    "2" => "Curved"
                );

                $stairs_type_id = $stairs_type_name = '';
                if( array_key_exists($leadcustomer_info->stairs_type_lead_id, $stairs_type_array) ){
                    $stairs_type_name = $stairs_type_array[$leadcustomer_info->stairs_type_lead_id];
                    $stairs_type_id = $leadcustomer_info->stairs_type_lead_id;
                }

                $stairs_reason_array = array(
                    "1" => "Mobility",
                    "2" => "Safety",
                    "3" => "Other"
                );

                $stairs_reason_id = $stairs_reason_name = '';
                if( array_key_exists($leadcustomer_info->stairs_reason_lead_id, $stairs_reason_array) ){
                    $stairs_reason_name = $stairs_reason_array[$leadcustomer_info->stairs_reason_lead_id];
                    $stairs_reason_id = $leadcustomer_info->stairs_reason_lead_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of stairs? ' . $stairs_type_name,
                    'two' => 'The reason for installing the Stairlift ' . $stairs_reason_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'stairs_type' => $stairs_type_id,
                    'reason' => $stairs_reason_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'stairs_type' => $stairs_type_name,
                    'reason' => $stairs_reason_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$stairs_type_name, $stairs_reason_name, $homeOwn, $lead_priority_name]";
                break;
            case 11:
                //Furnace
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $furnace_type_array = array(
                    "1" => "Do Not Know",
                    "2" => "Electric",
                    "3" => "Natural Gas",
                    "4" => "Oil",
                    "5" => "Propane Gas"
                );

                $furnace_type_id = $furnace_type_name = '';
                if( array_key_exists($leadcustomer_info->furnance_type_lead_id, $furnace_type_array) ){
                    $furnace_type_name = $furnace_type_array[$leadcustomer_info->furnance_type_lead_id];
                    $furnace_type_id = $leadcustomer_info->furnance_type_lead_id;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of central heating system required? ' . $furnace_type_name
                );

                $LeaddataIDs = array(
                    'type_of_heating' => $furnace_type_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'type_of_heating' => $furnace_type_name,
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name, $furnace_type_name]";
                break;
            case 12:
                //Boiler
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $furnace_type_array = array(
                    "1" => "Do Not Know",
                    "2" => "Electric",
                    "3" => "Natural Gas",
                    "4" => "Oil",
                    "5" => "Propane Gas"
                );

                $furnace_type_id = $furnace_type_name = '';
                if( array_key_exists($leadcustomer_info->furnance_type_lead_id, $furnace_type_array) ){
                    $furnace_type_name = $furnace_type_array[$leadcustomer_info->furnance_type_lead_id];
                    $furnace_type_id = $leadcustomer_info->furnance_type_lead_id;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                //Send Request
                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name,
                    'four' => 'Type of central heating system required? ' . $furnace_type_name
                );

                $Leaddatadetails = array(
                    'type_of_heating' => $furnace_type_name,
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $LeaddataIDs = array(
                    'type_of_heating' => $furnace_type_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name, $furnace_type_name]";
                break;
            case 13:
                //Central A/C
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name, $project_nature_name]";
                break;
            case 14:
                //Cabinet
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $project_nature_name = ($leadcustomer_info->lead_installing_id == 1 ? 'Cabinet Install' : 'Cabinet Refacing');

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the project? ' . $project_nature_name
                );

                $LeaddataIDs = array(
                    'project_nature' => $leadcustomer_info->lead_installing_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'project_nature' =>$project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name
                );

                $dataMassageForDB = "[$project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 15:
                //Plumbing
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $plumbing_service_array = array(
                    "1" => "Faucet/ Fixture Services",
                    "2" => "Pipe Services",
                    "3" => "Leak Repair",
                    "4" => "Remodeling/ Construction",
                    "5" => "Septic Systems",
                    "6" => "Drain/ Sewer Services",
                    "7" => "Shower Services",
                    "8" => "Sump Pump Services",
                    "9" => "Toilet Services",
                    "10" => "Water Heater Services",
                    "11" => "Water/ Fuel Tank",
                    "12" => "Water Treatment And Purification",
                    "13" => "Well Pump Services",
                    "14" => "Backflow Services",
                    "15" => "Bathroom Plumbing",
                    "16" => "Camera Line Inspection",
                    "17" => "Clogged Sink Repair",
                    "18" => "Disposal Services",
                    "19" => "Excavation",
                    "20" => "Grease Trap Services",
                    "21" => "Kitchen Plumbing",
                    "22" => "Storm Drain Cleaning",
                    "23" => "Trenchless Repairs",
                    "24" => "Water Damage Restoration",
                    "25" => "Water Jetting",
                    "26" => "Water Leak Services",
                    "27" => "Basement Plumbing",
                );

                $plumbing_service_id = $plumbing_service_name = '';
                if( array_key_exists($leadcustomer_info->plumbing_service_list_id, $plumbing_service_array) ){
                    $plumbing_service_name = $plumbing_service_array[$leadcustomer_info->plumbing_service_list_id];
                    $plumbing_service_id = $leadcustomer_info->plumbing_service_list_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of services required? ' . $plumbing_service_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $plumbing_service_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $plumbing_service_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$plumbing_service_name, $homeOwn, $lead_priority_name]";
                break;
            case 16:
                //Bathtubs
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Owner of the Property? ' . $homeOwn,
                    'two' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$homeOwn, $lead_priority_name]";
                break;
            case 17:
                //Sunrooms
                $sunroom_service_array = array(
                    "1" => "Build a new sunroom or patio enclosure",
                    "2" => "Enclose existing porch with roof, walls or windows",
                    "3" => "Screen in existing porch or patio",
                    "4" => "Add a metal awning or cover",
                    "5" => "Add a fabric awning or cover",
                    "6" => "Repair existing sunroom, porch or patio"
                );

                $sunroom_service_id = $sunroom_service_name = '';
                if( array_key_exists($leadcustomer_info->sunroom_service_lead_id, $sunroom_service_array) ){
                    $sunroom_service_name = $sunroom_service_array[$leadcustomer_info->sunroom_service_lead_id];
                    $sunroom_service_id = $leadcustomer_info->sunroom_service_lead_id;
                }

                $property_type_roofing_array = array(
                    "1" => "Residential",
                    "2" => "Commercial",
                );

                $property_type_id = $property_type_name = '';
                if( array_key_exists($leadcustomer_info->lead_property_type_roofing_id, $property_type_roofing_array) ){
                    $property_type_name = $property_type_roofing_array[$leadcustomer_info->lead_property_type_roofing_id];
                    $property_type_id = $leadcustomer_info->lead_property_type_roofing_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of project/services required? ' . $sunroom_service_name,
                    'two' => 'The project is starting: ' . $lead_priority_name,
                    'three' => 'Type of the property? ' . $property_type_name
                );

                $LeaddataIDs = array(
                    'services' => $sunroom_service_id,
                    'property_type_roofing' => $property_type_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $sunroom_service_name,
                    'property_type' => $property_type_name,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$sunroom_service_name, $lead_priority_name, $property_type_name]";
                break;
            case 18:
                //Handyman
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $amount_work_array = array(
                    "1" => "A variety of projects",
                    "2" => "A single project",
                );

                $amount_work_id = $amount_work_name = '';
                if( array_key_exists($leadcustomer_info->handyman_ammount_work_id, $amount_work_array) ){
                    $amount_work_name = $amount_work_array[$leadcustomer_info->handyman_ammount_work_id];
                    $amount_work_id = $leadcustomer_info->handyman_ammount_work_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Type of project? ' . $amount_work_name,
                    'two' => 'Owner of the Property? ' . $homeOwn,
                    'three' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'services' => $amount_work_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'services' => $amount_work_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$amount_work_name, $homeOwn, $lead_priority_name]";
                break;
            case 19:
                //Countertops
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                $countertops_service_array = array(
                    "1" => "Granite",
                    "2" => "Solid Surface (e.g corian)",
                    "3" => "Marble",
                    "4" => "Wood (e.g butcher block)",
                    "5" => "Stainless Steel",
                    "6" => "Laminate",
                    "7" => "Concrete",
                    "8" => "Other Solid Stone (e.g Quartz)",
                );

                $countertops_service_id = $countertops_service_name = '';
                if( array_key_exists($leadcustomer_info->countertops_service_lead_id, $countertops_service_array) ){
                    $countertops_service_name = $countertops_service_array[$leadcustomer_info->countertops_service_lead_id];
                    $countertops_service_id = $leadcustomer_info->countertops_service_lead_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'CounterTop material: ' . $countertops_service_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'service' => $countertops_service_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'service' => $countertops_service_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$countertops_service_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 20:
                //Doors
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                $project_type_array = array(
                    "1" => "Exterior",
                    "2" => "Interior"
                );

                $project_type_id = $project_type_name = '';
                if( array_key_exists($leadcustomer_info->door_typeproject_lead_id, $project_type_array) ){
                    $project_type_name = $project_type_array[$leadcustomer_info->door_typeproject_lead_id];
                    $project_type_id = $leadcustomer_info->door_typeproject_lead_id;
                }

                $number_of_doors_array = array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4+",
                );

                $number_of_doors_id = $number_of_doors_name = '';
                if( array_key_exists($leadcustomer_info->number_of_door_lead_id, $number_of_doors_array) ){
                    $number_of_doors_name = $number_of_doors_array[$leadcustomer_info->number_of_door_lead_id];
                    $number_of_doors_id = $leadcustomer_info->number_of_door_lead_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Interior/Exterior? ' . $project_type_name,
                    'two' => 'Number of doors involved? ' . $number_of_doors_name,
                    'three' => 'Type of project? ' . $project_nature_name,
                    'four' => 'Owner of the Property? ' . $homeOwn,
                    'five' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'homeOwn' =>$homeOwn_id,
                    'start_time' => $lead_priority_id,
                    'door_type' => $project_type_id,
                    'number_of_door' => $number_of_doors_id,
                    'project_nature' => $project_nature_id,
                );

                $Leaddatadetails = array(
                    'door_type' => $project_type_name,
                    'number_of_door' => $number_of_doors_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$project_type_name, $number_of_doors_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 21:
                //Gutter
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $project_nature_array = array(
                    "1" => "Install",
                    "2" => "Replace",
                    "3" => "Repair",
                );

                $project_nature_id = $project_nature_name = '';
                if( array_key_exists($leadcustomer_info->lead_installing_id, $project_nature_array) ){
                    $project_nature_name = $project_nature_array[$leadcustomer_info->lead_installing_id];
                    $project_nature_id = $leadcustomer_info->lead_installing_id;
                }

                $gutter_material_array = array(
                    "1" => "Copper",
                    "2" => "Galvanized Steel",
                    "3" => "PVC",
                    "4" => "Seamless Aluminum",
                    "5" => "Wood",
                    "6" => "not sure"
                );

                $gutter_material_id = $gutter_material_name = '';
                if( array_key_exists($leadcustomer_info->gutters_meterial_lead_id, $gutter_material_array) ){
                    $gutter_material_name = $gutter_material_array[$leadcustomer_info->gutters_meterial_lead_id];
                    $gutter_material_id = $leadcustomer_info->gutters_meterial_lead_id;
                }

                $dataMassageForBuyers = array(
                    'one' => 'Gutter material: ' . $gutter_material_name,
                    'two' => 'Type of project? ' . $project_nature_name,
                    'three' => 'Owner of the Property? ' . $homeOwn,
                    'four' => 'The project is starting: ' . $lead_priority_name
                );

                $LeaddataIDs = array(
                    'service' => $gutter_material_id,
                    'project_nature' => $project_nature_id,
                    'homeOwn' => $homeOwn_id,
                    'start_time' => $lead_priority_id
                );

                $Leaddatadetails = array(
                    'service' => $gutter_material_name,
                    'project_nature' => $project_nature_name,
                    'homeOwn' => $homeOwn,
                    'start_time' => $lead_priority_name,
                );

                $dataMassageForDB = "[$gutter_material_name, $project_nature_name, $homeOwn, $lead_priority_name]";
                break;
            case 22:
                //Paving
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $paving_service_array = array(
                    "1" => "Asphalt Paving - Install",
                    "2" => "Asphalt Sealing",
                    "3" => "Gravel or Loose Fill Paving - Install, Spread or Scrape",
                    "4" => "Asphalt Paving - Repair or Patch",
                );

                $paving_service_id = $paving_service_name = '';
                if( array_key_exists($leadcustomer_info->paving_service_lead_id, $paving_service_array) ){
                    $paving_service_name = $paving_service_array[$leadcustomer_info->paving_service_lead_id];
                    $paving_service_id = $leadcustomer_info->paving_service_lead_id;
                }

                $paving_best_describes_priject_array = array(
                    "1" => "New Layout",
                    "2" => "Restripe",
                );

                $paving_best_describes_priject_id = $paving_best_describes_priject_name = '';
                if( array_key_exists($leadcustomer_info->paving_best_describes_priject_id, $paving_best_describes_priject_array) ){
                    $paving_best_describes_priject_name = $paving_best_describes_priject_array[$leadcustomer_info->paving_best_describes_priject_id];
                    $paving_best_describes_priject_id = $leadcustomer_info->paving_best_describes_priject_id;
                }

                switch ($paving_service_id) {
                    case "1":
                        $paving_asphalt_type = array(
                            "1" => "Driveway",
                            "2" => "Road",
                            "3" => "Walkway or sidewalk",
                            "4" => "Patio",
                            "5" => "Sports court (tennis, basketball, etc.)",
                            "6" => "Parking Lot",
                        );

                        $paving_asphalt_type_id = $paving_asphalt_type_name = '';
                        if( array_key_exists($leadcustomer_info->paving_asphalt_type_id, $paving_asphalt_type) ){
                            $paving_asphalt_type_name = $paving_asphalt_type[$leadcustomer_info->paving_asphalt_type_id];
                            $paving_asphalt_type_id = $leadcustomer_info->paving_asphalt_type_id;
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'The area needing asphalt ' . $paving_asphalt_type_name,
                            'three' => 'Type of project ' . $paving_best_describes_priject_name,
                            'four' => 'Owner of the Property? ' . $homeOwn,
                            'five' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'asphalt_needing' => $paving_asphalt_type_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'asphalt_needing' => $paving_asphalt_type_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_asphalt_type_name, $paving_best_describes_priject_name, $homeOwn, $lead_priority_name]";
                        break;
                    case 3:
                        $paving_loose_fill_type_array = array(
                            "1" => "Driveway",
                            "2" => "Road",
                            "3" => "Walkway or sidewalk",
                            "4" => "Patio",
                            "5" => "Sports court (tennis, basketball, etc.)",
                            "6" => "Parking Lot",
                        );

                        $paving_loose_fill_type_id = $paving_loose_fill_type_name = '';
                        if( array_key_exists($leadcustomer_info->paving_loose_fill_type_id, $paving_loose_fill_type_array) ){
                            $paving_loose_fill_type_name = $paving_loose_fill_type_array[$leadcustomer_info->paving_loose_fill_type_id];
                            $paving_loose_fill_type_id = $leadcustomer_info->paving_loose_fill_type_id;
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'Material of loose fill required ' . $paving_loose_fill_type_name,
                            'three' => 'Type of project ' . $paving_best_describes_priject_name,
                            'four' => 'Owner of the Property? ' . $homeOwn,
                            'five' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'material_loose' => $paving_loose_fill_type_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'material_loose' => $paving_loose_fill_type_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_best_describes_priject_name, $paving_loose_fill_type_name, $homeOwn, $lead_priority_name]";
                        break;
                    default:
                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $paving_service_name,
                            'two' => 'Type of project ' . $paving_best_describes_priject_name,
                            'three' => 'Owner of the Property? ' . $homeOwn,
                            'four' => 'The project is starting: ' . $lead_priority_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $paving_service_id,
                            'project_type' => $paving_best_describes_priject_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $paving_service_name,
                            'project_type' => $paving_best_describes_priject_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                        );

                        $dataMassageForDB = "[$paving_service_name, $paving_best_describes_priject_name, $homeOwn, $lead_priority_name]";
                }
                break;
            case 23:
                //Painting
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $painting_service_array = array(
                    "1" => "Exterior Home or Structure - Paint or Stain",
                    "2" => "Interior Home or Surfaces - Paint or Stain",
                    "3" => "Painting or Staining - Small Projects",
                    "4" => "Metal Roofing - Paint",
                    "5" => "Specialty Painting - Textures",
                );

                $painting_service_id = $painting_service_name = '';
                if( array_key_exists($leadcustomer_info->painting_service_lead_id, $painting_service_array) ){
                    $painting_service_name = $painting_service_array[$leadcustomer_info->painting_service_lead_id];
                    $painting_service_id = $leadcustomer_info->painting_service_lead_id;
                }

                switch ($painting_service_id) {
                    case "1":
                        $painting1_typeof_project_array = array(
                            "1" => "New Construction",
                            "2" => "Repaint",
                            "3" => "Restain",
                        );

                        $painting1_typeof_project_id = $painting1_typeof_project_name = '';
                        if( array_key_exists($leadcustomer_info->painting1_typeof_project_id, $painting1_typeof_project_array) ){
                            $painting1_typeof_project_name = $painting1_typeof_project_array[$leadcustomer_info->painting1_typeof_project_id];
                            $painting1_typeof_project_id = $leadcustomer_info->painting1_typeof_project_id;
                        }

                        $painting1_stories_array = array(
                            "1" => "One Story",
                            "2" => "Two Stories",
                            "3" => "Three Stories or more",
                        );

                        $painting1_stories_id = $painting1_stories_name = '';
                        if( array_key_exists($leadcustomer_info->painting1_stories_number_id, $painting1_stories_array) ){
                            $painting1_stories_name = $painting1_stories_array[$leadcustomer_info->painting1_stories_number_id];
                            $painting1_stories_id = $leadcustomer_info->painting1_stories_number_id;
                        }

                        $painting1_kindsof_surfaces_array = array(
                            "1" => "New layout",
                            "2" => "Siding",
                            "3" => "Trim",
                            "4" => "Doors",
                            "5" => "Stucco",
                            "6" => "Shutters",
                            "7" => "Fence",
                            "8" => "Masonry (brick/stone)",
                            "9" => "Other",
                        );

                        $painting1_kindsof_surfaces_id = $painting1_kindsof_surfaces_name = '';
                        if( array_key_exists($leadcustomer_info->painting1_kindsof_surfaces_id, $painting1_kindsof_surfaces_array) ){
                            $painting1_kindsof_surfaces_name = $painting1_kindsof_surfaces_array[$leadcustomer_info->painting1_kindsof_surfaces_id];
                            $painting1_kindsof_surfaces_id = $leadcustomer_info->painting1_kindsof_surfaces_id;
                        }

                        $interior_historical_name = ($leadcustomer_info->historical_structure == 1 ? "Yes" : "No");

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Type of project ' . $painting1_typeof_project_name,
                            'five' => 'Number of stories of the property ' . $painting1_stories_name,
                            'six' => 'What kinds of surfaces need to be painted and/or stained? ' . $painting1_kindsof_surfaces_name,
                            'seven' => 'Is the location a historical structure? ' . $interior_historical_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'project_type' => $painting1_typeof_project_id,
                            'historical_structure' => $leadcustomer_info->historical_structure,
                            'stories_number' => $painting1_stories_id,
                            'surfaces_kinds' => $painting1_kindsof_surfaces_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'project_type' => $painting1_typeof_project_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'stories_number' => $painting1_stories_name,
                            'surfaces_kinds' => $painting1_kindsof_surfaces_name,
                            'historical_structure' => $interior_historical_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting1_typeof_project_name, $painting1_stories_name, $painting1_kindsof_surfaces_name, $interior_historical_name]";
                        break;
                    case 2:
                        $painting2_rooms_number_array = array(
                            "1" => "1-2",
                            "2" => "3-4",
                            "3" => "5-6",
                            "4" => "7-8",
                            "5" => "9 or more",
                        );

                        $painting2_rooms_number_id = $painting2_rooms_number_name = '';
                        if( array_key_exists($leadcustomer_info->painting2_rooms_number_id, $painting2_rooms_number_array) ){
                            $painting2_rooms_number_name = $painting2_rooms_number_array[$leadcustomer_info->painting2_rooms_number_id];
                            $painting2_rooms_number_id = $leadcustomer_info->painting2_rooms_number_id;
                        }

                        $painting2_typeof_paint_array = array(
                            "1" => "Walls",
                            "2" => "Walls And Ceilings",
                            "3" => "Ceilings",
                            "4" => "Others",
                        );

                        $painting2_typeof_paint_id = $painting2_typeof_paint_name = '';
                        if( array_key_exists($leadcustomer_info->painting2_typeof_paint_id, $painting2_typeof_paint_array) ){
                            $painting2_typeof_paint_name = $painting2_typeof_paint_array[$leadcustomer_info->painting2_typeof_paint_id];
                            $painting2_typeof_paint_id = $leadcustomer_info->painting2_typeof_paint_id;
                        }

                        $interior_historical_name = ($leadcustomer_info->historical_structure == 1 ? "Yes" : "No");

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Number of rooms need to be painted ' . $painting2_rooms_number_name,
                            'six' => 'What needs to be painted ' . $painting2_typeof_paint_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $leadcustomer_info->historical_structure,
                            'rooms_number' => $painting2_rooms_number_id,
                            'painted_needs' => $painting2_typeof_paint_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id,

                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'historical_structure' => $interior_historical_name,
                            'rooms_number' => $painting2_rooms_number_name,
                            'painted_needs' => $painting2_typeof_paint_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,

                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting2_rooms_number_name, $painting2_typeof_paint_name, $interior_historical_name]";
                        break;
                    case 3:
                        $interior_historical_name = ($leadcustomer_info->historical_structure == 1 ? "Yes" : "No");

                        $painting3_each_feature_array_static = array(
                            "1" => "Exterior Door(s)",
                            "2" => "Exterior Siding",
                            "3" => "Exterior Wood Trim",
                            "4" => "Fencing / Gates",
                            "5" => "Interior Door(s)",
                            "6" => "Interior Walls",
                            "7" => "Interior Wood Trim",
                            "8" => "Ceiling",
                            "9" => "Cabinetry",
                            "10" => "Fireplace",
                            "11" => "Paneling",
                            "12" => "Others",
                        );

                        $painting3_each_feature = '';
                        $painting3_each_feature_array = array();
                        $painting3_each_feature_id = $leadcustomer_info->painting3_each_feature_id;
                        if (!empty($painting3_each_feature_id)) {
                            if (is_array($painting3_each_feature_id)) {
                                $painting3_each_feature_array = $painting3_each_feature_id;
                            } else {
                                $painting3_each_feature_array = json_decode($painting3_each_feature_id, true);
                            }

                            foreach ($painting3_each_feature_array as $key => $val) {
                                $painting3_each_feature_name = '';
                                if( array_key_exists($val, $painting3_each_feature_array_static) ){
                                    $painting3_each_feature_name = $painting3_each_feature_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting3_each_feature .= ', ';
                                }

                                $painting3_each_feature .= $painting3_each_feature_name;
                            }
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Areas need to be painted/stained ' . $painting3_each_feature,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $leadcustomer_info->historical_structure,
                            'painted_feature' => $painting3_each_feature_array,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'painted_feature' => $painting3_each_feature,
                            'historical_structure' => $interior_historical_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $interior_historical_name, ($painting3_each_feature)]";
                        break;
                    case 4:
                        $interior_historical_name = ($leadcustomer_info->historical_structure == 1 ? "Yes" : "No");

                        $painting1_stories_array = array(
                            "1" => "One Story",
                            "2" => "Two Stories",
                            "3" => "Three Stories or more",
                        );

                        $painting1_stories_id = $painting1_stories_name = '';
                        if( array_key_exists($leadcustomer_info->painting1_stories_number_id, $painting1_stories_array) ){
                            $painting1_stories_name = $painting1_stories_array[$leadcustomer_info->painting1_stories_number_id];
                            $painting1_stories_id = $leadcustomer_info->painting1_stories_number_id;
                        }

                        $painting4_existing_roof_array_static = array(
                            "1" => "Peeling or Blistering",
                            "2" => "Bleeding",
                            "3" => "Nail Stains",
                            "4" => "Mildew",
                            "5" => "Chalking",
                            "6" => "No Known Problems",
                            "7" => "Fair Condition",
                            "8" => "Never Been Painted Before",
                            "9" => "Others",
                            "10" => "Don't Know",
                        );

                        $painting4_existing_roof = '';
                        $painting4_existing_roof_array = array();
                        $painting4_existing_roof_id = $leadcustomer_info->painting4_existing_roof_id;
                        if (!empty($painting4_existing_roof_id)) {
                            if (is_array($painting4_existing_roof_id)) {
                                $painting4_existing_roof_array = $painting4_existing_roof_id;
                            } else {
                                $painting4_existing_roof_array = json_decode($painting4_existing_roof_id, true);
                            }

                            foreach ($painting4_existing_roof_array as $key => $val) {
                                $painting4_existing_roof_name = '';
                                if( array_key_exists($val, $painting4_existing_roof_array_static) ){
                                    $painting4_existing_roof_name = $painting4_existing_roof_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting4_existing_roof .= ', ';
                                }

                                $painting4_existing_roof .= $painting4_existing_roof_name;
                            }
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'Is the location a historical structure? ' . $interior_historical_name,
                            'five' => 'Number of stories of the property ' . $painting1_stories_name,
                            'six' => 'The condition of the existing roof ' . $painting4_existing_roof
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'historical_structure' => $leadcustomer_info->historical_structure,
                            'stories_number' => $painting1_stories_id,
                            'existing_roof' => $painting4_existing_roof_array,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_name
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'stories_number' => $painting1_stories_name,
                            'historical_structure' => $interior_historical_name,
                            'existing_roof' => $painting4_existing_roof,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $interior_historical_name, $painting1_stories_name, ($painting4_existing_roof)]";
                        break;
                    case 5:
                        $painting5_kindof_texturing_array_static = array(
                            "1" => "Apply Texture To Unfinished Drywall for Paint",
                            "2" => "Match New Drywall To Exisiting Walls/Ceilings",
                            "3" => "Repair / Patch Drywall",
                            "4" => "Prepare For Wallpaper / Special Finish",
                            "5" => "Remove Popcorn Acoustic Ceiling Spray",
                            "6" => "Create Faux Effects",
                            "7" => "Paint Also Needed",
                            "8" => "Other",
                        );

                        $painting5_kindof_texturing = '';
                        $painting5_kindof_texturing_array = array();
                        $painting5_kindof_texturing_id = $leadcustomer_info->painting5_kindof_texturing_id;
                        if (!empty($painting5_kindof_texturing_id)) {
                            if (is_array($painting5_kindof_texturing_id)) {
                                $painting5_kindof_texturing_array = $painting5_kindof_texturing_id;
                            } else {
                                $painting5_kindof_texturing_array = json_decode($painting5_kindof_texturing_id, true);
                            }

                            foreach ($painting5_kindof_texturing_array as $key => $val) {
                                $painting5_kindof_texturing_name = '';
                                if( array_key_exists($val, $painting5_kindof_texturing_array_static) ){
                                    $painting5_kindof_texturing_name = $painting5_kindof_texturing_array_static[$val];
                                }

                                if ($key != 0) {
                                    $painting5_kindof_texturing .= ', ';
                                }

                                $painting5_kindof_texturing .= $painting5_kindof_texturing_name;
                            }
                        }

                        $painting5_surfaces_textured_array = array(
                            "1" => "Walls",
                            "2" => "Ceilings",
                            "3" => "Others",
                        );

                        $painting5_surfaces_textured_id = $painting5_surfaces_textured_name = '';
                        if( array_key_exists($leadcustomer_info->painting5_surfaces_textured_id, $painting5_surfaces_textured_array) ){
                            $painting5_surfaces_textured_name = $painting5_surfaces_textured_array[$leadcustomer_info->painting5_surfaces_textured_id];
                            $painting5_surfaces_textured_id = $leadcustomer_info->painting5_surfaces_textured_id;
                        }

                        $dataMassageForBuyers = array(
                            'one' => 'Type of service ' . $painting_service_name,
                            'two' => 'Owner of the Property? ' . $homeOwn,
                            'three' => 'The project is starting: ' . $lead_priority_name,
                            'four' => 'What kind of texturing needed? ' . $painting5_kindof_texturing,
                            'five' => 'What surfaces need to be textured? ' . $painting5_surfaces_textured_name,
                        );

                        $LeaddataIDs = array(
                            'service' => $painting_service_id,
                            'surfaces_kinds' => $painting5_kindof_texturing_array,
                            'surfaces_textured' => $painting5_surfaces_textured_id,
                            'homeOwn' => $homeOwn_id,
                            'start_time' => $lead_priority_id
                        );

                        $Leaddatadetails = array(
                            'service' => $painting_service_name,
                            'homeOwn' => $homeOwn,
                            'start_time' => $lead_priority_name,
                            'surfaces_kinds' => $painting5_kindof_texturing,
                            'surfaces_textured' => $painting5_surfaces_textured_name,
                        );

                        $dataMassageForDB = "[$painting_service_name, $homeOwn, $lead_priority_name, $painting5_surfaces_textured_name, ($painting5_kindof_texturing)]";
                        break;
                }
                break;
            case 24:
                //Auto Insurance
                if ($leadcustomer_info->lead_ownership == '1') {
                    $homeOwn = 'Yes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else if ($leadcustomer_info->lead_ownership == '0') {
                    $homeOwn = 'No';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                } else {
                    $homeOwn = 'No, But Authorized to Make Changes';
                    $homeOwn_id= $leadcustomer_info->lead_ownership;
                }

                $VehicleYear = $leadcustomer_info->VehicleYear;
                $VehicleMake = $leadcustomer_info->VehicleMake;
                $car_model = $leadcustomer_info->car_model;
                $more_than_one_vehicle = $leadcustomer_info->more_than_one_vehicle;
                $driversNum = $leadcustomer_info->driversNum;
                $birthday = $leadcustomer_info->birthday;
                $genders = $leadcustomer_info->genders;
                $married = $leadcustomer_info->married;
                $license = $leadcustomer_info->license;
                $InsuranceCarrier = $leadcustomer_info->InsuranceCarrier;
                $driver_experience = $leadcustomer_info->driver_experience;
                $number_of_tickets = $leadcustomer_info->number_of_tickets;
                $DUI_charges = $leadcustomer_info->DUI_charges;
                $SR_22_need = $leadcustomer_info->SR_22_need;
                $submodel = $leadcustomer_info->submodel;
                $coverage_type = $leadcustomer_info->coverage_type;
                $license_status = $leadcustomer_info->license_status;
                $license_state = $leadcustomer_info->license_state;
                $ticket_date = $leadcustomer_info->ticket_date;
                $violation_date = $leadcustomer_info->violation_date;
                $accident_date = $leadcustomer_info->accident_date;
                $claim_date = $leadcustomer_info->claim_date;
                $expiration_date = $leadcustomer_info->expiration_date;

                $dataMassageForBuyers = array(
                    'one' => 'Vehicle Make Year: ' .  $VehicleYear,
                    'two' => 'Vehicle Brand: ' . $VehicleMake,
                    'three' => 'Car Model: ' . $car_model,
                    'four' => 'Is there more than one vehicle owned by the driver?' . $more_than_one_vehicle,
                    'five' => 'Is there more than one driver in the household?' . $driversNum,
                    'six' => 'Birthdate of the driver: ' . $birthday,
                    'seven' => "Driver's Gender: " . $genders,
                    'eight' => 'Is the driver married?' . $married,
                    'nine' => 'Owner of a valid driving license: ' . $license,
                    'ten' => 'Current insurance company: ' . $InsuranceCarrier,
                    'eleven' => 'Is the driver experienced? ' . $driver_experience,
                    'twelve' => 'Number of tickets or accidents: ' .$number_of_tickets,
                    'thirteen' => 'Are there any DUI charges?' . $DUI_charges,
                    'fourteen' => 'Is there a need for SR-22? ' . $SR_22_need,
                    'fifteen' => 'Are you a home owner? ' . $homeOwn,
                    'sixteen' => 'Car Submodel: ' . $submodel,
                    'seventeen' => 'Insurance Coverage Type: ' . $coverage_type,
                    'eighteen' => 'License Status: ' . $license_status,
                    'nineteen' => 'License State: ' . $license_state,
                    'twenty' => 'Ticket Date: ' . $ticket_date,
                    'twentyone' => 'Violation Date: ' . $violation_date,
                    'twentytwo' => 'Accident Date: ' . $accident_date,
                    'twentythree' => 'Insurance Claim Date: ' . $claim_date,
                    'twentyfour' => 'Insurance Expiration Date: ' . $expiration_date
                );

                $LeaddataIDs = array(
                    'VehicleYear' => $VehicleYear,
                    'VehicleMake' => $VehicleMake,
                    'car_model' => $car_model,
                    'more_than_one_vehicle' => $more_than_one_vehicle,
                    'driversNum' => $driversNum,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'married' => $married,
                    'license' => $license,
                    'InsuranceCarrier' => $InsuranceCarrier,
                    'driver_experience' => $driver_experience,
                    'number_of_tickets' => $number_of_tickets,
                    'DUI_charges' => $DUI_charges,
                    'SR_22_need' => $SR_22_need,
                    'submodel' => $submodel,
                    'coverage_type' => $coverage_type,
                    'license_status' => $license_status,
                    'license_state' => $license_state,
                    'ticket_date' => $ticket_date,
                    'violation_date' => $violation_date,
                    'accident_date' => $accident_date,
                    'claim_date' => $claim_date,
                    'expiration_date' => $expiration_date,
                    'homeOwn' => $leadcustomer_info->lead_ownership
                );

                $Leaddatadetails = array(
                    'VehicleYear' => $VehicleYear,
                    'VehicleMake' => $VehicleMake,
                    'car_model' => $car_model,
                    'more_than_one_vehicle' => $more_than_one_vehicle,
                    'driversNum' => $driversNum,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'married' => $married,
                    'license' => $license,
                    'InsuranceCarrier' => $InsuranceCarrier,
                    'driver_experience' => $driver_experience,
                    'number_of_tickets' => $number_of_tickets,
                    'DUI_charges' => $DUI_charges,
                    'SR_22_need' => $SR_22_need,
                    'submodel' => $submodel,
                    'coverage_type' => $coverage_type,
                    'license_status' => $license_status,
                    'license_state' => $license_state,
                    'ticket_date' => $ticket_date,
                    'violation_date' => $violation_date,
                    'accident_date' => $accident_date,
                    'claim_date' => $claim_date,
                    'expiration_date' => $expiration_date,
                    'homeOwn' => $homeOwn
                );

                $dataMassageForDB = "[$submodel, $coverage_type, $license_status, $license_state, $ticket_date, $violation_date, $accident_date, $claim_date, $expiration_date, $homeOwn, $VehicleYear, $VehicleMake, $car_model, $more_than_one_vehicle, $driversNum, $birthday, $genders, $married, $license, $InsuranceCarrier, $driver_experience, $number_of_tickets, $DUI_charges, $SR_22_need]";
                break;
            case 25:
                //home insurance
                $house_type = $leadcustomer_info->house_type;
                $Year_Built = $leadcustomer_info->Year_Built;
                $primary_residence = $leadcustomer_info->primary_residence;
                $new_purchase = $leadcustomer_info->new_purchase;
                $previous_insurance_within_last30= $leadcustomer_info->previous_insurance_within_last30;
                $previous_insurance_claims_last3yrs = $leadcustomer_info->previous_insurance_claims_last3yrs;
                $married = $leadcustomer_info->married;
                $credit_rating = $leadcustomer_info->credit_rating;
                $birthday = $leadcustomer_info->birthday;

                $dataMassageForBuyers = array(
                    'one' => 'What kind of home Do You Live In: ' . $house_type,
                    'two' => 'Year built: ' . $Year_Built,
                    'three' => 'Is this your primary residence: ' . $primary_residence,
                    'four' => 'Is this a new purchase' . $new_purchase,
                    'five' => 'Have you had insurance within the last 30 days' . $previous_insurance_within_last30,
                    'six' => 'Have you made any home insurance claims in the past 3 years: ' . $previous_insurance_claims_last3yrs,
                    'seven' => "Are you married: " . $married,
                    'eight' => 'Credit rating?' . $credit_rating,
                    'nine' => 'When is your birthday: ' . $birthday,
                );

                $LeaddataIDs = array(
                    'house_type' => $house_type,
                    'Year_Built' => $Year_Built,
                    'primary_residence' => $primary_residence,
                    'new_purchase' => $new_purchase,
                    'previous_insurance_within_last30' => $previous_insurance_within_last30,
                    'previous_insurance_claims_last3yrs' => $previous_insurance_claims_last3yrs,
                    'married' => $married,
                    'credit_rating' => $credit_rating,
                    'birthday' => $birthday,
                );

                $Leaddatadetails = array(
                    'house_type' => $house_type,
                    'Year_Built' => $Year_Built,
                    'primary_residence' => $primary_residence,
                    'new_purchase' => $new_purchase,
                    'previous_insurance_within_last30' => $previous_insurance_within_last30,
                    'previous_insurance_claims_last3yrs' => $previous_insurance_claims_last3yrs,
                    'married' => $married,
                    'credit_rating' => $credit_rating,
                    'birthday' => $birthday,
                );

                $dataMassageForDB = "[$house_type, $Year_Built, $primary_residence, $new_purchase, $previous_insurance_within_last30, $previous_insurance_claims_last3yrs, $married, $credit_rating, $birthday]";
                break;
            case 26:
            case 27:
                //Life Insurance & Disability insurance
                $Height = $leadcustomer_info->Height;
                $weight = $leadcustomer_info->weight;
                $birthday = $leadcustomer_info->birthday;
                $genders = $leadcustomer_info->genders;
                $amount_coverage = $leadcustomer_info->amount_coverage;
                $military_personnel_status = $leadcustomer_info->military_personnel_status;
                $military_status = $leadcustomer_info->military_status;
                $service_branch = $leadcustomer_info->service_branch;

                $dataMassageForBuyers = array(
                    'one' => 'Select Your Height? ' .  $Height,
                    'two' => 'Enter Your Weight? ' . $weight,
                    'three' => 'When is your birthday? ' . $birthday,
                    'four' => 'Whats your gender?' . $genders,
                    'five' => 'Amount of Coverage You are Considering ?' . $amount_coverage,
                    'six' => 'Are you active or retired military personnel? ' . $military_personnel_status,
                    'seven' => "Military status ? " . $military_status,
                    'eight' => 'Service branch ?' . $service_branch,
                );

                $LeaddataIDs = array(
                    'Height' => $Height,
                    'weight' => $weight,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'amount_coverage' => $amount_coverage,
                    'military_personnel_status' => $military_personnel_status,
                    'military_status' => $military_status,
                    'service_branch' => $service_branch
                );

                $Leaddatadetails = array(
                    'Height' => $Height,
                    'weight' => $weight,
                    'birthday' => $birthday,
                    'genders' => $genders,
                    'amount_coverage' => $amount_coverage,
                    'military_personnel_status' => $military_personnel_status,
                    'military_status' => $military_status,
                    'service_branch' => $service_branch
                );

                $dataMassageForDB = "[$Height, $weight, $birthday, $genders, $amount_coverage, $military_personnel_status, $military_status, $service_branch]";
                break;
            case 28:
                //Business insurance
                $CommercialCoverage = $leadcustomer_info->CommercialCoverage;
                $company_benefits_quote = $leadcustomer_info->company_benefits_quote;
                $business_start_date = $leadcustomer_info->business_start_date;
                $estimated_annual_payroll = $leadcustomer_info->estimated_annual_payroll;
                $number_of_employees = $leadcustomer_info->number_of_employees;
                $coverage_start_month = $leadcustomer_info->coverage_start_month;
                $business_name = $leadcustomer_info->business_name;

                $dataMassageForBuyers = array(
                    'one' => 'What coverage does your business need? ' .  $CommercialCoverage,
                    'two' => 'Would you also like to get quotes for your companies benefits? ' . $company_benefits_quote,
                    'three' => 'When did you start your business? ' . $business_start_date,
                    'four' => 'What is Your Estimated Annual Employee Payroll in the Next 12 Months?' . $estimated_annual_payroll,
                    'five' => 'Total # of Employees including Yourself ?' . $number_of_employees,
                    'six' => 'When would you like the coverage to begin? ' . $coverage_start_month,
                    'seven' => "Business Name ?" . $business_name,
                );

                $LeaddataIDs = array(
                    'CommercialCoverage' => $CommercialCoverage,
                    'company_benefits_quote' => $company_benefits_quote,
                    'business_start_date' => $business_start_date,
                    'estimated_annual_payroll' => $estimated_annual_payroll,
                    'number_of_employees' => $number_of_employees,
                    'coverage_start_month' => $coverage_start_month,
                    'business_name' => $business_name
                );

                $Leaddatadetails = array(
                    'CommercialCoverage' => $CommercialCoverage,
                    'company_benefits_quote' => $company_benefits_quote,
                    'business_start_date' => $business_start_date,
                    'estimated_annual_payroll' => $estimated_annual_payroll,
                    'number_of_employees' => $number_of_employees,
                    'coverage_start_month' => $coverage_start_month,
                    'business_name' => $business_name
                );

                $dataMassageForDB = "[$CommercialCoverage, $company_benefits_quote, $business_start_date, $estimated_annual_payroll, $number_of_employees, $coverage_start_month, $business_name]";
                break;
            case 29:
            case 30:
                //Health Insurance & long term insurance
                $gender = $leadcustomer_info->genders;
                $birthday = $leadcustomer_info->birthday;
                $pregnancy = $leadcustomer_info->pregnancy;
                $tobacco_usage = $leadcustomer_info->tobacco_usage;
                $health_conditions = $leadcustomer_info->health_conditions;
                $number_of_people_in_household = $leadcustomer_info->number_of_people_in_household;
                $addPeople = $leadcustomer_info->addPeople;
                $annual_income = $leadcustomer_info->annual_income;

                $dataMassageForBuyers = array(
                    'one' => 'Whats your gender?' .  $gender,
                    'two' => 'When is your birthday?' . $birthday,
                    'three' => 'Are you or your spouse pregnant right now, or adopting a child? ' . $pregnancy,
                    'four' => 'Do you use tobacco?' . $tobacco_usage,
                    'five' => 'Do you have any of these health conditions?' . $health_conditions,
                    'six' => 'How many people are in your household?' . $number_of_people_in_household,
                    'seven' => "Is your insurance just for you, or do you plan on covering others?" . $addPeople,
                    'eight' => 'Whats your annual household income?' . $annual_income,
                );

                $LeaddataIDs = array(
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'pregnancy' => $pregnancy,
                    'tobacco_usage' => $tobacco_usage,
                    'health_conditions' => $health_conditions,
                    'number_of_people_in_household' => $number_of_people_in_household,
                    'addPeople' => $addPeople,
                    'annual_income' => $annual_income,
                );

                $Leaddatadetails = array(
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'pregnancy' => $pregnancy,
                    'tobacco_usage' => $tobacco_usage,
                    'health_conditions' => $health_conditions,
                    'number_of_people_in_household' => $number_of_people_in_household,
                    'addPeople' => $addPeople,
                    'annual_income' => $annual_income,
                );

                $dataMassageForDB = "[$gender, $birthday, $pregnancy, $tobacco_usage, $health_conditions, $number_of_people_in_household,$addPeople, $annual_income]";
                break;
            case 31:
                //debt relief
                $debt_amount = $leadcustomer_info->debt_amount;
                $debt_type = $leadcustomer_info->debt_type;

                $dataMassageForBuyers = array(
                    'one' => 'Debt Amount? ' . $debt_amount,
                    'two' => 'Debt Type? ' .  $debt_type,
                );

                $LeaddataIDs = array(
                    'debt_amount' => $debt_amount,
                    'debt_type' => $debt_type,
                );

                $Leaddatadetails = array(
                    'debt_amount' => $debt_amount,
                    'debt_type' => $debt_type,
                );

                $dataMassageForDB = "[$debt_amount, ($debt_type)]";
                break;
        }
        //==============================================================================================================

        return array(
            "dataMassageForBuyers" => $dataMassageForBuyers,
            "Leaddatadetails" => $Leaddatadetails,
            "LeaddataIDs" => $LeaddataIDs,
            "dataMassageForDB" => $dataMassageForDB
        );
    }

    public function ipregistry_validation($ip){

        return 1;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ipregistry.co/$ip?key=eigbie9bepwx2wjk",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
//            CURLOPT_HTTPHEADER => array(
//                'x-api-key: UgxbVYQWMY8fnLBQvEN2h9yTkUXz6ILp3SbRcnfx'
//            ),
        ));

        $result = curl_exec($curl);
        $result = json_decode($result , true);
        curl_close($curl);
        /**
         * security section inside result contain
        is_abuser:false,
        is_attacker:false,
        is_bogon:false,
        is_cloud_provider:true,
        is_proxy:false,
        is_relay:false,
        is_tor:false,
        is_tor_exit:false,
        is_vpn:true,
        is_anonymous:true,
        is_threat:false
         * security section type text so we need to converting to array
         */
        $security = $result["security"];
        $security_to_array = implode(",", $security);
        $security_to_array2 = explode(',',$security_to_array);

        if($security_to_array2["8"] == 1 || $security_to_array2["9"] == 1 || $security_to_array2["10"] == 1){
            return "failed IP validation!";
        }else{
            return "true";
        }
    }
}
