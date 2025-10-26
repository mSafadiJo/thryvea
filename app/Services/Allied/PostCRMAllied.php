<?php

namespace App\Services\Allied;

use App\Services\CrmApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostCRMAllied {
    public function callTools($data_msg, $crm_details){
        if(empty($crm_details['callTollsDetails'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "callTollsDetails"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $Lead_data_array = array(
            'auth_token' => $crm_details['callTollsDetails']['api_key'],
            'title' => ucfirst($crm_details['service_campaign_name']),
            'company_name' => ucfirst($crm_details['service_campaign_name']),
            'first_name'=> $data_msg['first_name'],
            'last_name'=> $data_msg['last_name'],
            'email_address' => $data_msg['LeadEmail'],
            'zip_code' => $data_msg['Zipcode'],
            'address' => $data_msg['street'],
            'city' => $data_msg['City'],
            'state' => $data_msg['State'],
            'phone_number' => $data_msg['LeadPhone'],
            'mobile_number' => $data_msg['LeadPhone'],
            'dial_duplicate' => 1,
            'note' => "Lead From " . config('app.name', '')
        );

        if( !empty($crm_details['callTollsDetails']['file_id']) ){
            $Lead_data_array['file'] = $crm_details['callTollsDetails']['file_id'];
            $Lead_data_array['hot_lead'] = 1;
        }

        $url_api = "https://app.calltools.com/api/contacts/";

        $leadsCustomerCampaign_id = $data_msg['leadCustomer_id'];

        $url_api = str_replace(" ","%20",$url_api);

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        $from_job = (!empty($crm_details['from_job']) ? 1 : 0 );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id'], $from_job);
        return 1;
    }

    public function five9Crm($data_msg, $crm_details){
        //Five9 CRM
        if(empty($crm_details['Five9Details'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "Five9Details"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = $data_msg['first_name'];
        $last_name = $data_msg['last_name'];
        $number1 = $data_msg['LeadPhone'];
        $zip = $data_msg['Zipcode'];
        $street = $data_msg['street'];
        $city = $data_msg['City'];
        $email = $data_msg['LeadEmail'];
        $state = $data_msg['State'];
        $Product = strtoupper($crm_details['service_campaign_name']);
        $leadsCustomerCampaign_id = strtoupper($crm_details['leadsCustomerCampaign_id']);
        $five9_domian = $crm_details['Five9Details']['five9_domian'];
        $five9_list = $crm_details['Five9Details']['five9_list'];

        $url_api = "https://api.five9.com/web2campaign/AddToList?F9domain=$five9_domian&F9list=$five9_list&number1=$number1&first_name=$first_name&last_name=$last_name&email=$email&street=$street&city=$city&zip=$zip&Leadkey=$leadsCustomerCampaign_id&Product=$Product&F9CallASAP=True%20Response%20String%20:";
        $url_api = str_replace(" ","+",$url_api);

        $httpheader = array();

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "GET", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function leadsPedia($data_msg, $crm_details){
        if(empty($crm_details['Leads_PediaDetails'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "Leads_PediaDetails"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = $data_msg['first_name'];
        $last_name = $data_msg['last_name'];
        $number1 = $data_msg['LeadPhone'];
        $zip = $data_msg['Zipcode'];
        $street = $data_msg['street'];
        $city = $data_msg['City'];
        $county = $data_msg['county'];
        $email = $data_msg['LeadEmail'];
        $state = $data_msg['State'];
        $trusted_form = $data_msg['trusted_form'];
        $lead_source_text = $data_msg['lead_source'];
        $google_ts = $data_msg['google_ts'];
        $lp_campaign_id = $crm_details['Leads_PediaDetails']['IP_Campaign_ID'];
        $lp_campaign_key = $crm_details['Leads_PediaDetails']['campine_key'];
        $lp_url = $crm_details['Leads_PediaDetails']['leads_pedia_url'];

        $LeadId = $data_msg['LeadId'];
        $IPAddress = $data_msg['IPAddress'];
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $UserAgent = $data_msg['UserAgent'];
        $OriginalURL = $data_msg['OriginalURL'];
        $OriginalURL2 = $data_msg['OriginalURL2'];
        $SessionLength = $data_msg['SessionLength'];
        $lead_browser_name = $data_msg['browser_name'];
        $tcpa_compliant = $data_msg['tcpa_compliant'];
        $TCPAText = $data_msg['TCPAText'];
        $lead_source_text = $data_msg['lead_source'];

        switch ($crm_details['service_id']){
            case 2:
                //Solar Service
                switch ($crm_details['buyer_id']) {
                    case 20:
                        //Smart Home Solution
                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&trusted_form_cert_id=$trusted_form";

                        $monthly_electric_bill = $crm_details['data']['monthly_electric_bill'];
                        $utility_provider = $crm_details['data']['utility_provider'];

                        switch ($crm_details['data']['roof_shade']){
                            case "Full Sun":
                                $roof_shade = "No Shade";
                                break;
                            case "Mostly Shaded":
                                $roof_shade = "Full Shade";
                                break;
                            case "Partial Shade":
                                $roof_shade = "Partial Shade";
                                break;
                            default:
                                $roof_shade = "Not Sure";
                        }

                        $url_api .= "&monthly_electric_bill=$monthly_electric_bill&utility_provider=$utility_provider&roof_type=$roof_shade";
                        break;
                    case 22:
                        //Lead Cactus
                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&email_address=$email&xxTrustedFormCertUrl=$trusted_form&jornaya_lead_id=$LeadId&ip_address=$IPAddress&lp_s1=$lead_source_text&lp_s2=$lead_source_text&lp_s3=$lead_source_text";

                        switch ($crm_details['data']['monthly_electric_bill']){
                            case '$0 - $50':
                                $average_bill = '$0-50';
                                break;
                            case '$51 - $100':
                                $average_bill = '$51-100';
                                break;
                            case '$101 - $150':
                                $average_bill = '$101-150';
                                break;
                            case '$151 - $200':
                                $average_bill = '$151-200';
                                break;
                            case '$201 - $300':
                                $average_bill = '$201-300';
                                break;
                            case '$301 - $400':
                                $average_bill = '$301-400';
                                break;
                            case '$401 - $500':
                                $average_bill = '$401-500';
                                break;
                            default:
                                $average_bill = '$501-600';
                        }

                        switch ($crm_details['data']['roof_shade']){
                            case "Full Sun":
                                $roof_shade = "No Shade";
                                break;
                            case "Mostly Shaded":
                                $roof_shade = "A Lot Of Shade";
                                break;
                            case "Partial Shade":
                                $roof_shade = "A Little Shade";
                                break;
                            default:
                                $roof_shade = "Uncertain";
                        }

                        $utility_provider = $crm_details['data']['utility_provider'];
                        $property_type = $crm_details['data']['property_type'];
                        $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');

                        $url_api .= "&average_bill=$average_bill&electricUtilityProviderText=$utility_provider&roof_shade=$roof_shade&homeowner=$homeowner";
                        break;
                    case 56:
                        //Trinity Solar 56
                        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
                        if ($listOFCampainDB_type == "Exclusive") {
                            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
                            $Lead_Cost_type = "Exclusive";
                        } else {
                            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
                            $Lead_Cost_type = "Shared";
                        }

                        if ($crm_details['campaign_Type'] == 4) {
                            $appointment_date = "";
                            $appointment_time = "";
                            if ($data_msg['appointment_date'] != "") {
                                $appointment_date = date('Y-m-d', strtotime($data_msg['appointment_date']));
                                $appointment_time = date('H:i:s', strtotime($data_msg['appointment_date']));
                            }

                            $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&lp_caller_id=$number1&appointment_date=$appointment_date&appointment_time=$appointment_time";
                        } else {
                            $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&trusted_form=$trusted_form&ip_address=$IPAddress&lp_cost=$Lead_Cost";
                        }

                        if (!empty($crm_details['data'])) {
                            $monthly_electric_bill = $crm_details['data']['monthly_electric_bill'];

                            switch ($monthly_electric_bill){
                                case '$201 - $300':
                                    $average_bill = '$201-300';
                                    break;
                                case '$301 - $400':
                                    $average_bill = '$301-400';
                                    break;
                                case '$401 - $500' || '$500+':
                                    $average_bill = '$400+';
                                    break;
                                default:
                                    $average_bill = '$100-200';
                            }

                            switch ($crm_details['data']['roof_shade']){
                                case "Full Sun":
                                    $roof_shade = "No Shade";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade = "Full Shade";
                                    break;
                                case "Partial Shade":
                                    $roof_shade = "Partial Shade";
                                    break;
                                default:
                                    $roof_shade = "Not Sure of Roof Shade";
                            }

                            $property_type = $crm_details['data']['property_type'];
                            $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');

                            $url_api .= "&average_monthly_utility_bill=$average_bill&roof_shade=$roof_shade&homeowner=$homeowner";

                            //Check if it is warm transfer
                            if ($crm_details['campaign_Type'] == 6) {
                                $url_api .= "&phone_home=$number1&lp_caller_id=$number1";
                            }
                        }
                        break;
                    case 185:
                        //snk media group
                        $monthly_electric_bill = $crm_details['data']['monthly_electric_bill'];
                        switch ($monthly_electric_bill){
                            case '$101 - $150' || '$151 - $200':
                                $average_bill = '$101-200';
                                break;
                            case '$201 - $300':
                                $average_bill = '$201-300';
                                break;
                            case '$301 - $400':
                                $average_bill = '$301-400';
                                break;
                            case '$401 - $500' || '$500+':
                                $average_bill = '+$401';
                                break;
                            default:
                                $average_bill = 'Less than $100';
                        }

                        switch ($crm_details['data']['roof_shade']){
                            case "Full Sun":
                                $roof_shade_data = "No Shade";
                                break;
                            case "Mostly Shaded":
                                $roof_shade_data = "Full Shade";
                                break;
                            case "Partial Shade":
                                $roof_shade_data = "Partial Shade";
                                break;
                            default:
                                $roof_shade_data = "Not Sure";
                        }

                        $property_type = $crm_details['data']['property_type'];
                        $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');

                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&lp_s2=$google_ts&first_name=$first_name&last_name=$last_name&phone_home=$number1&email_address=$email&address=$street&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&roof_shade=$roof_shade_data&homeownership=$homeowner&electricity_bill=$average_bill&utility_provider=Other&jornaya_id=$LeadId&trusted_form_url=$trusted_form&landing_page_url=$OriginalURL&TCPA_Language=$TCPAText&TrustedForm_Yes_No=Yes&JornayaID_Yes_No=Yes&TF_Yes_No=Yes&Jornaya_Yes_No=Yes";
                        break;
                    default:
                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&trusted_form=$trusted_form";
                }
                break;
            case 5:
                //Walk In tubs Service
                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&xxTrustedFormCertUrl=$trusted_form";

                if (!empty($crm_details['data'])) {
                    $reason = $crm_details['data']['reason'];
                    $start_date = $crm_details['data']['start_date'];
                    $homeOwn = $crm_details['data']['homeOwn'];

                    $url_api .= "&homeowner=$homeOwn&start_date=$start_date&reason=$reason";
                }
                break;
            default:
                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&trusted_form=$trusted_form";
        }

        if ($crm_details['is_ping_account'] == 1) {
            if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                $url_api .= "&lp_ping_id=$TransactionId";
            } else {
                return 0;
            }
        }

        if( config('app.env', 'local') == "local" ) {
            //Test Mode
            $url_api .= "&lp_test=1";
        }

        $leadsCustomerCampaign_id = '';
        if( !empty($crm_details['leadsCustomerCampaign_id']) ){
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        }

        $url_api = str_replace(" ","%20",$url_api);

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, '', "GET",  1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        $status = 0;
        if( !empty($result2['result']) ){
            if( $result2['result'] == 'success' || $result2['msg'] == 'Lead Accepted' ){
                $status = 1;
            }
        }
        return $status;
    }

    public function hubspot($data_msg, $crm_details){
        if(empty($crm_details['hubspotDetails'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "hubspotDetails"));
            return 0;
        }

        $crm_api_file = new CrmApi();
        $first_name = $data_msg['first_name'];
        $last_name = $data_msg['last_name'];
        $email_address = $data_msg['LeadEmail'] ;
        $address = $data_msg['street'];
        $zip_code = $data_msg['Zipcode'];
        $city = $data_msg['City'];
        $state = $data_msg['State'];
        $phone_number = $data_msg['LeadPhone'];
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        $app_name =  config('app.name', '');
        $sourceAndBidType = ($listOFCampainDB_type == "Exclusive" ? "Allied Digital Media Exclusive" : "Allied Digital Media");

        $Lead_data_array = "{\r\n  \"properties\": [\r\n
{\r\n      \"property\": \"email\",\r\n          \"value\": \"$email_address\"\r\n},
\r\n
{\r\n      \"property\": \"firstname\",\r\n      \"value\": \"$first_name\"\r\n},
\r\n
{\r\n      \"property\": \"lastname\",\r\n       \"value\": \"$last_name\"\r\n},
\r\n
{\r\n      \"property\": \"website\",\r\n        \"value\": \"$app_name\"\r\n},
\r\n
{\r\n      \"property\": \"company\",\r\n        \"value\": \"\"\r\n},
\r\n
{\r\n      \"property\": \"lead_source\",\r\n    \"value\": \"$sourceAndBidType\"\r\n},
\r\n
{\r\n      \"property\": \"phone\",\r\n          \"value\": \"$phone_number\"\r\n},
\r\n
{\r\n      \"property\": \"address\",\r\n        \"value\": \"$address\"\r\n},
\r\n
{\r\n      \"property\": \"city\",\r\n           \"value\": \"$city\"\r\n},
\r\n
{\r\n      \"property\": \"state\",\r\n          \"value\": \"$state\"\r\n},
\r\n
{\r\n      \"property\": \"zip\",\r\n            \"value\": \"$zip_code\"\r\n}
\r\n  ]\r\n}";

        $url_api = "https://api.hubapi.com/contacts/v1/contact";
        if($crm_details['hubspotDetails']['key_type'] == 0){
            $url_api .= "?hapikey=" . $crm_details['hubspotDetails']['Api_Key'];
        }

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        if($crm_details['hubspotDetails']['key_type'] == 1){
            $httpheader[] = "Authorization: Bearer " . $crm_details['hubspotDetails']['Api_Key'];
        }

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if(!empty($result2['status'])){
            if($result2['status'] == "error"){
                return 0;
            }
        }
        return 1;
    }

    public function Pipdrive($data_msg, $crm_details){
        if(empty($crm_details['pipedriveDetails'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "pipedriveDetails"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $Lead_data_array = array(
            'api_token' => $crm_details['pipedriveDetails']['api_token'],
            'persons_domain' => $crm_details['pipedriveDetails']['persons_domain'],
            'persons' => $crm_details['pipedriveDetails']['persons'],
            'deals_leads' => $crm_details['pipedriveDetails']['deals_leads'],
            'title' => ucfirst($crm_details['service_campaign_name']),
            'first_name'=> $data_msg['first_name'],
            'last_name'=> $data_msg['last_name'],
            'email_address' => $data_msg['LeadEmail'],
            'phone_number' => $data_msg['LeadPhone'],
        );

        $first_name = ucwords($data_msg['first_name']);
        $last_name = ucwords($data_msg['last_name']);
        $mail = $data_msg['LeadEmail'] ;
        $phone = $data_msg['LeadPhone'];

        $street = ucwords(trim($data_msg['street']));
        $city = ucwords(trim($data_msg['City']));
        $state = ucwords(trim($data_msg['State']));
        $state_code = trim($data_msg['state_code']);
        $Zipcode = trim($data_msg['Zipcode']);

        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        if ($listOFCampainDB_type == "Exclusive") {
            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Exclusive";
        } else {
            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Shared";
        }

        // ================================== //
        // FORM INPUT CAPTURE
        // ================================== //
        $name = $first_name . ' ' . $last_name;

        // ================================== //
        // API token
        // ================================== //
        $api_token = $Lead_data_array['api_token'];
        $person_domain = $Lead_data_array['persons_domain'];
        $is_person = $Lead_data_array['persons'];
        $is_deals = $Lead_data_array['deals_leads'];

        // ================================== //
        // PERSON'S API Key fields' Array values
        // ================================== //
        if( $is_person == 1 ){
            $Lead_data_array = array(
                'name'  => $name,
                'email' => $mail,
                'phone' => $phone,
            );

            switch ($crm_details['buyer_id']){
                case 194:
                    //	Kilowatt Solar 194
                    $Lead_data_array['f5538955510ae7048610f4225eb19bf5b7b6deaf'] = "State: $state, City: $city, Street: $street";
                    break;
                case 420:
                    //Sun Badger Solar 420
                    $Lead_data_array['27f0b6de8997d6b27bd1ac692905fab8f62f7173'] = "$street, $city, $state_code $Zipcode, US";//address
                    $Lead_data_array['postal_address'] = $street;//address
                    break;
                case 800:
                    //PEP Solar	800
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);

                    switch ($roof_shade){
                        case "Mostly Shaded":
                            $roof_shade_data = "Shady";
                            break;
                        case "Partial Sun":
                            $roof_shade_data = "Some Shade";
                            break;
                        default:
                            $roof_shade_data = "Full Sun";
                    }

                    $Lead_data_array['Utility'] = $utility_provider;
                    $Lead_data_array['Energy-Type'] = "All Electric";
                    $Lead_data_array['Roof-Radiance'] = $roof_shade_data;
                    $Lead_data_array['Monthly-Electric-Bill'] = $monthly_electric_bill;
                    //$Lead_data_array['Address'] = "State: $state, City: $city, Street: $street";
                    break;
                case 907:
                    //Allied Veterans Roofing, Solar & HVAC Company	907
                    $Lead_data_array['4741984edeabbcb415812caff055538c87d9da5e'] = $street;//street address
                    $Lead_data_array['354a1c2b7b6ab4398a1f436827172af003a6462b'] = "ADM";//Source
                    break;
            }

            // ================================== //
            // API PERSONS domain
            // ================================== //
            $url_api = 'https://'.$person_domain.'.pipedrive.com/v1/persons?api_token=' . $api_token;

            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];

            $httpheader = array(
                "cache-control: no-cache",
                "Accept: application/json",
                "content-type: application/json"
            );

            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
            $result_data = json_decode($result, true);
            if (!empty($result_data['success'])) {
                if ($result_data['success'] == true) {
                    if ($is_deals == 1) {
                        $person_id = $result_data['data']['id'];

                        $Lead_data_array2 = array(
                            'title' => $name,
                            'person_id' => $person_id,
			    'value' => ucfirst($crm_details['service_campaign_name'])
                        );

                        switch ($crm_details['buyer_id']){
                            case 194:
                                //Kilowatt Solar 194
                                $Lead_data_array2['27f936309c7f3685b070f2eba7d0915eb852dea6'] = 'Allied';
                                break;
                            case 420:
                                //Sun Badger Solar 420
                                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                                $utility_provider = trim($crm_details['data']['utility_provider']);
                                $roof_shade = trim($crm_details['data']['roof_shade']);
                                $property_type = trim($crm_details['data']['property_type']);

                                $roof_shade_data = ($roof_shade == "Mostly Shaded" ? "Too Shaded" : "Viable Roof");
                                $property_type_data = ($property_type == "Rented" ? "No" : "Yes");

                                $Lead_data_array2['e6bea47ff543ee018be42d422f23b033b3b08288'] = $Lead_Cost;//Lead Cost
                                $Lead_data_array2['ba318b22118ccd30ad68686b48dbee75888d281f'] = $Lead_Cost_type;//Lead Cost Type
                                $Lead_data_array2['4b9fab40353950422a2cd9c0366b1c0ec8e9b20c'] = "Allied";//Lead Source
                                $Lead_data_array2['3cb8f2ea27526c0022f6bc80ebb5edec3215db5e'] = "Allied";//Lead Source (invisible)
                                $Lead_data_array2['a0ba88a0d8c8056099ffc57675c739c3eecd3be5'] = $mail;//email
                                $Lead_data_array2['ee655e39bda3ec744236818a14651e75c0bd39f7'] = $phone;//phone number
                                $Lead_data_array2['697dd2574bc090e7aa644d055c0d362d09f63a35'] = $utility_provider;//utility company
                                $Lead_data_array2['7d113de82a481399ff9ee847e5ded557572cc7be'] = "$street, $city, $state_code $Zipcode, US";//address
                                $Lead_data_array2['0a04a9c455bbfdeb6779d8dd6a890c9a31947889'] = $monthly_electric_bill;//Average Monthly Cost
                                $Lead_data_array2['5322713769c00dfbca7c5a532ffaf66a45755751'] = $property_type_data;//Homeowner
                                $Lead_data_array2['4d7f1ee1b2de6f36893ab9c90f98207da05b0b74'] = $roof_shade_data;//Roof Shaded
                                break;
                            case 800:
                                //PEP Solar	800
                                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                                $utility_provider = trim($crm_details['data']['utility_provider']);
                                $roof_shade = trim($crm_details['data']['roof_shade']);
                                $property_type = trim($crm_details['data']['property_type']);

                                switch ($roof_shade){
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Shady";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Some Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Full Sun";
                                }

                                $Lead_data_array2['Utility'] = $utility_provider;
                                $Lead_data_array2['Energy-Type'] = "All Electric";
                                $Lead_data_array2['Roof-Radiance'] = $roof_shade_data;
                                $Lead_data_array2['Monthly-Electric-Bill'] = $monthly_electric_bill;
                                //$Lead_data_array2['Address'] = "State: $state, City: $city, Street: $street";
                                break;
                        }

                        $url_api2 = 'https://' . $person_domain . '.pipedrive.com/api/v1/deals?api_token=' . $api_token;

                        $result2 = $crm_api_file->api_send_data($url_api2, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array2), "POST", 1, $crm_details['campaign_id']);
                        $result_data2 = json_decode($result2, true);
                        if (!empty($result_data2['success'])) {
                            if ($result_data2['success'] == true) {
                                return 1;
                            }
                        }
                    } else {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }

    public function Jangle($data_msg, $crm_details){
        if(empty($crm_details['JangleDetails'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "JangleDetails"));
            return 0;
        }
        try {
            $crm_api_file = new CrmApi();

            $Lead_data_array = array(
                'Authorization' => $crm_details['JangleDetails']['Authorization'],
                'PostUrl' => $crm_details['JangleDetails']['PostUrl'],
                'title' => ucfirst($crm_details['service_campaign_name']),
                'first_name' => $data_msg['first_name'],
                'last_name' => $data_msg['last_name'],
                'email_address' => $data_msg['LeadEmail'],
                'phone_number' => $data_msg['LeadPhone'],
            );

            $Authorization = $Lead_data_array['Authorization'];




            switch ($crm_details['buyer_id']) {
                case 375:
                    //Blue ink digital	375
                    if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
                        //Test Mode
                        $url_api = "https://test-api.gateway.blueinkanalytics.com/v2/home_improvement/post";
                    } else {
                        $url_api = $Lead_data_array['PostUrl'];
                    }
                    break;
                default:
                    $url_api = $Lead_data_array['PostUrl'];
            }

            $first_name = trim($data_msg['first_name']);
            $last_name = trim($data_msg['last_name']);
            $number1 = trim($data_msg['LeadPhone']);
            $zip = trim($data_msg['Zipcode']);
            $street = trim($data_msg['street']);
            $city = trim($data_msg['City']);
            $email = trim($data_msg['LeadEmail']);
            $state = trim($data_msg['State']);
            $trusted_form = trim($data_msg['trusted_form']);
            $statename_code = trim($data_msg['state_code']);
            $request_date = trim(date('Y-m-d H:i:s'));
            //Lead Details Browser
            $UserAgent = $data_msg['UserAgent'];
            $OriginalURL = $data_msg['OriginalURL'];
            $OriginalURL2 = $data_msg['OriginalURL2'];
            $SessionLength = $data_msg['SessionLength'];
            $IPAddress = $data_msg['IPAddress'];
            $LeadId = $data_msg['LeadId'];
            $lead_browser_name = $data_msg['browser_name'];
            $TCPAText = $data_msg['TCPAText'];
            $lead_source_text = $data_msg['lead_source'];
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
            $tcpa_compliant = $data_msg['tcpa_compliant'];
            if( $tcpa_compliant == 1 ){
                $tcpa_compliant2 = "Yes";
                $tcpa_compliant3 = "yes";
                $tcpa_compliant4 = "true";
                $tcpa_compliant5 = 1;
            } else {
                $tcpa_compliant2 = "No";
                $tcpa_compliant3 = "no";
                $tcpa_compliant4 = "false";
                $tcpa_compliant5 = 2;
            }

            $httpheader = array(
                "Authorization: Token $Authorization",
                "content-type: application/json"
            );

            $subid = $lead_source_text;

            $Lead_data_array = array(
                "meta" => array(
                    "originally_created" => $request_date,
                    "source_id" => $subid,
                    "offer_id" => $leadsCustomerCampaign_id,
                    "lead_id_code" => $LeadId,
                    "trusted_form_cert_url" => $trusted_form,
                    "user_agent" => $UserAgent,
                    "landing_page_url" => $OriginalURL2,
                    "tcpa_compliant" => $tcpa_compliant4,
                    "tcpa_consent_text" => $TCPAText
                ),
                "contact" => array(
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "phone" => $number1,
                    "address" => $street,
                    "city" => $city,
                    "state" => $statename_code,
                    "zip_code" => $zip,
                    "ip_address" => $IPAddress,
                    "phone_last_four" => "",
                )
            );

            if ($crm_details['is_ping_account'] == 1) {
                if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                    $TransactionId = $data_msg['ping_post_data']['TransactionId'];

                    $Lead_data_array['auth_code'] = $TransactionId;
                } else {
                    return 0;
                }
            }

            $best_call_time = "Any time";
            if ($crm_details['campaign_Type'] == 4 && $data_msg['appointment_date'] != "") {
                $best_call_time = date('m/d/Y h:i A', strtotime($data_msg['appointment_date'])) . " EST";
            }

            switch ($crm_details['service_id']){
                case 1:
                    //windows
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $number_of_windows = trim($crm_details['data']['number_of_window']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Need repair services at this time" : "Interested in replacement windows");

                    switch ($number_of_windows){
                        case '1':
                            $number_windows = "1";
                            break;
                        case '2':
                            $number_windows = "2";
                            break;
                        case "3-5":
                            $number_windows = "5";
                            break;
                        case "6-9":
                            $number_windows = "7";
                            break;
                        default:
                            $number_windows = "10";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "windows" => array(
                            "project_type" => $project_nature_data,
                            "num_windows" => $number_windows
                        ),
                    );
                    break;
                case 2:
                    //solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $SecurityUsage = ($property_type == "Owned" ? "true" : "false");

                    switch ($monthly_electric_bill){
                        case '$0 - $50':
                            $monthly_bill = 50;
                            break;
                        case "$51 - $100":
                            $monthly_bill = 100;
                            break;
                        case "$101 - $150":
                            $monthly_bill = 150;
                            break;
                        case "$151 - $200":
                            $monthly_bill = 200;
                            break;
                        case "$201 - $300":
                            $monthly_bill = 300;
                            break;
                        case "$301 - $400":
                            $monthly_bill = 400;
                            break;
                        case "$401 - $500":
                            $monthly_bill = 500;
                            break;
                        default:
                            $monthly_bill = 600;
                    }

                    switch ($roof_shade){
                        case "Full Sun":
                            $roof_shade_data = "No Shade";
                            break;
                        case "Mostly Shaded":
                            $roof_shade_data = "Full Shade";
                            break;
                        case "Partial Sun":
                            $roof_shade_data = "Some Shade";
                            break;
                        default:
                            $roof_shade_data = "Not Sure";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => "1-3 months",
                        "monthly_electric_bill" => $monthly_bill,
                        "utility_provider" => $utility_provider,
                        "roof_shade" => $roof_shade_data,
                        "property_type" => "Single Family",
                        "credit_rating" => "Excellent"
                    );
                    break;
                case 3:
                    //Home Security
                    $Installation_Preferences = trim($crm_details['data']['Installation_Preferences']);
                    $lead_have_item_before_it = trim($crm_details['data']['lead_have_item_before_it']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($property_type){
                        case "Business":
                            $Usage = "Commercial";
                            $SecurityUsage = "false";
                            break;
                        case "Rented":
                            $Usage = "residential";
                            $SecurityUsage = "false";
                            break;
                        default:
                            $Usage = "residential";
                            $SecurityUsage = "true";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "home_security" => array(
                            "building_type" => "Other",
                            "usage" => $Usage
                        )
                    );
                    break;
                case 4:
                    //Flooring
                    $Type_OfFlooring = trim($crm_details['data']['flooring_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair Existing Flooring" ? "Repair" : "Installation");

                    switch ($Type_OfFlooring){
                        case "Vinyl Linoleum Flooring":
                            $Type_OfFlooring_data = "Vinyl";
                            break;
                        case "Tile Flooring":
                            $Type_OfFlooring_data = "Tile";
                            break;
                        case "Hardwood Flooring":
                            $Type_OfFlooring_data = "Hardwood";
                            break;
                        case "Laminate Flooring":
                            $Type_OfFlooring_data = "Laminate";
                            break;
                        default:
                            $Type_OfFlooring_data = "Carpet";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "flooring" => array(
                            "flooring_type" => $Type_OfFlooring_data,
                            "inquiry_type" => $project_nature_data
                        )
                    );
                    break;
                case 6:
                    //roofing
                    $roof_type = trim($crm_details['data']['roof_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($roof_type){
                        case "Asphalt Roofing":
                            $roof_type_data = "Asphalt shingle";
                            break;
                        case "Wood Shake/Composite Roofing":
                            $roof_type_data = "Cedar shake";
                            break;
                        case "Metal Roofing":
                            $roof_type_data = "Metal";
                            break;
                        case "Natural Slate Roofing":
                            $roof_type_data = "Natural state";
                            break;
                        default:
                            $roof_type_data = "Tile";
                    }

                    switch ($project_nature){
                        case "Install roof on new construction":
                            $project_nature_data = "New roof for new home";
                            break;
                        case "Completely replace roof":
                            $project_nature_data = "New roof for an existing home";
                            break;
                        default:
                            $project_nature_data = "Repair";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => "true",
                        "purchase_time_frame" => $start_time_data,
                        "roof" => array(
                            "project_type" => $project_nature_data,
                            "roofing_type" => $roof_type_data
                        )
                    );
                    break;
                case 7:
                    //Home Siding
                    $type_of_siding = trim($crm_details['data']['type_of_siding']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair section(s) of siding" ? "Siding repair" : "Replace siding");

                    switch($type_of_siding){
                        case "Vinyl Siding":
                            $type_of_siding_data = "Vinyl";
                            break;
                        case "Brickface Siding" || "Stoneface Siding":
                            $type_of_siding_data = "Brick or stone";
                            break;
                        case "Composite wood Siding":
                            $type_of_siding_data = "Wood";
                            break;
                        default:
                            $type_of_siding_data = "Other";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "siding" => array(
                            "siding_type" => $type_of_siding_data,
                            "project_type" => $project_nature_data
                        )
                    );
                    break;
                case 8:
                    //Kitchen
                    $service_kitchen = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $service_kitchen_data = ($service_kitchen == "Full Kitchen Remodeling" ? "Floor plan" : "Cabinets");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "kitchen" => array(
                            "project_type" => $service_kitchen_data
                        )
                    );
                    break;
                case 9:
                    //Bathroom
                    $bathroom_type_name = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($bathroom_type_name){
                        case "Shower / Bath" || "Sinks" || "Toilet":
                            $bathroom_type_name_data = "Bath, sinks";
                            break;
                        default:
                            $bathroom_type_name_data = "Full bathroom";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "bathroom" => array(
                            "project_type" => $bathroom_type_name_data
                        )
                    );
                    break;
                case 10:
                    //Stairs
                    $stairs_type = trim($crm_details['data']['stairs_type']);
                    $reason = trim($crm_details['data']['reason']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $stairs_type_data = ($stairs_type == "Straight" ? "Straight staircase" : "Curved staircase");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "stair_lift" => array(
                            "stair_type" => $stairs_type_data,
                            "project_type" => "Private",
                            "carry_weight" => 200,
                            "num_stairs" => 25,
                            "num_floors" => 2,
                            "outdoor_stairs" => false,
                            "stair_material" => "Concrete"
                        )
                    );
                    break;
                case 11:
                    //Furnace
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New unit installed");

                    switch($type_of_heating){
                        case "Natural Gas":
                            $type_of_heating_data = "Gas furnace";
                            break;
                        case "Oil":
                            $type_of_heating_data = "Oil furnace";
                            break;
                        case "Propane Gas":
                            $type_of_heating_data = "Propane furnace";
                            break;
                        default:
                            $type_of_heating_data = "Electric furnace";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "hvac" => array(
                            "project_type" => $project_nature_data,
                            "air_type" => "Heating and cooling",
                            "system_type" => $type_of_heating_data
                        )
                    );
                    break;
                case 12:
                    //Boiler
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New unit installed");

                    switch($type_of_heating){
                        case "Natural Gas":
                            $type_of_heating_data = "Gas boiler";
                            break;
                        case "Oil":
                            $type_of_heating_data = "Oil boiler";
                            break;
                        case "Propane Gas":
                            $type_of_heating_data = "Propane boiler";
                            break;
                        default:
                            $type_of_heating_data = "Electric boiler";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "hvac" => array(
                            "project_type" => $project_nature_data,
                            "air_type" => "Heating and cooling",
                            "system_type" => $type_of_heating_data
                        )
                    );
                    break;
                case 13:
                    //Central A/C
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New unit installed");
                    $type_of_heating_data = "Central AC";

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "hvac" => array(
                            "project_type" => $project_nature_data,
                            "air_type" => "Heating and cooling",
                            "system_type" => $type_of_heating_data
                        )
                    );
                    break;
                case 14:
                    //Cabinet
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Cabinet Refacing" ? "Reface existing cabinets" : "Install new custom cabinets");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "cabinets" => array(
                            "project_type" => $project_nature_data,
                            "location_in_house" => "Other",
                            "current_materials" => "Wood",
                            "reface" => "Veneer"
                        )
                    );
                    break;
                case 15:
                    //Plumbing
                    $services = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($services){
                        case "Storm Drain Cleaning";
                            $services_data = "Drain cleaning";
                            break;
                        case "Water Heater Services":
                            $services_data = "Install or repair water heater";
                            break;
                        case "Septic Systems":
                            $services_data = "Septic install or replace";
                            break;
                        case "Drain/ Sewer Services":
                            $services_data = "Sewer main";
                            break;
                        case "Well Pump Services":
                            $services_data = "Well pumps";
                            break;
                        default:
                            $services_data = "Water main";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "plumbing" => array(
                            "project_type" => "Install",
                            "service_type" =>  $services_data
                        )
                    );
                    break;
                case 17:
                    //sunrooms
                    $services = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $SecurityUsage = "true";
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "sunrooms" => array(
                            "num_rooms" => 3,
                            "length" => 10,
                            "width" => 13
                        )
                    );
                    break;
                case 18:
                    //Handyman
                    $handyman_ammount_name = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "handy_man" => array(
                            "service_type" => $handyman_ammount_name
                        )
                    );
                    break;
                case 20:
                    //Doors
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $door_type = trim($crm_details['data']['door_type']);
                    $number_of_door = trim($crm_details['data']['number_of_door']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New installation");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "doors" => array(
                            "project_type" => $project_nature_data,
                            "material" => "Other",
                            "pre_hung" => true
                        )
                    );
                    break;
                case 21:
                    //Gutter
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $service = trim($crm_details['data']['service']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "gutters" => array(
                            "protection" => false
                        )
                    );
                    break;
                case 23:
                    //Painting
                    $service_type = trim($crm_details['data']['service']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($service_type){
                        case "Exterior Home or Structure - Paint or Stain":
                            $service_type_data = "Exterior Painting";
                            break;
                        case "Interior Home or Surfaces - Paint or Stain":
                            $service_type_data = "Interior Painting";
                            break;
                        case "Specialty Painting - Textures":
                            $service_type_data = "Specialty Painting - Textures";
                            break;
                        default:
                            $service_type_data = "Other";
                    }

                    $Lead_data_array['data'] = array(
                        "best_call_time" => $best_call_time,
                        "own_property" => $SecurityUsage,
                        "purchase_time_frame" => $start_time_data,
                        "painting" => array(
                            "project_type" => $service_type_data
                        )
                    );
                    break;
            }

            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);

            $result2 = json_decode($result, true);

            if (!empty($result2)) {
                if (!empty($result2['status'])) {
                    if ($result2['status'] == "success") {
                        return 1;
                    }
                }
            }

        } catch (Exception $e) {

        }
    }

    public function leadPerfectionCrm($data_msg, $crm_details){
        //leadperfection
        if(empty($crm_details['leadPerfectionCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "leadPerfectionCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $statename_code = trim($data_msg['state_code']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];

        $phone1type = 1;
        $phone2type = 0;
        $phone3type = 0;
        $LogNumber = 1;
        $sentto = "Leadperfection";
        $srs_id = $crm_details['leadPerfectionCrm']['lead_perfection_srs_id'];
        $pro_id = $crm_details['leadPerfectionCrm']['lead_perfection_pro_id'];
        $pro_desc = $crm_details['leadPerfectionCrm']['lead_perfection_pro_desc'];
        $sender_data = $crm_details['leadPerfectionCrm']['lead_perfection_sender'];
        $url_link = $crm_details['leadPerfectionCrm']['lead_perfection_url'];

        $sender = config('app.name', '');
        if( !empty($sender_data) ){
            $sender = $sender_data;
        }

        $url_api = "$url_link?firstname=$first_name&lastname=$last_name&address1=$street&city=$city&state=$statename_code&zip=$zip&phone1type=$phone1type&phone1=$number1&phone2type=$phone2type&phone3type=$phone3type&email=$email&LogNumber=$LogNumber&sender=$sender&sentto=$sentto&srs_id=$srs_id";
        if( !empty($pro_id) ){
            $url_api .= "&pro_id=$pro_id&productid=$pro_id";
        }
        if( !empty($pro_desc) ){
            $url_api .= "&proddescr=$pro_desc";
        }

        $httpheader = array(
            "content-type: application/json"
        );
        $url_api = str_replace(" ","%20",$url_api);
        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . strtolower($result), 'ok') == true) {
            return 1;
        }
        return 0;
    }

    public function Improveit360Crm($data_msg, $crm_details){
        //Improveit360Crm
        if(empty($crm_details['Improveit360Crm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "Improveit360Crm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $statename_code = trim($data_msg['state_code']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $phone1type = "Home";
        $date = date('Y-m-d');
        $trusted_form = $data_msg['trusted_form'];
        $campaignID = $crm_details['campaign_id'];

        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        if ($listOFCampainDB_type == "Exclusive") {
            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Exclusive";
        } else {
            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Shared";
        }

        $source = $crm_details['Improveit360Crm']['improveit360_source'];
        $url_link = $crm_details['Improveit360Crm']['improveit360_url'];
        $market_segment = $crm_details['Improveit360Crm']['market_segment'];
        $source_type_imp = $crm_details['Improveit360Crm']['source_type'];
        $project_imp = $crm_details['Improveit360Crm']['project'];

        $source_type = "Websites";
        if( !empty($source_type_imp) ){
            $source_type = $source_type_imp;
        }

        $project = $crm_details['service_campaign_name'];
        if( !empty($project_imp) ){
            $project = $project_imp;
        }

        $url_api = "$url_link?FirstName=$first_name&LastName=$last_name&StreetAddress=$street&City=$city&State=$statename_code&Zip=$zip&Email=$email&Phone1=$number1&Phone1Type=$phone1type&Product=$project&Date=$date&Source=$source&SourceType=$source_type";

        if( !empty($market_segment) ){
            $url_api .= "&i360__Market_Segment_Editable__c=$market_segment&MarketSegment=$market_segment";
        }

        switch ($crm_details['buyer_id']) {
            case 918:
            case 919:
            case 920:
            case 926:
                // LEI Home Enhancements – Columbus 918
                // LEI Home Enhancements – Fort Worth 919
                // LEI Home Enhancements – Chicago, Indianapolis, Cincinnati 920
                // LEI Home Enhancements – Richmond, Raleigh, Virginia Beach, Washington DC, Philadelphia 926

                $appointment_date = "";
                $appointment_time = "";
                if ($crm_details['campaign_Type'] == 4 && $data_msg['appointment_date'] != "") {
                    $appointment_date = date('m/d/Y', strtotime($data_msg['appointment_date']));
                    $appointment_time = date('h:i A', strtotime($data_msg['appointment_date']));
                }
                $url_api .= "&RequestAppontmentTime=$appointment_time&RequestAppontmentDate=$appointment_date";
                break;
            case 667:
                // 667 Wintek USA
                $Lead_Cost = '$'.$Lead_Cost;
                $createdDate = date('m/d/Y');

                $url_api.="&i360__Vendor__c=0018Z00002XgWLhQAN&Provider__c=Allied Digital Media&Provider_Database__c=Allied Digital Media - New Internet Leads - Exclusive&Order_ID__c=112135&Order_Cost__c=$Lead_Cost&Order_Purchase_Date__c=$createdDate&Lead_Manager__c=a0j1U000000AlRN&i360__External_Campaign_ID__c=$campaignID&Provider_UID__c=$trusted_form";

                switch ($crm_details['service_id']){
                    case 1:
                        // Windows
                        $number_of_windows = trim($crm_details['data']['number_of_window']);
                        $start_time = trim($crm_details['data']['start_time']);
                        $project_nature = trim($crm_details['data']['project_nature']);

                        $serviceDescription = ($project_nature == 'Install' ? 'Install' : 'Replacement');

                        switch ($number_of_windows) {
                            case '3-5':
                                $number_windows = "3_5";
                                break;
                            case '6-9':
                                $number_windows = "6_9";
                                break;
                            default:
                                $number_windows = "10_and_more";
                        }

                        switch ($start_time){
                            case 'Immediately':
                                $start_time_data = "Immediately";
                                break;
                            case "Within 6 months":
                                $start_time_data = "Within 6 months";
                                break;
                            default:
                                $start_time_data = "Not sure";
                        }

                        $url_api.="&Quantity__c=$number_windows&Project_Time_Frame__c=$start_time_data&Service_Requested__c=$serviceDescription";

                        break;
                }
                break;
            case 1067:
                //Clear Choice Home Improvements 1067
                $url_api .= "&i360__External_Campaign_ID__c=$leadsCustomerCampaign_id";
                switch ($crm_details['service_id']) {
                    case 6:
                        //Roofing
                        $roof_type = trim($crm_details['data']['roof_type']);
                        $url_api .= "&Product=$roof_type";
                        break;
                }
                break;
        }

        $httpheader = array(
            "content-type: application/json"
        );

        $url_api = str_replace(" ","%20",$url_api);
        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . strtolower($result), 'success') == true) {
            return 1;
        }
        return 0;
    }

    public function LeadConduitCrm($data_msg, $crm_details){
        //LeadConduitCrm
        if(empty($crm_details['LeadConduitCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "LeadConduitCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $trusted_form = $data_msg['trusted_form'];
        $statename_code = trim($data_msg['state_code']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $UserAgent = $data_msg['UserAgent'];
        $OriginalURL = $data_msg['OriginalURL'];
        $OriginalURL2 = $data_msg['OriginalURL2'];
        $SessionLength = $data_msg['SessionLength'];
        $IPAddress = $data_msg['IPAddress'];
        $LeadId = $data_msg['LeadId'];
        $lead_browser_name = $data_msg['browser_name'];
        $tcpa_compliant = $data_msg['tcpa_compliant'];
        $TCPAText = $data_msg['TCPAText'];
        $lead_source_text = $data_msg['lead_source'];
        $google_ts = $data_msg['google_ts'];
        $lead_source_name = $data_msg['lead_source_name'];
        $campaign_name = $crm_details['campaign_name'];

        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        if ($listOFCampainDB_type == "Exclusive") {
            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Exclusive";
        } else {
            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Shared";
        }

        $url_api = $crm_details['LeadConduitCrm']['post_url'];

        switch ($crm_details['service_campaign_name']){
            case "Window":
                $service_name = "windows";
                break;
            case "Home Siding":
                $service_name = "siding";
                break;
            case "Bathroom":
                $service_name = "bath";
                break;
            default:
                $service_name = strtolower($crm_details['service_campaign_name']);
        }

        $Lead_data_array = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "address_1" => $street,
            "city" => $city,
            "state" => $statename_code,
            "postal_code"=> $zip,
            "trustedform_cert_url" => $trusted_form,
            "phone_1" => $number1,
            "universal_leadid" => $LeadId,
            "product_id_dabel" => $service_name,
            "log_number_dabel" => $service_name,
            "ip_address" => $IPAddress,
            "user_agent" => $UserAgent,
        );

        $httpheader = array(
            "content-type: application/json"
        );

        switch ($crm_details['buyer_id']){
            case 22:
                //Dabella 22
                switch ($crm_details['service_campaign_name']){
                    case "Window":
                        $service_name = "Windows";
                        break;
                    case "Home Siding":
                        $service_name = "Siding";
                        break;
                    case "Bathroom":
                        $service_name = "Bath";
                        break;
                    case "Roofing":
                        $service_name = "Roof";
                        break;
                }

                $Lead_data_array['product_id_dabel'] = $service_name;
                $Lead_data_array['original_source'] = $google_ts;
                $Lead_data_array['price'] = $Lead_Cost;
                break;
            case 24:
                //Harley Exteriors 128
                switch ($crm_details['service_campaign_name']){
                    case "Window":
                        $service_name = "Win";
                        break;
                }
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['Source'] = "srs_2245";
                $Lead_data_array['srs_id'] = "srs_id";
                $Lead_data_array['source_harly'] = "srs_2245";
                break;
        }

        if( config('app.env', 'local') == "local" || !empty($data_msg['is_test']) ) {
            //Test Mode
            $Lead_data_array['is_test'] = true;
            //$Lead_data_array['first_name'] = "SPWRTest";
        }

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . $result, 'success') == true) {
            return 1;
        }
        return 0;
    }

    public function MarketsharpmCrm($data_msg, $crm_details){
        if(empty($crm_details['MarketsharpmCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "MarketsharpmCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $MSM_source = $crm_details['MarketsharpmCrm']['MSM_source'];
        $MSM_coy = $crm_details['MarketsharpmCrm']['MSM_coy'];
        $MSM_formId = $crm_details['MarketsharpmCrm']['MSM_formId'];
        $MSM_leadCaptureName = 'MarketSharp';
        $MSM_firstname = $data_msg['first_name'];
        $MSM_lastname = $data_msg['last_name'];
        $MSM_homephone = $data_msg['LeadPhone'];
        $MSM_zip = $data_msg['Zipcode'];
        $MSM_address1 = $data_msg['street'];
        $MSM_city = $data_msg['City'];
        $MSM_email = $data_msg['LeadEmail'];
        $MSM_state = $data_msg['State'];
        $service_name = $crm_details['service_campaign_name'];
        $MSM_custom_Interests = $service_name;

        $url_api = "https://haaws.marketsharpm.com/LeadCapture/MarketSharp/LeadCapture.ashx?MSM_source=$MSM_source&MSM_coy=$MSM_coy&MSM_formId=$MSM_formId&MSM_leadCaptureName=$MSM_leadCaptureName&MSM_firstname=$MSM_firstname&MSM_lastname=$MSM_lastname&MSM_homephone=$MSM_homephone&MSM_cellphone=$MSM_homephone&MSM_zip=$MSM_zip&MSM_address1=$MSM_address1&MSM_city=$MSM_city&MSM_email=$MSM_email&MSM_state=$MSM_state&MSM_custom_Interests=$MSM_custom_Interests";

        if (config('app.env', 'local') == "local" || strtolower($MSM_firstname) == "test"
            || strtolower($MSM_lastname) == "test" ) {
            //Test
            $url_api .= "&MSM_testform=true";
        }

        $url_api = str_replace(" ", "%20", $url_api);
        $Lead_data_array = array();

        $leadsCustomerCampaign_id = '';
        if (!empty($crm_details['leadsCustomerCampaign_id'])) {
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        }

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function leadPortalCrm($data_msg, $crm_details){
        if(empty($crm_details['leadPortalCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "leadPortalCrm"));
            return 0;
        }
        try {
            $crm_api_file = new CrmApi();

            $Lead_data_array = array(
                'PostUrl' => $crm_details['leadPortalCrm']['api_url'],
                'SRC'=> $crm_details['leadPortalCrm']['SRC'],
                'Key' => $crm_details['leadPortalCrm']['key'],
                'type' => $crm_details['leadPortalCrm']['type']
            );

            $url_api = $Lead_data_array['PostUrl'];

            $first_name = trim($data_msg['first_name']);
            $last_name = trim($data_msg['last_name']);
            $number1 = trim($data_msg['LeadPhone']);
            $zip = trim($data_msg['Zipcode']);
            $street = trim($data_msg['street']);
            $city = trim($data_msg['City']);
            $email = trim($data_msg['LeadEmail']);
            $state = trim($data_msg['State']);
            $trusted_form = trim($data_msg['trusted_form']);
            $statename_code = trim($data_msg['state_code']);
            $UserAgent = $data_msg['UserAgent'];
            $OriginalURL = $data_msg['OriginalURL'];
            $OriginalURL2 = $data_msg['OriginalURL2'];
            $SessionLength = $data_msg['SessionLength'];
            $IPAddress = $data_msg['IPAddress'];
            $LeadId = $data_msg['LeadId'];
            $lead_browser_name = $data_msg['browser_name'];
            $TCPAText = $data_msg['TCPAText'];
            $lead_source_text = $data_msg['lead_source'];
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
            $tcpa_compliant = $data_msg['tcpa_compliant'];
            if( $tcpa_compliant == 1 ){
                $tcpa_compliant2 = "Yes";
                $tcpa_compliant3 = "yes";
                $tcpa_compliant4 = "true";
                $tcpa_compliant5 = 1;
            } else {
                $tcpa_compliant2 = "No";
                $tcpa_compliant3 = "no";
                $tcpa_compliant4 = "false";
                $tcpa_compliant5 = 2;
            }

            $httpheader = array(
                "cache-control: no-cache",
                "Accept: application/json",
                "content-type: application/json"
            );

            $TransactionId = "";
            if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                $TransactionId = $data_msg['ping_post_data']['TransactionId'];
            }

            $Repair_Project = "No";

            switch ($crm_details['service_id']){
                case 1;
                    //windows
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $number_of_windows = trim($crm_details['data']['number_of_window']);

                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                            $Project = (($number_of_windows == 1 || $number_of_windows == 2) ? "Windows-2 or less" : "Windows-3+");
                            break;
                        case 760:
                            //Hello Project USA 760
                            if ($project_nature == "Repair") {
                                $Project = "Windows Repair";
                            } else {
                                $Project = ($number_of_windows == 1 ? "Windows Replace - Single" : "Windows Replace - Multiple");
                            }
                            break;
                        default:
                            if ($project_nature == "Repair") {
                                $Project = "Window Repair";
                            } else {
                                $Project = ($number_of_windows == 1 ? "Window Install - Single" : "Window Install - Multiple");
                            }
                    }
                    break;
                case 2:
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

                    switch ($monthly_electric_bill){
                        case '$0 - $50':
                            $average_bill = '$0-50';
                            break;
                        case '$51 - $100':
                            $average_bill = '$51-100';
                            break;
                        case '$101 - $150':
                            $average_bill = '$101-150';
                            break;
                        case '$151 - $200':
                            $average_bill = '$151-200';
                            break;
                        case '$201 - $300':
                            $average_bill = '$201-300';
                            break;
                        case '$301 - $400':
                            $average_bill = '$301-400';
                            break;
                        case '$401 - $500':
                            $average_bill = '$401-500';
                            break;
                        default:
                            $average_bill = '$501-600';
                    }

                    switch ($roof_shade){
                        case "Full Sun":
                            $roof_shade_data = "No Shade";
                            break;
                        case "Mostly Shaded":
                            $roof_shade_data = "A lot of Shade";
                            break;
                        case "Partial Sun":
                            $roof_shade_data = "A little shade";
                            break;
                        default:
                            $roof_shade_data = "Uncertain";
                    }

                    $property_type_data = ($property_type == "Rented" ? "Rent" : "Own");
                    $solar_electric = ($power_solution == "Solar Water Heating for my Home" ? "No" : "Yes");
                    break;
                case 3:
                    //home Security
                    $Project = "Home Security";
                    break;
                case 4:
                    //Flooring
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair Existing Flooring" ? "Yes" : "No");
                            $Project = "Epoxy Flooring";
                            break;
                        default:
                            $Project = "Flooring";
                    }
                    break;
                case 5:
                    //WALK-IN TUBS
                    $Project = "Walk-in Tub";
                    break;
                case 6:
                    //Roofing
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Repair_Project = ($project_nature == "Repair existing roof" ? "Yes" : "No");
                    $Project = "Roofing";
                    break;
                case 7:
                    //Home Siding
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair section(s) of siding" ? "Yes" : "No");
                            $Project = "Siding";
                            break;
                        default:
                            $Project = "Siding - Install or Replace";
                    }
                    break;
                case 8:
                    //Kitchen
                    $Project = "Kitchen Remodel";
                    break;
                case 9:
                    //Bathroom
                    $Project = "Bathroom Remodel";
                    break;
                case 11:
                    //Furnace
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                            $Project = "HVAC";
                            break;
                        default:
                            $Project = ($project_nature == "Repair" ? "Furnace / Heating System - Repair/Service" : "Furnace / Heating System - Install/Replace");
                    }
                    break;
                case 12:
                    //Boiler
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                            $Project = "HVAC";
                            break;
                        default:
                            $Project = ($project_nature == "Repair" ? "Boiler or Radiator System - Service/Repair" : "Boiler Or Radiator System - Install/Replace");
                    }
                    break;
                case 13:
                    //Central A/C
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                            $Project = "HVAC";
                            break;
                        default:
                            $Project = ($project_nature == "Repair" ? "Central A/C - Repair/Service" : "Central A/C - Install/Replace");
                    }
                    break;
                case 15:
                    //Plumbing
                    $Project = "Plumbing";
                    break;
                case 18:
                    //Handyman
                    $Project = "Handyman";
                    break;
                case 19:
                    //Countertops
                    $Project = "Countertops";
                    break;
                case 21:
                    //Gutter
                    $project_nature = trim($crm_details['data']['project_nature']);
                    switch ($crm_details['buyer_id']){
                        case 553:
                            //conXpros 553
                            $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                            $Project = "Gutters";
                            break;
                        default:
                            $Project = "Gutter Install/Repair";
                    }
                    break;
                case 23:
                    //Painting
                    $Project = "Painting";
                    break;
            }

            if ($crm_details['service_id'] == 2) {
                $type = "26";
                if( !empty($Lead_data_array['type']) ){
                    $type = $Lead_data_array['type'];
                }
                $Lead_data_array_post = array(
                    "Request" => array(
                        "Key" => $Lead_data_array['Key'],
                        "API_Action" => "pingPostLead",
                        "Format" => "JSON",
                        "Mode" => (!empty($TransactionId) ? "post" : "full"),
                        "Return_Best_Price" => "1",
                        "TYPE" => $type,
                        "TCPA_Consent" => $tcpa_compliant2,
                        "TCPA" => $tcpa_compliant2,
                        "TCPA_Language" => $TCPAText,
                        "LeadiD_Token" => $LeadId,
                        "IP_Address" => $IPAddress,
                        "SRC" => $Lead_data_array['SRC'],
                        "Sub_ID" => $lead_source_text,
                        "Pub_ID" => $lead_source_text,
                        "Zip" => $zip,
                        "City" => $city,
                        "State" => $statename_code,
                        "Address" => $street,
                        "Roof_Shade" => $roof_shade_data,
                        "Shade" => $roof_shade_data,
                        "Solar_Electric" => $solar_electric,
                        "Project" => "Electrical_Supplier",
                        "Homeowner" => $property_type_data,
                        "Residence_Ownership" => $property_type_data,
                        "Credit_Rating" => "Good",
                        "Credit" => "Good",
                        "Electric_Bill" => $average_bill,
                        "Monthly_Electric_Bill" => $average_bill,
                        "Electrical_Supplier" => $utility_provider,
                        "Utility_Provider" => $utility_provider,
                        "First_Name" => $first_name,
                        "Last_Name" => $last_name,
                        "Lead_ID" => $TransactionId,
                        "Email" => $email,
                        "Home_Phone" => $number1,
                        "Primary_Phone" => $number1,
                        "Landing_Page" => $OriginalURL2,
                        "User_Agent" => $UserAgent,
                        "Trusted_Form_URL" => $trusted_form,
                        "xxTrustedFormCertUrl" => $trusted_form
                    )
                );
            }
            else if($crm_details['service_id'] == 24){
                $type = ( !empty($Lead_data_array['type']) ? $Lead_data_array['type'] : "33" );
                //Auto Insurance
                $VehicleYear = trim($crm_details['data']['VehicleYear']);
                $VehicleMake = trim($crm_details['data']['VehicleMake']);
                $car_model = trim($crm_details['data']['car_model']);
                $more_than_one_vehicle = trim($crm_details['data']['more_than_one_vehicle']);
                $driversNum = trim($crm_details['data']['driversNum']);
                $birthday = trim($crm_details['data']['birthday']);
                $genders = trim($crm_details['data']['genders']);
                $married = trim($crm_details['data']['married']);
                $license = trim($crm_details['data']['license']);
                $InsuranceCarrier = trim($crm_details['data']['InsuranceCarrier']);
                $driver_experience = trim($crm_details['data']['driver_experience']);
                $number_of_tickets = trim($crm_details['data']['number_of_tickets']);
                $DUI_charges = trim($crm_details['data']['DUI_charges']);
                $SR_22_need = trim($crm_details['data']['SR_22_need']);
                $ownership = trim($crm_details['data']['homeOwn']);

                $Lead_data_array_post = array(
                    "Request" => array(
                        "Key" => $Lead_data_array['Key'],
                        "API_Action" => "pingPostLead",
                        "Format" => "JSON",
                        "Mode" => (!empty($TransactionId) ? "post" : "full"),
                        "Return_Best_Price" => "1",
                        "TYPE" => $type,
                        "IP_Address" => $IPAddress,
                        "SRC" => $Lead_data_array['SRC'],
                        "Sub_ID" => $lead_source_text,
                        "Pub_ID" => $lead_source_text,
                        "Landing_Page" => $OriginalURL2,
                        "User_Agent" => $UserAgent,
                        "TCPA_Consent" => $tcpa_compliant2,
                        "TCPA_Language" => $TCPAText,
                        "LeadiD_Token" => $LeadId,
                        "Trusted_Form_URL" => $trusted_form,
                        "xxTrustedFormCertUrl" => $trusted_form,

                        "Driver_1_State" => $statename_code,
                        "Driver_1_Zip" => $zip,
                        "Vehicle_1_Year" => $VehicleYear,
                        "Vehicle_1_Make" => $VehicleMake,
                        "Vehicle_1_Model" => $car_model,
                        "Vehicle_1_Ownership" => "Owned",
                        "Vehicle_1_Primary_Use" => "Commute To/From Work",
                        "Vehicle_1_Average_One_Way_Mileage" => "8",
                        "Vehicle_1_Annual_Mileage" => "7",
                        "Vehicle_1_Parking" => "Driveway",
                        "Vehicle_1_Average_Days_Per_Week_Used" => "5",
                        "Vehicle_1_Desired_Collision_Coverage" => "No Coverage",
                        "Vehicle_1_Desired_Comprehensive_Coverage" => "No Coverage",
                        "Driver_1_Birthdate" => date("y-m-d", strtotime($birthday)),
                        "Driver_1_Gender" => $genders,
                        "Driver_1_Marital_Status" => ( $married == "Yes" ? "Married" : "Single" ),
                        "Driver_1_Credit_Rating" => "Good",
                        "Driver_1_License_Status" => ( $license == "Yes" ? "Active" : "Expired" ),
                        "Driver_1_Licensed_State" => $statename_code,
                        "Driver_1_Education" => "Unknown",
                        "Driver_1_Occupation" => "Other/Not Listed",
                        "Driver_1_Age_When_First_Licensed" => 18,
                        "Driver_1_Filing_Required" => ( $SR_22_need == "Yes" ? "SR-22" : "None" ),
                        "Driver_1_Current_Residence" => ( $ownership == "Yes" ? "Own" : "Rent" ),
                        "Driver_1_Tickets_Accidents_Claims_Past_3_Years" => ( $number_of_tickets == "0" ? "No" : "Yes" ),
                        "Driver_1_Insured_Past_30_Days" => "Yes",
                        "Driver_1_Bankruptcy_In_Past_5_Years" => "No",
                        "Driver_1_Additional_Drivers" => $driversNum,
                        "Driver_1_Additional_Vehicles" => $more_than_one_vehicle,
                        "Driver_1_Reposessions_In_The_Past_5_Years" => "Unknown",
                        "Driver_1_DUI_DWI_In_The_Past_5_Years" => ( $DUI_charges == "Yes" ? "Yes" : "No" ),

                        "Driver_1_First_Name" => $first_name,
                        "Driver_1_Last_Name" => $last_name,
                        "Lead_ID" => $TransactionId,
                        "Driver_1_Email" => $email,
                        "Driver_1_Daytime_Phone" => $number1,
                        "Driver_1_City" => $city,
                        "Driver_1_Address" => $street
                    )
                );
            }
            else {
                if( !empty($crm_details['data']['homeOwn']) ){
                    $ownership = ($crm_details['data']['homeOwn'] != "Yes" ? "No" : "Yes");
                } else {
                    if( !empty($crm_details['data']['property_type']) ){
                        $ownership = ($crm_details['data']['property_type'] == "Rented"  ? "No" : "Yes");
                    } else {
                        $ownership = "Yes";
                    }
                }

                $type = "18";
                if( !empty($Lead_data_array['type']) ){
                    $type = $Lead_data_array['type'];
                }

                $Lead_data_array_post = array(
                    "Request" => array(
                        "Key" => $Lead_data_array['Key'],
                        "API_Action" => "pingPostLead",
                        "Format" => "JSON",
                        "Mode" => (!empty($TransactionId) ? "post" : "full"),
                        "Return_Best_Price" => "1",
                        "TYPE" => $type,
                        "IP_Address" => $IPAddress,
                        "SRC" => $Lead_data_array['SRC'],
                        "Sub_ID" => $lead_source_text,
                        "Pub_ID" => $lead_source_text,
                        "Zip" => $zip,
                        "City" => $city,
                        "State" => $statename_code,
                        "Address" => $street,
                        "Project" => $Project,
                        "TCPA_Consent" => $tcpa_compliant2,
                        "TCPA_Language" => $TCPAText,
                        "LeadiD_Token" => $LeadId,
                        "Homeowner" => $ownership,
                        "First_Name" => $first_name,
                        "Last_Name" => $last_name,
                        "Lead_ID" => $TransactionId,
                        "Email" => $email,
                        "Home_Phone" => $number1,
                        "Primary_Phone" => $number1,
                        "Landing_Page" => $OriginalURL2,
                        "User_Agent" => $UserAgent,
                        "Trusted_Form_URL" => $trusted_form,
                        "xxTrustedFormCertUrl" => $trusted_form
                    )
                );

                switch($crm_details['buyer_id']){
                    case 553:
                        //conXpros 553
                        $Lead_data_array_post['Request']['Repair'] = $Repair_Project;
                        break;
                    case 760:
                        //760 hello project USA
                        unset($Lead_data_array_post['Request']['Return_Best_Price']);
                        $Lead_data_array_post['Request']['Return_Lead_Cost'] = "1";
                        break;
                }
            }

            if (config('app.env', 'local') == "local") {
                //Test Mode
//                    if ($crm_details['service_id'] != 2) {
//                        $Lead_data_array_post['Request']['Project'] = "Alarm/ security system - Install";
//                    }

                $Lead_data_array_post['Request']['State'] = "AK";
                $Lead_data_array_post['Request']['Zip'] = "99999";
                $Lead_data_array_post['Request']['Test_Lead'] = "1";
            }

            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
            $result2 = json_decode($result, true);
            if (!empty($result2['response'])) {
                if (!empty($result2['response']['status'])) {
                    if ($result2['response']['status'] == "Matched") {
                        $TransactionId = $result2['response']['lead_id'];
                        return $TransactionId;
                    }
                }
            }
            return 0;
        } catch (Exception $e) {

        }
    }

    public function leads_pedia_track($data_msg, $crm_details){
        if(empty($crm_details['leads_pedia_track'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "leads_pedia_track"));
            return 0;
        }
        try {
            $crm_api_file = new CrmApi();

            $lp_campaign_id = $crm_details['leads_pedia_track']['lp_campaign_id'];
            $lp_campaign_key = $crm_details['leads_pedia_track']['lp_campaign_key'];
            $lp_url = $crm_details['leads_pedia_track']['post_url'];

            $httpheader = array(
                "cache-control: no-cache",
                "Accept: application/json",
                "content-type: application/json"
            );

            $first_name = trim($data_msg['first_name']);
            $last_name = trim($data_msg['last_name']);
            $number1 = trim($data_msg['LeadPhone']);
            $zip = trim($data_msg['Zipcode']);
            $street = trim($data_msg['street']);
            $city = trim($data_msg['City']);
            $email = trim($data_msg['LeadEmail']);
            $state = trim($data_msg['State']);
            $statename_code = $data_msg['state_code'];
            $trusted_form = trim($data_msg['trusted_form']);
            $UserAgent = $data_msg['UserAgent'];
            $OriginalURL = $data_msg['OriginalURL'];
            $OriginalURL2 = $data_msg['OriginalURL2'];
            $SessionLength = $data_msg['SessionLength'];
            $IPAddress = $data_msg['IPAddress'];
            $LeadId = $data_msg['LeadId'];
            $lead_browser_name = $data_msg['browser_name'];
            $TCPAText = $data_msg['TCPAText'];
            $lead_source_text = $data_msg['lead_source'];
            $google_ts = $data_msg['google_ts'];
            $timestamp = date('Y-m-d H:i:s');
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
            $tcpa_compliant = $data_msg['tcpa_compliant'];
            $lead_source_name = $data_msg['lead_source_name'];
            if( $tcpa_compliant == 1 ){
                $tcpa_compliant2 = "Yes";
                $tcpa_compliant3 = "yes";
                $tcpa_compliant4 = "true";
                $tcpa_compliant5 = 1;
            } else {
                $tcpa_compliant2 = "No";
                $tcpa_compliant3 = "no";
                $tcpa_compliant4 = "false";
                $tcpa_compliant5 = 2;
            }

            $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=$lead_source_text&lp_response=JSON&city=$city&state=$statename_code&zip_code=$zip&first_name=$first_name&last_name=$last_name&address=$street&phone_home=$number1&phone_cell=$number1&email_address=$email&ip_address=$IPAddress";

            switch ($crm_details['buyer_id']){
                case 25:
                    //pbtp Powered by The People LLC 540
                    $type_data = $crm_details['listOFCampainDB_type'];
                    $source_id = "6461";
                    $source_key = "XP-6461";

                    $url_api .= "&leadtoken=$LeadId&type=$type_data&source_id=$source_id&source_key=$source_key&tcpa=$tcpa_compliant2&tcpa_text=$TCPAText&TrustedForm=$trusted_form&telephone=$number1";
                    break;

            }

            switch ($crm_details['service_id']){
                case 1:
                    //Windows
                    $number_of_windows = trim($crm_details['data']['number_of_window']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            if($project_nature == "Repair") {
                                $type_of_work = "Window Repair";
                            } else {
                                $type_of_work = ($number_of_windows == "1" ? "Window Install - Single" : "Windows Install - Multiple");
                            }

                            $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Windows";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$homeowner&residential_commercia=$residential_commercia&category=$category";
                            break;
                        default:
                            $homeowner = ($ownership == "Yes" ? "yes" : "no");
                            $replace_repair = ($project_nature == "Repair" ? "repair" : "install");
                            $project_timeframe = ($start_time == 'Immediately' ? "immediate" : "over 2 weeks");
                            $property_type = "residential";

                            switch ($number_of_windows){
                                case "3-5":
                                    $number_of_windows_data = 4;
                                    break;
                                case "6-9":
                                    $number_of_windows_data = 7;
                                    break;
                                case "10+":
                                    $number_of_windows_data = 10;
                                    break;
                                default:
                                    $number_of_windows_data = $number_of_windows;
                            }

                            $url_api .= "&no_of_windows=$number_of_windows_data&project_timeframe=$project_timeframe&homeowner=$homeowner&replace_repair=$replace_repair&property_type=$property_type";
                    }
                    break;
                case 2:
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $category = "Solar";
                            $type_of_work = "Install Solar Panels";
                            switch ($property_type){
                                case "Owned":
                                    $home_owner = "Yes";
                                    $residential_commercia = "residential";
                                    break;
                                case "Rented":
                                    $home_owner = "No";
                                    $residential_commercia = "residential";
                                    break;
                                default:
                                    $home_owner = "No";
                                    $residential_commercia = "commercial";
                            }

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 3:
                    //Home Security
                    $Installation_Preferences = trim($crm_details['data']['Installation_Preferences']);
                    $lead_have_item_before_it = trim($crm_details['data']['lead_have_item_before_it']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $category = "Home Security";
                            $type_of_work = "Alarm/Security System-Install";

                            switch ($property_type){
                                case "Owned":
                                    $home_owner = "Yes";
                                    $residential_commercia = "residential";
                                    break;
                                case "Rented":
                                    $home_owner = "No";
                                    $residential_commercia = "residential";
                                    break;
                                default:
                                    $home_owner = "No";
                                    $residential_commercia = "commercial";
                            }

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 4:
                    //Flooring
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $Type_OfFlooring = trim($crm_details['data']['flooring_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Flooring";

                            switch ($Type_OfFlooring){
                                case "Vinyl Linoleum Flooring":
                                    $type_of_work = ($project_nature == "Repair Existing Flooring" ? "Vinyl/Linoleum Floor - Repair" : "Vinyl/Linoleum Floor - Install");
                                    break;
                                case "Tile Flooring":
                                    $type_of_work = ($project_nature == "Repair Existing Flooring" ? "Tile Floor Repair" : "Tile Floor Install");
                                    break;
                                case "Hardwood Flooring":
                                    $type_of_work = "Hardwood Floor - Install";
                                    break;
                                case "Laminate Flooring":
                                    $type_of_work = ($project_nature == "Repair Existing Flooring" ? "Laminate Flooring - Repair" : "Laminate Flooring - Install");
                                    break;
                                default:
                                    $type_of_work = "Flooring";
                            }

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$homeowner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 5:
                    //WALK-IN TUBS
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = "Bathtub/Shower - Install/Replace";
                            $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Bathtub/Shower";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$homeowner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 6:
                    //Roofing
                    $roof_type = trim($crm_details['data']['roof_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $start_time = trim($crm_details['data']['start_time']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            if( $property_type == "Residential" ){
                                $home_owner = "Yes";
                                $residential_commercia = "residential";
                                switch ($roof_type){
                                        case "Wood Shake/Composite Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Composition Shingle Repair" : "Roofing Composition Shingle Install");
                                            break;
                                        case "Metal Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Metal Repair" : "Roofing Metal Install");
                                            break;
                                        case "Natural Slate Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Natural Slate Repair" : "Roofing Natural Slate Install");
                                            break;
                                        case "Tile Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Tile Repair" : "Roofing Tile Install");
                                            break;
                                        default:
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roof Repair" : "New Roof");
                                    }
                            } else {
                                $home_owner = "No";
                                $residential_commercia = "commercial";
                                $type_of_work = "Commercial Roofing";
                            }

                            $category = "Roofing";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                        default:
                            $owner = "yes";
                            $time_frame = ($start_time == 'Immediately' ? "immediate" : "over_2_weeks");
                            $property_typeRoofing = ($property_type == 'Residential' ? "residential" : "commercial");
                            $best_call_time = "Anytime";

                            switch ($roof_type){
                                case "Asphalt Roofing":
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $task_id = 'Roof Install - Asphalt Shingle';
                                            $roofing_material = "Asphalt Shingle";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $task_id = 'Roof Replace - Asphalt Shingle';
                                            $roofing_material = "Asphalt Shingle";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $task_id = 'Roof Repair - Asphalt Shingle';
                                            $roofing_material = "Asphalt Shingle";
                                            $service_type = "repair";
                                    }
                                    break;
                                case "Wood Shake/Composite Roofing":
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $task_id = 'Roof Install-Wood Shake/Comp.';
                                            $roofing_material = "Cedar Shake";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $task_id = 'Roof Replace - Wood Shake';
                                            $roofing_material = "Cedar Shake";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $task_id = 'Roof Repair - Wood Shake/Comp.';
                                            $roofing_material = "Cedar Shake";
                                            $service_type = "repair";
                                    }
                                    break;
                                case  "Metal Roofing":
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $task_id = 'Roof Install - Metal';
                                            $roofing_material = "Metal";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $task_id = 'Roof Replace - Metal';
                                            $roofing_material = "Metal";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $task_id = 'Roof Repair - Metal';
                                            $roofing_material = "Metal";
                                            $service_type = "repair";
                                    }
                                    break;
                                case "Natural Slate Roofing":
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $task_id = 'Roof Install - Natural Slate';
                                            $roofing_material = "Natural Slate";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $task_id = 'Roof Replace - Natural Slate';
                                            $roofing_material = "Natural Slate";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $task_id = 'Roof Repair - Natural Slate';
                                            $roofing_material = "Natural Slate";
                                            $service_type = "repair";
                                    }
                                    break;
                                case "Tile Roofing":
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $task_id = 'Roof Install - Tile';
                                            $roofing_material = "Tar";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $task_id = 'Roof Replace - Tile';
                                            $roofing_material = "Tar";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $task_id = 'Roof Repair - Tile';
                                            $roofing_material = "Tar";
                                            $service_type = "repair";
                                    }
                                    break;
                                default:
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $roofing_material = "Other";
                                            $service_type = "new";
                                            break;
                                        case "Completely replace roof":
                                            $roofing_material = "Other";
                                            $service_type = "replace";
                                            break;
                                        default:
                                            $roofing_material = "Other";
                                            $service_type = "repair";
                                    }
                            }

                            $url_api .= "&service_type=$service_type&time_frame=$time_frame&owner=$owner&property_type=$property_typeRoofing&task_id=$task_id&best_call_time=$best_call_time&roofing_material=$roofing_material";
                    }
                    break;
                case 7:
                    //Home Siding
                    $type_of_siding = trim($crm_details['data']['type_of_siding']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature != "Repair section(s) of siding" ? "Siding Install/Replace" : "Siding Repair");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Home Siding";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 8:
                    //Kitchen
                    $service_kitchen = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $demolishing_walls = trim($crm_details['data']['demolishing_walls']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = "Kitchen Remodel";
                            $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                            $home_owner = "No";
                            $residential_commercia = "residential";
                            $category = "Kitchen";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 9:
                    //bathroom
                    $bathroom_type_name = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = "Bathroom Remodel";
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Bathroom";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 11:
                    //Furnace
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Repair" ? "Furnace/Heating System - Repair/Service" : "Furnace/Heating System - Install/Replace");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Furnace";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 12:
                    //Boiler
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Repair" ? "Boiler Repair/Service" : "Boiler Install/Replace");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Boiler";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 13:
                    //Central A/C
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Repair" ? "Central A/C - Repair/Service" : "Central A/C - Install/Replace");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Central A/C";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 14:
                    //Cabinet
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    switch ($crm_details['service_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Cabinet Refacing" ? "Cabinets/Drawers Repair" : "Cabinets/Drawers Installation");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");

                            $residential_commercia = "residential";
                            $category = "Cabinets";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 16:
                    //Bathtubs
                    $ownership = trim($crm_details['data']['homeOwn']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = "Bathtub/Shower - Install/Replace";
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Bathtub/Shower";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 18:
                    //Handyman
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = "Handyman";
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Handyman";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 20:
                    //Doors
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Repair" ? "Door Repair" : "Door Install");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Doors";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
                case 21:
                    //Gutter
                    $service = trim($crm_details['data']['service']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    switch ($crm_details['buyer_id']){
                        case 25:
                            //pbtp Powered by The People LLC 540
                            $type_of_work = ($project_nature == "Repair" ? "Gutter Repair" : "Gutter Install/Replace");
                            $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                            $residential_commercia = "residential";
                            $category = "Gutters";

                            $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                            break;
                    }
                    break;
            }

            if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
                //Test Mode
                $url_api .= "&lp_test=1";
            }

            if ($crm_details['is_ping_account'] == 1) {
                if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                    $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                    $url_api .= "&lp_ping_id=$TransactionId";
                }
            }

            $url_api = str_replace(" ", "%20", $url_api);

            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
            $result2 = json_decode($result, true);
            $status = 0;
            if( !empty($result2['result']) ){
                if( $result2['result'] == 'success' || $result2['msg'] == 'Lead Accepted' ){
                    $status = 1;
                }
            }
            return $status;
        } catch (Exception $e) {

        }
    }

    public function AcculynxCrm($data_msg, $crm_details){
        //AcculynxCrm
        if(empty($crm_details['AcculynxCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "AcculynxCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $api_key = $crm_details['AcculynxCrm']['api_key'];

        $priority = "Normal";
        if(!empty($crm_details['data']['start_time'])){
            $start_time = trim($crm_details['data']['start_time']);
            if ($start_time == 'Immediately') {
                $priority = "Urgent";
            } else if ($start_time == 'Not Sure') {
                $priority = "Normal";
            } else {
                $priority = "High";
            }
        }

        $jobCategory = "Residential";
        $workType = "New";
        if(!empty($crm_details['data']['project_nature'])){
            $project_nature = trim($crm_details['data']['project_nature']);
            if ($project_nature == "Repair") {
                $jobCategory = "Repair";
                $workType = "Repair";
            }
        }

        $httpheader = array(
            "Authorization: Bearer $api_key",
            "content-type: application/json",
            "Accept: application/json"
        );

        $url_api = "https://api.acculynx.com/api/v1/leads";

        $Lead_data_array = array(
            "firstName" => $first_name,
            "lastName" => $last_name,
            "companyName" => config('app.name', ''),
            "phoneNumber1" => $number1,
            "phoneType1" => "Home",
            "emailAddress" => $email,
            "jobCategory" => $jobCategory,
            "workType" => $workType,
            "street" => $street,
            "city" => $city,
            "state" => $state,
            "zip" => $zip,
            "country" => "US",
            "priority" => $priority
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        $status = 0;
        if( !empty($result2['success']) ){
            if( $result2['success'] == true ){
                $status = 1;
            }
        }
        return $status;
    }

    public function ZohoCrm($data_msg, $crm_details){
        //Zoho CRM
        if(empty($crm_details['ZohoCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "ZohoCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $statename_code = trim($data_msg['state_code']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $service_name = ucfirst($crm_details['service_campaign_name']);
        $user_id = $crm_details['buyer_id'];
        $OriginalURL2 = trim($data_msg['OriginalURL2']);

        switch ($service_name){
            case "Window":
                $service_name = "Windows";
                break;
        }

        $refresh_token = $crm_details['ZohoCrm']['refresh_token'];
        $client_id = $crm_details['ZohoCrm']['client_id'];
        $client_secret = $crm_details['ZohoCrm']['client_secret'];
        $redirect_url = $crm_details['ZohoCrm']['redirect_url'];
        $grant_type = "refresh_token";

        $url_token = "https://accounts.zoho.com/oauth/v2/token";
        $input_token = array(
            'refresh_token' => $refresh_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_url,
            'grant_type' => $grant_type
        );
        $httpheader_token = array();

        $result_token = $crm_api_file->api_send_data($url_token, $httpheader_token, $leadsCustomerCampaign_id, $input_token, "POST", 1, $crm_details['campaign_id']);
        $result_token2 = json_decode($result_token, true);
        $status = 0;
        if( !empty($result_token2['access_token']) ){
            $access_token = $result_token2['access_token'];

            $Company = $crm_details['ZohoCrm']['Lead_Source'];
            $Lead_Source = $crm_details['ZohoCrm']['Lead_Source'];
            $Country = "US";
            $lead_status = "New";
            $url_Leads = "https://www.zohoapis.com/crm/v2/Leads";

            switch ($user_id) {
                case 778:
                    //Green Home Makeover 778
                    $input_leads = '{
    "data": [
        {
            "Company": "' . $Company . '",
            "Last_Name": "' . $last_name . '",
            "First_Name": "' . $first_name . '",
            "Email": "' . $email . '",
            "State": "' . $state . '",
            "Mobile": "+1' . $number1 . '",
            "Street": "' . $street . '",
            "Zip_Code": "' . $zip . '",
            "Country": "' . $Country . '",
            "City": "' . $city . '",
            "Lead_Source": "' . $Lead_Source . '",
            "Interested In": "' . $service_name . '"
        }
    ]
}';
                    break;
                case 1139:
                    // 1139 IntegrateSun
                    $url_Leads = "https://www.zohoapis.com/crm/v2/Contacts";

                    $average_bill = 0;
                    switch ($crm_details['service_id']){
                        case 2:
                            //Solar
                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);

                            switch ($monthly_electric_bill){
                                case '$0 - $50':
                                    $average_bill = 50;
                                    break;
                                case '$51 - $100':
                                    $average_bill = 100;
                                    break;
                                case '$101 - $150':
                                    $average_bill = 150;
                                    break;
                                case '$151 - $200':
                                    $average_bill = 200;
                                    break;
                                case '$201 - $300':
                                    $average_bill = 300;
                                    break;
                                case '$301 - $400':
                                    $average_bill = 400;
                                    break;
                                case '$401 - $500':
                                    $average_bill = 500;
                                    break;
                                default:
                                    $average_bill = 600;
                            }
                            break;
                    }

                    $input_leads = '{
            "data": [
                {
                    "Last_Name": "' . $last_name . '",
                    "First_Name": "' . $first_name . '",
                    "Email": "' . $email . '",
                    "Phone": "+1' . $number1 . '",
                    "Mailing_Street": "' . $street . '",
                    "Mailing_City": "' . $city . '",
                    "Mailing_State": "' . $statename_code . '",
                    "Mailing_Zip": "' . $zip . '",
                    "Mailing_Country": "' . $Country . '",
                    "Pre_Solar_Bill": "' . $average_bill . '",
                    "lead_source1": "S033",
                    "Lead_Status": "Open for Calls"
                }
            ]
        }';
                    break;
                case 1227:
                    //green conception 1227

                    switch ($crm_details['service_id']){
                        case 2:
                            //Solar
                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                            $power_solution = trim($crm_details['data']['power_solution']);

                            $google_ts = $data_msg['google_ts'];
                            $srcTier = "AlliedDigitalMedia";
                            $input_leads = '{
                        "data": [
                            {
                                "Last_Name": "' . $last_name . '",
                                "First_Name": "' . $first_name . '",
                                "Email": "' . $email . '",
                                "State": "' . $statename_code . '",
                                "Phone": "' . $number1 . '",
                                "Street": "' . ucwords($street) . '",
                                "Zip_Code": "' . $zip . '",
                                "Country": "' . $Country . '",
                                "City": "' . ucwords(strtolower($city)) . '",
                                "Lead_Source": "' . $srcTier . '",
                                "SubId": "' . $google_ts . '",
                                "Website": "' . $OriginalURL2 . '",
                                "Created_By": "390912000000044003",
                                "Currency": "USD",
                                "Lead_Vertical": "Solar",
                                "Modified By": "390912000000044003",
                                "Project_Notes": "Solution Type: '. $power_solution.', Monthly Electric Bill: '.$monthly_electric_bill.'",
                                "Owner": "390912000000044003"
                            }
                        ]
                    }';
                            break;

                        case 6:
                            $roof_type = trim($crm_details['data']['roof_type']);
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $property_type = trim($crm_details['data']['property_type']);
                            $start_time = trim($crm_details['data']['start_time']);

                            switch ($roof_type){
                                case "Asphalt Roofing":
                                    $roof_type_text = "Asphalt Shingle";
                                    break;
                                case "Wood Shake/Composite Roofing":
                                    $roof_type_text = "Cedar Shake";
                                    break;
                                case "Metal Roofing":
                                    $roof_type_text = "Metal";
                                    break;
                                case "Natural Slate Roofing":
                                    $roof_type_text = "Slate";
                                    break;
                                case "Tile Roofing":
                                    $roof_type_text = "Clay/Concrete Tile";
                                    break;
                                default:
                                    $roof_type_text="Other";
                            }

                            $google_ts = $data_msg['google_ts'];
                            $srcTier = "AlliedDigitalMedia";
                            $input_leads = '{
                        "data": [
                            {
                                "Last_Name": "' . $last_name . '",
                                "First_Name": "' . $first_name . '",
                                "Email": "' . $email . '",
                                "State": "' . $statename_code . '",
                                "Phone": "' . $number1 . '",
                                "Street": "' . ucwords($street) . '",
                                "Zip_Code": "' . $zip . '",
                                "Country": "' . $Country . '",
                                "City": "' . ucwords(strtolower($city)) . '",
                                "Lead_Source": "' . $srcTier . '",
                                "SubId": "' . $google_ts . '",
                                "Roof_Type": "' . $roof_type_text . '",
                                "Website": "' . $OriginalURL2 . '",
                                "Created_By": "390912000000044003",
                                "Currency": "USD",
                                "Lead_Vertical": "Roofing",
                                "Modified By": "390912000000044003",
                                "Project_Notes": "Project Nature: '. $project_nature.', Property Type: '.$property_type.', Start Time: '.$start_time.'",
                                "Owner": "390912000000044003"
                            }
                        ]
                    }';
                            break;
                    }

                    break;


                default:
                    $input_leads = '{
    "data": [
        {
            "Company": "' . $Company . '",
            "Last_Name": "' . $last_name . '",
            "First_Name": "' . $first_name . '",
            "Email": "' . $email . '",
            "State": "' . $statename_code . '",
            "Phone": "' . $number1 . '",
            "Street": "' . $street . '",
            "Zip_Code": "' . $zip . '",
            "Country": "' . $Country . '",
            "City": "' . $city . '",
            "Lead_Source": "' . $Lead_Source . '",
            "Lead_Status": "' . $lead_status . '",
            "Interested In": "' . $service_name . '"
        }
    ]
}';
            }

            $httpheader_Leads = array(
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            );

            $result_Leads = $crm_api_file->api_send_data($url_Leads, $httpheader_Leads, $leadsCustomerCampaign_id, stripslashes($input_leads), "POST", 1, $crm_details['campaign_id']);
            if (strpos("-" . $result_Leads, 'SUCCESS') == true) {
                return 1;
            }
        }
        return $status;
    }

    public function HatchCrm($data_msg, $crm_details){
        //Hatch CRM
        if(empty($crm_details['HatchCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "HatchCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $user_id = $crm_details['buyer_id'];

        $dep_id = $crm_details['HatchCrm']['dep_id'];
        $org_token = $crm_details['HatchCrm']['org_token'];
        $urlSub = $crm_details['HatchCrm']['URL_sub'];
        $serviceN = $crm_details['service_campaign_name'];

        //Hatch API ======================================================
        if (!empty($urlSub)){
            $url_api_post = "https://$urlSub.usehatchapp.com/api/webhooks/$dep_id/newlead";
        } else {
            $url_api_post = "https://prod.usehatchapp.com/api/webhooks/$dep_id/newlead";
        }

        $httpheader = array(
            "X-API-KEY: $org_token",
            "Content-Type: application/json",
            "Accept : application/xml"
        );

        $Lead_data_array = array(
            "firstName" => $first_name,
            "lastName" => $last_name,
            "email" => $email,
            "phoneNumber" => $number1,
            "source" => 'Allied Digital Media',
            "id" => $leadsCustomerCampaign_id
        );

        switch ($user_id){
            case 151:
                $Lead_data_array['service'] = $serviceN;
                break;
            case 1106:
                //Asher Lasting Exteriors  1106
                $Lead_data_array['details'] = array("address" => $street);
                $Lead_data_array['zipcode'] = $zip;
                break;
            case 667:
                // Wintek USA 667
                switch ($crm_details['service_id']){
                    case 1:
                        $Lead_data_array['Service'] = "Windows";
                        break;
                    default:
                        $Lead_data_array['Service'] = $serviceN;
                }
                break;
//            case 667:
//                //This update is not related with hatch{}
//                // Wintek USA 667
//                $data = array(
//                    'email' => $email,
//                    'first_name' => $first_name,
//                    'last_name' => $last_name,
//                    'street' => $street,
//                    'city' => $city,
//                    'state' => $state,
//                    'zip' => $zip,
//                    'number1' => $number1,
//                    'Interest' => "Window",
//                    'BestTimeToCall' => "Any Time",
//                );
//                Mail::send(['text'=>'Mail.wintekusa'], $data, function($message) {
//                    $message->to('eleadimport@blp9x754corlhis2fjj25198tcv63uknq5iirz96z8prryx7h.1u-12dgiuai.na210.apex.salesforce.com', 'WintekUSA')->subject('Provider = Allied Digital Media');
//                    $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
//                });
//                break;
        }

        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . $result, 'true') == true) {
            return 1;
        }
        return 0;
    }

    public function SalesforceCrm($data_msg, $crm_details){
        //Salesforce CRM
        if(empty($crm_details['salesforceCRM'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "salesforceCRM"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $state = trim($data_msg['State']);
        $statename_code = $data_msg['state_code'];
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];

        $url_api = ( !empty($crm_details['salesforceCRM']['url']) ? $crm_details['salesforceCRM']['url'] : "https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" );
        $lead_source = ( !empty($crm_details['salesforceCRM']['lead_source']) ? $crm_details['salesforceCRM']['lead_source'] : "");
        $retURL = $crm_details['salesforceCRM']['retURL'];
        $oid = $crm_details['salesforceCRM']['oid'];

        $Lead_data_array = "oid=$oid&retURL=$retURL&first_name=$first_name&last_name=$last_name&email=$email&phone=$number1&street=$street&city=$city&zip=$zip&state_code=$statename_code&state=$state&lead_source=$lead_source&country_code=US";

        switch ($crm_details['buyer_id']){
            case 586:
                //Solar Titan USA 586
                $property_type = $crm_details['data']['property_type'];
                $property_type_data = "0124x000000DlNY";
                if ($property_type == 'Business') {
                    $property_type_data = "0124x000000DlNd";
                }
                $Lead_data_array .= "&recordType=0124x000000DlNY&recordType=$property_type_data";
                break;
            case 1109:
                //Ameco Solar - Hybrid - Rev-Share 1109
                switch ($crm_details['service_id']){
                    case 2:
                        $Lead_data_array .= "&00N3r00000HFdtv=Solar PV";
                        break;
                    case 6:
                        $Lead_data_array .= "&00N3r00000HFdtv=Roofing";
                        break;
                }
                break;
            case 1128:
                //Boston Solar 1128
                $Lead_data_array .= "&Secondary_Lead_Source__c=Allied Digital Media&System_Path__c=Web&Parent_Account__c=TBSC&Type__c=Residential&Project_Type__c=Solar";
                break;
        }

        $Lead_data_array = str_replace(" ","%20",$Lead_data_array);

        $httpheader = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Cookie: BrowserId=cDZghvzqEeqVPLVV8x7_Fg"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function BuilderPrimeCRM($data_msg, $crm_details){
        if(empty($crm_details['Builder_Prime_CRM'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "Builder_Prime_CRM"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = $data_msg['first_name'];
        $last_name = $data_msg['last_name'];
        $number1 = $data_msg['LeadPhone'];
        $email = $data_msg['LeadEmail'];
        $zip = $data_msg['Zipcode'];
        $street = $data_msg['street'];
        $city = $data_msg['City'];
        $statename_code = $data_msg['state_code'];
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];

        $postURL = $crm_details['Builder_Prime_CRM']['post_url'];
        $secretKey = $crm_details['Builder_Prime_CRM']['secret_key'];
        $httpheader = array(
            "Content-Type: application/json",
        );

        $Lead_data_array = array(
            "firstName" => $first_name,
            "lastName" => $last_name,
            "email" => $email,
            "mobilePhone" => $number1,
            "addressLine1" => $street,
            "city" => $city,
            "state" => $statename_code,
            "zip" => $zip,
            "companyName" => config('app.name', ''),
            "leadStatusName" => "Lead received",
            "leadSourceName" => "ADM - Form",
            "secretKey" => $secretKey,
        );

        switch ($crm_details['buyer_id']){
            case 1041:
                //Punch List Bath & Home Remodeling 1041
                $Lead_data_array['className'] = "Punch List Remodeling";
                $Lead_data_array['projectTypeName'] = "Bathroom Remodel";
                break;
            case 1244:
                //Window Depot USA 1244
                $number_of_windows = trim($crm_details['data']['number_of_window']);
                $project_nature = trim($crm_details['data']['project_nature']);
                $start_time = trim($crm_details['data']['start_time']);
                $ownership = trim($crm_details['data']['homeOwn']);
                $serviceName= $crm_details['service_campaign_name'];
                $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
                $Lead_data_array['notes'] = "Lead Service:$serviceName, Lead ID:$leadsCustomerCampaign_id, Owner of the Property:$ownership,The project is starting:$start_time,How many windows are involved:$number_of_windows,Type of the project:$project_nature";
                break;
        }

        $result = $crm_api_file->api_send_data($postURL, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if(str_contains(strtolower($result) , 'success')){
            return 1;
        }
        return 0;
    }

    public function ZapierCRM($data_msg, $crm_details){
        //ZapierCrm
        if(empty($crm_details['ZapierCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "ZapierCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $lead_source_text = $data_msg['lead_source'];
        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $email = trim($data_msg['LeadEmail']);
        $number1 = trim($data_msg['LeadPhone']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $zip = trim($data_msg['Zipcode']);
        $state = trim($data_msg['State']);
        $statename_code = $data_msg['state_code'];
        $user_id = $crm_details['buyer_id'];
        $OriginalURL = $data_msg['OriginalURL'];

        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        if ($listOFCampainDB_type == "Exclusive") {
            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Exclusive";
        } else {
            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Shared";
        }

        $url_api = $crm_details['ZapierCrm']['post_url'];

        $httpheader = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );

        $Lead_data_array = array(
            "Lead_ID" => $leadsCustomerCampaign_id,
            "Source" => $lead_source_text,
            "Campaign" => $crm_details['campaign_id'],
            "First" => $first_name,
            "Last" => $last_name,
            "Email" => $email,
            "Phone" => $number1,
            "Street" => $street,
            "City" => $city,
            "State" => $statename_code,
            "Zip" => $zip,
        );

        switch ($crm_details['service_id']){
            case 2:
                $utility_provider = $crm_details['data']['utility_provider'];
                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                $roof_shade = trim($crm_details['data']['roof_shade']);
                $property_type = trim($crm_details['data']['property_type']);
                $power_solution = trim($crm_details['data']['power_solution']);

                $Lead_data_array['Utility_Company'] = $utility_provider;

                switch ($crm_details['buyer_id']) {
                    case 1254:
                        //Sunvision LLC 1254
                        $Lead_data_array['avgbill'] = $monthly_electric_bill;
                        $Lead_data_array['shade'] = $roof_shade;
                        $Lead_data_array['propertytype'] = "single family home";
                        $Lead_data_array['credit'] = "Good";
                        break;
                }
                break;
            case 4:
                // Flooring
                $Type_OfFlooring = trim($crm_details['data']['flooring_type']);
                $project_nature = trim($crm_details['data']['project_nature']);
                $start_time = trim($crm_details['data']['start_time']);
                $ownership = trim($crm_details['data']['homeOwn']);

                $Lead_data_array['flooring_type'] = $Type_OfFlooring;
                $Lead_data_array['project_nature'] = $project_nature;
                $Lead_data_array['start_time'] = $start_time;
                $Lead_data_array['property_owner'] = $ownership;
                break;
        }

        switch ($user_id){
            case 1117:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Cleveland";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 1116:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Tide Water";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 712:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Pittsburgh";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 711:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Mid Atlantic";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 710:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "New Jersey";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 708:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Philadelphia";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 1289:
                $Lead_data_array['url'] = $OriginalURL;
                $Lead_data_array['Division'] = "Richmond";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                $Lead_data_array['Price'] = $Lead_Cost;
                break;
            case 1294:
                $Lead_data_array['Division'] = "Columbus";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                break;
            case 1293:
                $Lead_data_array['Division'] = "Long Island";
                $Lead_data_array['Service'] = $crm_details['service_campaign_name'];
                break;
        }

        $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if (!empty($result2['status'])) {
            if( $result2['status'] == "success" ){
                return 1;
            }
        }
        return 0;
    }

    public function SetShapeCrm($data_msg, $crm_details){
        //SetShapeCrm
        if(empty($crm_details['SetShapeCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "SetShapeCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $lead_source_text = $data_msg['lead_source'];
        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $email = trim($data_msg['LeadEmail']);
        $number1 = trim($data_msg['LeadPhone']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $zip = trim($data_msg['Zipcode']);
        $state = trim($data_msg['State']);
        $statename_code = $data_msg['state_code'];

        $url_api = $crm_details['SetShapeCrm']['post_url'];

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "Content-Type: application/json"
        );

        $Lead_data_array_post = array(
            "ImportleadId" => $leadsCustomerCampaign_id,
            "firstname" => $first_name,
            "lastname" => $last_name,
            "email" => $email,
            "phone" => $number1,
            "city" => $city,
            "state" => $statename_code,
            "zip" => $zip,
            "leadfulladdress" => $street,
            "leadsource" => $lead_source_text,
        );

        switch ($crm_details['service_id']) {
            case 2:
                //Solar
                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                $utility_provider = trim($crm_details['data']['utility_provider']);
                $roof_shade = trim($crm_details['data']['roof_shade']);
                $property_type = trim($crm_details['data']['property_type']);
                $power_solution = trim($crm_details['data']['power_solution']);

                $Lead_data_array_post['avgsummerbill'] = $monthly_electric_bill;
                $Lead_data_array_post['electricCompany'] = $utility_provider;
                $Lead_data_array_post['creditscore'] = "Good";
                $Lead_data_array_post['bestContactTime'] = "Morning";
                $Lead_data_array_post['typeOfsolution'] = $power_solution;
                break;
        }

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if (!empty($result2['msg'])) {
            if ($result2['msg'] == "success") {
                return 1;
            }
        }
        return 0;
    }

    public function jobNimbusCrm($data_msg, $crm_details){
        //jobNimbusCrm
        if(empty($crm_details['SetJobNimbusCrm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "SetJobNimbusCrm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $lead_source_text = $data_msg['lead_source'];
        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $email = trim($data_msg['LeadEmail']);
        $number1 = trim($data_msg['LeadPhone']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $zip = trim($data_msg['Zipcode']);
        $state = trim($data_msg['State']);
        $statename_code = $data_msg['state_code'];
        $user_id = $crm_details['buyer_id'];

        $api_key = $crm_details['SetJobNimbusCrm']['api_key'];
        $url_api = "https://app.jobnimbus.com/api1/contacts";

        $httpheader = array(
            'Accept: application/json',
            'Content-Type: application/json',
            "Authorization: Bearer ".$api_key
        );

        $Lead_data_array_post = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "status_name" => "Lead",
            "address_line1" => $street,
            "city" => $city,
            "state_text" => $statename_code,
            "zip" => $zip,
            "email" => $email,
            "number" => $number1,
            "home_phone" => $number1,
            "mobile_phone" => $number1,
        );

        switch ($user_id){
            case 1243:
                $Lead_data_array_post['source_name'] = "AlliedLead";
                $Lead_data_array_post['sales_rep_name'] = "Jessica Fristoe";
                break;
        }

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if (!empty($result2)) {
            if(!empty($result2["type"])){
                return 1;
            }
        }
        //return 1;
    }

    public function SunBaseDataCrm($data_msg, $crm_details){
        //Sunbase CRM
        if(empty($crm_details['set_sunbase_crm'])){
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "set_sunbase_crm"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $first_name = trim($data_msg['first_name']);
        $last_name = trim($data_msg['last_name']);
        $number1 = trim($data_msg['LeadPhone']);
        $zip = trim($data_msg['Zipcode']);
        $street = trim($data_msg['street']);
        $city = trim($data_msg['City']);
        $email = trim($data_msg['LeadEmail']);
        $statename_code = trim($data_msg['state_code']);
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $lead_source_text = $data_msg['lead_source'];
        $google_ts = $data_msg['google_ts'];

        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        if ($listOFCampainDB_type == "Exclusive") {
            $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Exclusive";
        } else {
            $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
            $Lead_Cost_type = "Shared";
        }

        $url_api = $crm_details['set_sunbase_crm']['url'];
        $schema_name = $crm_details['set_sunbase_crm']['schema_name'];
        $lead_other = config('app.name', '');
        $lead_source = (!empty($google_ts) ? $google_ts : $lead_source_text);

        $Lead_data_array = array(
            "schema_name" => $schema_name,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "address1" => $street,
            "city" => $city,
            "state" => $statename_code,
            "zip_code" => $zip,
            "phone" => $number1,
            "email" => $email,
            "lead_other" => $lead_other,
            "lead_source" => $lead_source,
            "lead_cost" => $Lead_Cost,
        );

        switch ($crm_details['service_id']){
            case 2:
                //Solar
                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                $utility_provider = trim($crm_details['data']['utility_provider']);

                switch ($monthly_electric_bill){
                    case '$0 - $50':
                        $average_bill = 50;
                        break;
                    case '$51 - $100':
                        $average_bill = 100;
                        break;
                    case '$101 - $150':
                        $average_bill = 150;
                        break;
                    case '$151 - $200':
                        $average_bill = 200;
                        break;
                    case '$201 - $300':
                        $average_bill = 300;
                        break;
                    case '$301 - $400':
                        $average_bill = 400;
                        break;
                    case '$401 - $500':
                        $average_bill = 500;
                        break;
                    default:
                        $average_bill = 600;
                }

                $Lead_data_array['customField1'] = $utility_provider;
                $Lead_data_array['customField37'] = $average_bill;
                break;
        }

        $httpheader = array(
            "Accept: application/json",
            "content-type: application/x-www-form-urlencoded"
        );

        $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, http_build_query($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if(str_contains(strtolower($result) , 'successfully')){
            return 1;
        }
        return 0;
    }

    public function googleSheets($spreadsheetId, $range, $values){
        try {
            $crm_api_file = new CrmApi();

            $client = new \Google_Client();
            $client->setApplicationName('GoogleSheetAllied');
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(public_path() . '/GoogleFile/eloquent-victor-324807-eeb1c833bce2.json');
            $service = new \Google_Service_Sheets($client);

            //$spreadsheetId = "1VPBqPd8HSDkfwQo1rrOXweicwHcB5m_g3ubRQ7VB9M0";
            //$range = "Sheet1";
            //$values = [
            //    ["test", "test", 'test', 'test'],
            //];

            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            $params = ["valueInputOption" => "RAW"];
            $insert = ["insertDataOption" => "INSERT_ROWS"];

            $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params, $insert);
        } catch (Exception $e) {

        }
        return 1;
    }

    public function CustomCrm($data_msg, $crm_details,$user_id){
        try {
            $crm_api_file = new CrmApi();

            $lead_source_text = $data_msg['lead_source'];
            $lead_source_id = $data_msg['lead_source_id'];
            $campaign_name = $crm_details['campaign_name'];
            $first_name = $data_msg['first_name'];
            $last_name = $data_msg['last_name'];
            $number1 = $data_msg['LeadPhone'];
            $email = $data_msg['LeadEmail'];
            $zip = $data_msg['Zipcode'];
            $street = $data_msg['street'];
            $city = $data_msg['City'];
            $county = $data_msg['county'];
            $statename_code = $data_msg['state_code'];
            $state = $data_msg['State'];
            $trusted_form = $data_msg['trusted_form'];
            $lead_type_service_id = $data_msg['service_id'];
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
            $service_campaign_name = $crm_details['service_campaign_name'];
            $TCPAText = $data_msg['TCPAText'];
            $UserAgent = $data_msg['UserAgent'];
            $OriginalURL = $data_msg['OriginalURL'];
            $OriginalURL2 = $data_msg['OriginalURL2'];
            $SessionLength = $data_msg['SessionLength'];
            $IPAddress = $data_msg['IPAddress'];
            $LeadId = $data_msg['LeadId'];
            $lead_browser_name = $data_msg['browser_name'];
            $tcpa_compliant = $data_msg['tcpa_compliant'];
            $traffic_source = $data_msg['traffic_source'];
            $google_ts = $data_msg['google_ts'];
            $lead_source_name = $data_msg['lead_source_name'];
            if( $tcpa_compliant == 1 ){
                $tcpa_compliant2 = "Yes";
                $tcpa_compliant3 = "yes";
                $tcpa_compliant4 = true;
                $tcpa_compliant5 = 1;
            } else {
                $tcpa_compliant2 = "No";
                $tcpa_compliant3 = "no";
                $tcpa_compliant4 = false;
                $tcpa_compliant5 = 2;
            }

            $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
            if ($listOFCampainDB_type == "Exclusive") {
                $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
                $Lead_Cost_type = "Exclusive";
            } else {
                $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
                $Lead_Cost_type = "Shared";
            }

            //CustomCrm CRM
            switch ($user_id) {
                case 15:
                    //RGR Marketing	15
                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                    } else {
                        return 0;
                    }

                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    switch ($crm_details['service_id']) {
                        case 1:
                            //Window
                            $number_of_windows = trim($crm_details['data']['number_of_window']);
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $ownership = trim($crm_details['data']['homeOwn']);

                            $homeowner = ($ownership == "Yes" ? "yes" : "no");
                            $ProjectType = ($project_nature == "Repair" ? "repair" : "replace");

                            $Lead_data_array = array(
                                "type" => "homeimprovementwindow",
                                "publisher_id" => "494",
                                "rcid" => "1158",
                                "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                "subid" => $google_ts,
                                "trustedformtoken" => $trusted_form,
                                "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                "tcpa" => $TCPAText,
                                "ping_id" => $TransactionId,
                                "firstname" => $first_name,
                                "lastname" => $last_name,
                                "address" => $street,
                                "phone" => $number1,
                                "email" => $email,
                                "city" => $city,
                                "zip" => $zip,
                                "state" => $statename_code,
                                "homeowner" => $homeowner, //Home Owner
                                "projecttype" => $ProjectType, //project nature
                                "windowcount" => $number_of_windows, //number of windows
                                "ipaddress" => $IPAddress,
                            );
                            break;
                        case 2:
                            //Solar
                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                            $utility_provider = trim($crm_details['data']['utility_provider']);
                            $roof_shade = trim($crm_details['data']['roof_shade']);
                            $property_type = trim($crm_details['data']['property_type']);

                            $homeowner = ($property_type == 'Rented' ? 'no' : 'yes');

                            switch ($roof_shade) {
                                case "Full Sun":
                                    $roof_shade_data = "no shade";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "a lot of shade";
                                    break;
                                case "Partial Sun":
                                    $roof_shade_data = "a little shade";
                                    break;
                                default:
                                    $roof_shade_data = "uncertain";
                            }

                            switch ($monthly_electric_bill) {
                                case '$0 - $50':
                                    $monthly_bill = 50;
                                    break;
                                case '$51 - $100':
                                    $monthly_bill = 100;
                                    break;
                                case '$101 - $150':
                                    $monthly_bill = 150;
                                    break;
                                case '$151 - $200':
                                    $monthly_bill = 200;
                                    break;
                                case '$201 - $300':
                                    $monthly_bill = 300;
                                    break;
                                case '$301 - $400':
                                    $monthly_bill = 400;
                                    break;
                                case '$401 - $500':
                                    $monthly_bill = 500;
                                    break;
                                default:
                                    $monthly_bill = 600;
                            }

                            $Lead_data_array = array(
                                "publisher_id" => "494",
                                "rcid" => "1100",
                                "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                "ping_id" => $TransactionId,
                                "type" => "solar",
                                "firstname" => $first_name,
                                "lastname" => $last_name,
                                "city" => $city,
                                "address" => $street,
                                "phone" => $number1,
                                "email" => $email,
                                "upload_type" => "RGR",
                                "state" => $statename_code,
                                "zip" => $zip,
                                "electricityprovider" => $utility_provider, //Electricity Provider
                                "creditscore" => "good", //Credit Status
                                "powerbill" => $monthly_bill, //Power Bill.
                                "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                "homeowner" => $homeowner, //home owner
                                "roofshade" => $roof_shade_data, //Roof Shade
                                "ipaddress" => $IPAddress,
                                "trustedformtoken" => $trusted_form
                            );

                            break;
                        case 6:
                            //Roofing
                            $Type_OfRoofing = trim($crm_details['data']['roof_type']);
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $property_type = trim($crm_details['data']['property_type']);

                            switch ($Type_OfRoofing) {
                                case "Asphalt Roofing":
                                    $roofmaterial = "asphalt";
                                    break;
                                case "Wood Shake/Composite Roofing":
                                    $roofmaterial = "wood";
                                    break;
                                case "Metal Roofing":
                                    $roofmaterial = "metal";
                                    break;
                                case "Natural Slate Roofing":
                                    $roofmaterial = "slate";
                                    break;
                                default:
                                    $roofmaterial = "unsure";
                            }

                            $ProjectType = ($project_nature == "Repair existing roof" ? "repair" : "replace");

                            $Lead_data_array = array(
                                "type" => "homeimprovementroofing",
                                "publisher_id" => "494",
                                "rcid" => "1159",
                                "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                "subid" => $google_ts,
                                "ipaddress" => $IPAddress,
                                "trustedformtoken" => $trusted_form,
                                "leadidtoken" => $LeadId,//Jornaya LeadiD Token
                                "tcpa" => $TCPAText,
                                "ping_id" => $TransactionId,
                                "firstname" => $first_name,
                                "lastname" => $last_name,
                                "email" => $email,
                                "phone" => $number1,
                                "address" => $street,
                                "city" => $city,
                                "state" => $statename_code,
                                "zip" => $zip,
                                "homeowner" => "yes",//HomeOwner
                                "projecttype" => $ProjectType, //project nature
                                "roofmaterial" => $roofmaterial, //number of windows
                            );
                            break;
                        case 7:
                            //siding
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $type_of_siding = trim($crm_details['data']['type_of_siding']);
                            $ownership = trim($crm_details['data']['homeOwn']);

                            $homeowner = ($ownership == "Yes" ? "yes" : "no");
                            $projecttype = ($project_nature == "Repair section(s) of siding" ? "repair" : "replace");

                            switch ($type_of_siding) {
                                case "Vinyl Siding":
                                    $SidingType = "vinyl";
                                    break;
                                case "Fiber Cement Siding":
                                    $SidingType = "fiber";
                                    break;
                                case "Composite wood Siding":
                                    $SidingType = "wood";
                                    break;
                                default:
                                    $SidingType = "unsure";
                            }

                            $Lead_data_array = array(
                                "type" => "homeimprovementsiding",
                                "publisher_id" => "494",
                                "rcid" => "1160",
                                "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                "subid" => $google_ts,
                                "trustedformtoken" => $trusted_form,
                                "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                "tcpa" => $TCPAText,
                                "ping_id" => $TransactionId,
                                "firstname" => $first_name,
                                "lastname" => $last_name,
                                "address" => $street,
                                "phone" => $number1,
                                "email" => $email,
                                "city" => $city,
                                "zip" => $zip,
                                "state" => $statename_code,
                                "homeowner" => $homeowner, //Home Owner
                                "projecttype" => $projecttype, //project nature
                                "sidingtype" => $SidingType, //Siding Type
                                "ipaddress" => $IPAddress,
                            );
                            break;
                        case 9:
                            //Bathroom
                            $bathroom_type_name = trim($crm_details['data']['services']);
                            $ownership = trim($crm_details['data']['homeOwn']);

                            $homeowner = ($ownership == "Yes" ? "yes" : "no");
                            $projecttype = "new bath";

                            $Lead_data_array = array(
                                "type" => "homeimprovementbath",
                                "publisher_id" => "494",
                                "rcid" => "1161",
                                "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                "subid" => $google_ts,
                                "trustedformtoken" => $trusted_form,
                                "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                "tcpa" => $TCPAText,
                                "ping_id" => $TransactionId,
                                "firstname" => $first_name,
                                "lastname" => $last_name,
                                "address" => $street,
                                "phone" => $number1,
                                "email" => $email,
                                "city" => $city,
                                "zip" => $zip,
                                "state" => $statename_code,
                                "homeowner" => $homeowner,
                                "projecttype" => $projecttype,
                                "addremovewalls" => "no",
                                "ipaddress" => $IPAddress,
                            );
                            break;
                    }

                    switch ($lead_type_service_id) {
                        case 1:
                        case 2:
                        case 6:
                        case 7:
                        case 9:
                            if (config('app.env', 'local') == "local") {
                                //Test Mode
                                $Lead_data_array['subid'] = "matched";
                                $url_api = "https://api.reallygreatrate.com/test/pingpost/post";
                            } else {
                                $url_api = "https://api.reallygreatrate.com/lead/pingpost/post";
                            }

                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['status'])) {
                                if ($result2['status'] == "matched") {
                                    return 1;
                                }
                            }
                            break;
                    }
                    break;
                case 17:
                    //BCI ACRYLIC 27

                $trusted_form_link = substr($data_msg['trusted_form'], strrpos($data_msg['trusted_form'], '/') + 1);

                $url_api_post = "https://app.leadconduit.com/flows/682e089ec67ac098a4f77fce/sources/682e08bc891b89e0dd36a55a/submit";
                $httpheader = array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                );

                $Lead_data_array = array(
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "phone_1" => $number1,
                    "email" => $email,
                    "address_1" => $street,
                    "city" => $city,
                    "state" => $statename_code,
                    "postal_code" => $zip,
                    "campaign_id" => $campaign_name,
                    "trustedform_cert_url" => $trusted_form,
                    "trustedform_cert_bci" => $trusted_form,

                    "dialed_tollfree_bci" => "0000000504",
                    "userid_bci" => "Thryvea",
                    "password_bci" => "GtK9)%fg",

                );
                $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                $result2 = json_decode($result, true);
                if (!empty($result2)) {
                    if (!empty($result2['outcome'])) {
                        if ($result2['outcome'] == "success") {
                            $TransactionId = $result2['lead']['id'];
                            $Payout = $result2['price'];
                            $multi_type = 0;
                            $Result = 1;
                            return 1;
                        }
                    }
                }
                    break;
                case 18:
                    //HELLO PROJECT USA
                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                    } else {
                        return 0;
                    }

                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $url_api = "https://helloprojectusa.leadportal.com/apiJSON.php";
                    $Key = "3c47b231abb663ec2b8266279033f56cd30def9c705ec71b1a2be17cd1c88072";
                    $API_Action = "pingPostLead";
                    $TYPE = 18;
                    $SRC = "Thryvea_Internal";
                    $Sub_ID = "Thry20";
                    $Pub_ID = "Thry20";
                    $Format = "JSON";
                    switch ($lead_type_service_id) {
                        case 1:
                            $Project = "Windows";
                            $ownership = trim($crm_details['data']['homeOwn']);
                            break;
                        case 2:
                            $Project = "Solar";
                            $property_type = trim($crm_details['data']['property_type']);
                            $ownership = ($property_type == 'Rented' ? 'No' : 'Yes');
                            break;
                        case 6:
                            $Project = "Roofing";
                            $ownership = "Yes";
                            break;
                        case 9:
                            $Project = "Bathroom Remodeling";
                            $ownership = trim($crm_details['data']['homeOwn']);
                            break;
                        default:
                            $Project = "";
                    }

                    if( config('app.env', 'local') == "local" ) {
                        //Test Mode
                        $statename_code = "IL";
                        $zip = "99999";
                        $IPAddress = "75.2.92.149";
                        $OriginalURL2 = "landing";
                        $email = "test@nags.us";

                    }

                    $Lead_data_array = array(
                        "Request" => array(
                            "Key" => $Key,
                            "API_Action" => $API_Action,
                            "Format" => $Format,
                            "Mode" => "post",
                            "TYPE" => $TYPE,
                            "SRC" => $SRC,
                            "Pub_ID" => $Pub_ID,
                            "Sub_ID" => $Sub_ID,
                            "First_Name" => $first_name,
                            "Last_Name" => $last_name,
                            "Address" => $street,
                            "Primary_Phone" => $number1,
                            "Email" => $email,
                            "Lead_ID" => $TransactionId,
                            "Homeowner" => $ownership,
                            "City" => $city,
                            "State" => $statename_code,
                            "Zip" => $zip,
                            "Project" => $Project,
                            "IP_Address" => $IPAddress,
                            "TCPA_Consent" => $tcpa_compliant2,
                            "TCPA_Language" => $TCPAText,
                            "Landing_Page" => $OriginalURL2,
                            "User_Agent" => "Mozilla/5.0 (Linux; Android 11; motorola one 5G UW ace Build/RRV31.Q3-8; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/103.0.5060.71 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/374.0.0.20.109;]",
                            "Trusted_Form_URL" => $trusted_form,
                            "xxTrustedFormCertUrl" => $trusted_form,
                            "LeadiD_Token" => $LeadId
                        )
                    );

                    if( config('app.env', 'local') == "local" ) {
                        //Test Mode
                        $Lead_data_array['Test_Lead'] = "1";
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['response'])) {
                        if (!empty($result2['response']['status'])) {
                            if ($result2['response']['status'] == "Matched") {
                                $TransactionId = $result2['response']['lead_id'];
                                return $TransactionId;
                            }
                        }
                    }
                    break;
                case 20:
                    // Miller Home
                    $trusted_form_link = substr($data_msg['trusted_form'], strrpos($data_msg['trusted_form'], '/') + 1);

                    $url_api_post = "https://app.leadconduit.com/flows/62b22c3d8f4dd0a195375334/sources/682e306f575d5d3e31452043/submit";
                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                    );

                    $Lead_data_array = array(
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone_1" => $number1,
                        "email" => $email,
                        "address_1" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "postal_code" => $zip,
                        "trustedform_cert_url" => "https://cert.trustedform.com/35a2349b187daa986e23403503571a0f7d66945d",
                        "product" => "RLCC-BS-D",
                        "keyword_source_mill" => "Thryvea",
                    );
                    $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2)) {
                        if (!empty($result2['outcome'])) {
                            if ($result2['outcome'] == "success") {
                                $TransactionId = $result2['lead']['id'];
                                $Payout = $result2['price'];
                                $multi_type = 0;
                                $Result = 1;
                                return 1;
                            }
                        }
                    }
                    break;
                case 23:
                    //HomeQuote 23
                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                    } else {
                        return 0;
                    }

                    $httpheader = array(
                        "Content-Type: application/x-www-form-urlencoded",
                        "Accept: application/json",
                    );

                        if (empty($SessionLength) || $SessionLength == null ) {
                            $SessionLength = "3000";
                        }

                    $Lead_data_array = "ping_id=$TransactionId&first_name=$first_name&last_name=$last_name&address=$street&phone=$number1&email=$email&city=$city&session_length=$SessionLength&ip=$IPAddress&useragent=$UserAgent&country_iso_2=US&region=$statename_code&referrer=$OriginalURL2&zipcode=$zip&trusted_form_cert_url=$trusted_form&trusted_form_cert_id=$trusted_form";

                    switch ($lead_type_service_id){
                        case 1:
                            //windows
                            $PingURLTest ="https://cdgtrck.com/api/v1/leads?a=419&c=340&cmp=5828&cmp_key=0hvb79ejmw&post_test=true";
                            $PingURL ="https://cdgtrck.com/api/v1/leads?a=419&c=340&cmp=5828&cmp_key=0hvb79ejmw";

                            $ownership = trim($crm_details['data']['homeOwn']);
                            $start_time = trim($crm_details['data']['start_time']);
                            $number_of_windows = trim($crm_details['data']['number_of_window']);
                            $project_nature = trim($crm_details['data']['project_nature']);

                            switch ($start_time){
                                case 'Immediately':
                                    $BuyTimeframe = "Immediately";
                                    break;
                                case 'Not Sure':
                                    $BuyTimeframe = "Don't know";
                                    break;
                                default:
                                    $BuyTimeframe = "1-6 months";
                            }
                            switch ($project_nature) {
                                case 'Install':
                                    $project_nature_data = "3";
                                    break;
                                case "Replace":
                                    $project_nature_data = "1";
                                    break;
                                default:
                                    $project_nature_data = "2";
                            }
                            switch ($number_of_windows) {
                                case "1":
                                    $number_of_windows_data = "1";
                                    break;
                                case "2":
                                    $number_of_windows_data = "2";
                                    break;
                                case "3-5":
                                    $number_of_windows_data = "3";
                                    break;
                                case "6-9":
                                    $number_of_windows_data = "4";
                                    break;
                                default:
                                    $number_of_windows_data = "5";
                            }
                            switch ($ownership) {
                                case 'Yes':
                                    $homeowner = "Yes";
                                    $authorized_to_make_changes = "Yes";
                                    break;
                                case "No":
                                    $homeowner = "No";
                                    $authorized_to_make_changes = "No";
                                    break;
                                default:
                                    $homeowner = "No, But Authorized to Make Changes";
                                    $authorized_to_make_changes = "Yes";
                            }

                            $Lead_data_array .="&number=$number_of_windows_data&project=$project_nature_data&homeowner=$homeowner&authorized_to_make_changes=$authorized_to_make_changes";
                            break;
                        case 2:
                            //Solar
                            $PingURLTest ="https://cdgtrck.com/api/v1/leads?a=419&c=333&cmp=5826&cmp_key=8ks2to5r1l&post_test=true";
                            $PingURL ="https://cdgtrck.com/api/v1/leads?a=419&c=333&cmp=5826&cmp_key=8ks2to5r1l";

                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                            $utility_provider = trim($crm_details['data']['utility_provider']);
                            $roof_shade = trim($crm_details['data']['roof_shade']);
                            $property_type = trim($crm_details['data']['property_type']);
                            $power_solution = trim($crm_details['data']['power_solution']);

                            $OwnHome = ($property_type == "Owned" ? "Yes" : "No");
                            switch ($monthly_electric_bill){
                                case '$0 - $50':
                                    $average_bill = '1';
                                    break;
                                case '$51 - $100':
                                    $average_bill = '2';
                                    break;
                                case '$101 - $150':
                                    $average_bill = '3';
                                    break;
                                case '$151 - $200':
                                    $average_bill = '4';
                                    break;
                                case '$201 - $300':
                                    $average_bill = '5';
                                    break;
                                case '$301 - $400':
                                    $average_bill = '6';
                                    break;
                                case '$401 - $500':
                                    $average_bill = '7';
                                    break;
                                default:
                                    $average_bill = '11';

                            }
                            switch ($roof_shade){
                                case "Full Sun":
                                    $roof_shade_data = "1";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "3";
                                    break;
                                case "Partial Shade":
                                    $roof_shade_data = "2";
                                    break;
                                default:
                                    $roof_shade_data = "4";
                            }

                            $Lead_data_array .="&homeowner=$OwnHome&electric_bill=$average_bill&electric_utility_provider=$utility_provider&roof_shade=$roof_shade_data";
                            break;
                        case 6:
                            //Roofing
                            $PingURLTest ="https://cdgtrck.com/api/v1/leads?a=419&c=328&cmp=5827&cmp_key=p8uvjlkrwz&post_test=true";
                            $PingURL ="https://cdgtrck.com/api/v1/leads?a=419&c=328&cmp=5827&cmp_key=p8uvjlkrwz";

                            $roof_type = trim($crm_details['data']['roof_type']);
                            $project_nature = trim($crm_details['data']['project_nature']);

                            switch ($roof_type){
                                case "Wood Shake/Composite Roofing":
                                    $service = "2";
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $project_nature_data = "2";
                                            $type_material = "2-2";
                                            break;
                                        case "Completely replace roof":
                                            $project_nature_data = "1";
                                            $type_material = "1-2";
                                            break;
                                        default:
                                            $project_nature_data = "3";
                                            $type_material = "3-2";
                                    }
                                    break;
                                case "Metal Roofing":
                                    $service = "3";
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $project_nature_data = "2";
                                            $type_material = "2-3";
                                            break;
                                        case "Completely replace roof":
                                            $project_nature_data = "1";
                                            $type_material = "1-3";
                                            break;
                                        default:
                                            $project_nature_data = "3";
                                            $type_material = "3-3";
                                    }
                                    break;
                                case "Natural Slate Roofing":
                                    $service = "4";
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $project_nature_data = "2";
                                            $type_material = "2-4";
                                            break;
                                        case "Completely replace roof":
                                            $project_nature_data = "1";
                                            $type_material = "1-4";
                                            break;
                                        default:
                                            $project_nature_data = "3";
                                            $type_material = "3-4";
                                    }
                                    break;
                                case "Tile Roofing":
                                    $service = "6";
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $project_nature_data = "2";
                                            $type_material = "2-6";
                                            break;
                                        case "Completely replace roof":
                                            $project_nature_data = "1";
                                            $type_material = "1-6";
                                            break;
                                        default:
                                            $project_nature_data = "3";
                                            $type_material = "3-6";
                                    }
                                    break;
                                default:
                                    $service = "1";
                                    switch ($project_nature){
                                        case "Install roof on new construction":
                                            $project_nature_data = "2";
                                            $type_material = "2-1";
                                            break;
                                        case "Completely replace roof":
                                            $project_nature_data = "1";
                                            $type_material = "1-1";
                                            break;
                                        default:
                                            $project_nature_data = "3";
                                            $type_material = "3-1";
                                    }
                            }
                            $ownership_data = "Yes";

                            $Lead_data_array .= "&roof_material=$service&project=$project_nature_data&homeowner=$ownership_data&type_material=$type_material&time_to_call=2&authorized_to_make_changes=$ownership_data&tcpa_text=$TCPAText";
                            break;
                        case 9:
                            //bathroom
                            $PingURLTest ="https://cdgtrck.com/api/v1/leads?a=419&c=339&cmp=5825&cmp_key=pdvyf0knse&post_test=true";
                            $PingURL ="https://cdgtrck.com/api/v1/leads?a=419&c=339&cmp=5825&cmp_key=pdvyf0knse";

                            $bathroom_type = trim($crm_details['data']['services']);
                            $start_time = trim($crm_details['data']['start_time']);
                            $ownership = trim($crm_details['data']['homeOwn']);

                            switch ($bathroom_type){
                                case "Shower / Bath":
                                    $bathroom_type_data = "1";
                                    break;
                                default:
                                    $bathroom_type_data = "3";
                            }
                            switch ($start_time){
                                case 'Immediately':
                                    $BuyTimeframe = "1";
                                    break;
                                case 'Not Sure':
                                    $BuyTimeframe = "3";
                                    break;
                                default:
                                    $BuyTimeframe = "2";
                            }

                            $Lead_data_array .= "&project=$bathroom_type_data&homeowner=$ownership&timeframe=$BuyTimeframe&authorized_to_make_changes=Yes";
                            break;
                    }

                    // $url_api = str_replace(" ", "+", $Lead_data_array);

                    if (config('app.env', 'local') == "local") {
                        $url_api = $PingURLTest;
                    } else {
                        $url_api = $PingURL;
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['Result'])) {
                        if ($result2['Result'] == "Success") {
                            return 1;
                        }
                    }
                    break;
                case 29:
                    //	Clean Energy Authoroty 29
                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                        $lead_coust = $data_msg['ping_post_data']['Payout'];

                        switch ($crm_details['service_id']) {
                            case 1:
                                //Windows
                                $number_of_windows = trim($crm_details['data']['number_of_window']);
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $ownership = trim($crm_details['data']['homeOwn']);

                                switch ($number_of_windows) {
                                    case "1":
                                        $NumWindows = "New Window - Single";
                                        break;
                                    case "2":
                                        $NumWindows = "New Windows - 2";
                                        break;
                                    case "3-5":
                                        $NumWindows = "New Windows - 3-5";
                                        break;
                                    case "6-9":
                                        $NumWindows = "New Windows - 6-9";
                                        break;
                                    default:
                                        $NumWindows = "New Windows - 10+";
                                }

                                $ownership = ($ownership != "Yes" ? "Yes" : "No");
                                if (config('app.env', 'local') == "local") {
                                    //Test Mode
                                    $url_api_post = "https://uat.sbbnetinc.com/rest/api/windows/submit-inquiry-json";
                                    $source_name = "TESTTCPA";
                                } else {
                                    //Live Mode
                                    $url_api_post = "https://live.sbbnetinc.com/rest/api/windows/submit-inquiry-json";
                                    $source_name = "thrwi";
                                }

                                $Lead_data_array_post = array(
                                    "Request" => array(
                                        // "Key" => "d65a1e05c2f788c17954f386a18690503a260f799db79979fd2e23fb4fd8e1a9",
                                        "API_Action" => "pingPostLead",
                                        "Mode" => "post",
                                        "Return_Best_Price" => "1",
                                        "TYPE" => "18",
                                        "IP_Address" => $IPAddress,
                                        "SRC" => $source_name,
                                        "Landing_Page" => $OriginalURL2,
                                        // "source_number" => $vendor_id,
                                        "Sub_ID" => $leadsCustomerCampaign_id,
                                        "Pub_ID" => $google_ts,
                                        "Universal_Lead_ID" => $LeadId,
                                        "Active_Prospect_URL" => $trusted_form,
                                        "Best_Time_To_Call" => "Anytime",
                                        "Property_Zip" => $zip,
                                        "Property_City" => $city,
                                        "Property_State" => $statename_code,
                                        "Property_Address" => $street,
                                        "Window_Task" => $NumWindows,
                                        "Property_Owner" => $ownership,
                                        "NoSale" => "No",
                                        "Timing" => "Immediately",
                                        "vendor_lead_id" => $leadsCustomerCampaign_id,
                                        "Lead_ID" => $TransactionId,
                                        "Lead_Cost" => $lead_coust,
                                        "First_Name" => $first_name,
                                        "Last_Name" => $last_name,
                                        "Email" => $email,
                                        "Primary_Phone" => $number1,
                                        "Tcpa_Language" => $TCPAText,
                                    )
                                );
                                $httpheader = array(
                                    'Authorization: Basic cmVzdC11c2VyOjVTOGNCRHEmRWYha3BMKk5XNXVM',
                                    'Content-Type: application/json',
                                    //'Accept: application/xml',
                                );

                                $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
                                $result2 = json_decode($result, true);
                                if (!empty($result2['Status']) && $result2['Status'] === 'Sold') {
                                    return 1;
                                }
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($crm_details['data']['roof_type']);
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $property_type = trim($crm_details['data']['property_type']);
                                $start_time = trim($crm_details['data']['start_time']);

                                switch ($Type_OfRoofing) {
                                    case "Asphalt Roofing":
                                        $Type_OfRoofing_data = "Asphalt Shingles";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Asphalt Shingle Roofing - Repair" : "Asphalt Shingle Roofing - Install or Replace");
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $Type_OfRoofing_data = "Wood Shake";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Wood Shake or Composite Roofing - Repair" : "Wood Shake or Composite Roofing - Install or Replace");
                                        break;
                                    case "Metal Roofing":
                                        $Type_OfRoofing_data = "Metal";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Metal Roofing - Repair" : "Metal Roofing - Install or Replace");
                                        break;
                                    case "Natural Slate Roofing":
                                        $Type_OfRoofing_data = "Slate";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Natural Slate Roofing - Repair" : "Natural Slate Roofing - Install or Replace");
                                        break;
                                    case "Tile Roofing":
                                        $Type_OfRoofing_data = "Cement Tile";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Traditional Tile Roofing - Repair" : "Traditional Tile Roofing - Install or Replace");
                                        break;
                                    default:
                                        $Type_OfRoofing_data = "Other or Unknown";
                                        $roof_task = ($project_nature == "Repair existing roof" ? "Flat, Foam or Single Ply Roofing - Repair" : "Flat, Foam, or Single Ply Roofing - Install or Replace");
                                }

                                if (config('app.env', 'local') == "local") {
                                    //Test Mode
                                    $url_api_post = "https://uat.sbbnetinc.com/rest/api/roofing/submit-inquiry-json";
                                    $source_name = "TESTTCPA";
                                } else {
                                    //Live Mode
                                    $url_api_post = "https://live.sbbnetinc.com/rest/api/roofing/submit-inquiry-json";
                                    $source_name = "thrro";
                                }

                                $Lead_data_array_post = array(
                                    "Request" => array(
                                        "API_Action" => "pingPostLead",
                                        "Format" => "JSON",
                                        "Mode" => "post",
                                        "Return_Best_Price" => "1",
                                        "TYPE" => "16",
                                        "IP_Address" => $IPAddress,
                                        "SRC" => $source_name,
                                        "Landing_Page" => $OriginalURL2,
                                        "Sub_ID" => $leadsCustomerCampaign_id,
                                        "Pub_ID" => $google_ts,
                                        "Universal_Lead_ID" => $LeadId,
                                        "Best_Time_To_Call" => "Anytime",
                                        "Property_Zip" => $zip,
                                        "Property_City" => $city,
                                        "Property_State" => $statename_code,
                                        "Roof_Material" => $Type_OfRoofing_data,
                                        "Roof_Task" => $roof_task,
                                        "Property_Owner" => "Yes",
                                        "NoSale" => "No",
                                        "Timing" => "Immediately",
                                        "vendor_lead_id" => $leadsCustomerCampaign_id,
                                        "Lead_ID" => $TransactionId,
                                        "Lead_Cost" => $lead_coust,
                                        "First_Name" => $first_name,
                                        "Last_Name" => $last_name,
                                        "Email" => $email,
                                        "Primary_Phone" => $number1,
                                        "Property_Address" => $street,
                                        "Active_Prospect_URL" => $trusted_form,
                                        "Tcpa_Language" => $TCPAText,
                                    )
                                );

                                $httpheader = array(
                                    'Authorization: Basic cmVzdC11c2VyOjVTOGNCRHEmRWYha3BMKk5XNXVM',
                                    'Content-Type: application/json',
                                );

                                $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
                                $result2 = json_decode($result, true);
                                if (!empty($result2['Status']) && $result2['Status'] === 'Sold') {
                                    return 1;
                                }
                                break;
                        }
                    }
                    break;
                case 32:
                    //Energy Pal 1291

                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                    } else {
                        return 0;
                    }

                    $url_api = "https://api.energypal.com/api/v1/leads/post";
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $Lead_data_array = array(
                        "cid" => "zroimatoeai5b5br",
                        "ping_id" => $TransactionId,
                        "universal_leadid" => $LeadId,
                        "ip_address" => $IPAddress,
                        "tcpa_text" => $TCPAText,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone" => $number1,
                        "email" => $email,
                        "address" => array(
                            "street" => $street,
                            "city" => $city,
                            "state" => $statename_code,
                            "zip" => $zip,
                            "country" => "US",
                        )
                    );

                    switch ($lead_type_service_id) {
                        case 2:
                            //Solar
                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                            $utility_provider = trim($crm_details['data']['utility_provider']);
                            $roof_shade = trim($crm_details['data']['roof_shade']);
                            $property_type = trim($crm_details['data']['property_type']);

                            $homeowner = ($property_type == "Rented" ? "Renter/Not Owner" : "Single Family");
                            switch ($monthly_electric_bill) {
                                case '$0 - $50':
                                    $average_bill = 50;
                                    break;
                                case '$51 - $100':
                                    $average_bill = 100;
                                    break;
                                case '$101 - $150':
                                    $average_bill = 150;
                                    break;
                                case '$151 - $200':
                                    $average_bill = 200;
                                    break;
                                case '$201 - $300':
                                    $average_bill = 300;
                                    break;
                                case '$301 - $400':
                                    $average_bill = 400;
                                    break;
                                case '$401 - $500':
                                    $average_bill = 500;
                                    break;
                                default:
                                    $average_bill = 600;
                            }

                            switch ($roof_shade) {
                                case "Full Sun":
                                    $roof_shade_data = "No Shade";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "A Lot of Shade";
                                    break;
                                case "Partial Sun":
                                    $roof_shade_data = "A Little Shade";
                                    break;
                                default:
                                    $roof_shade_data = "Unsure";
                            }

                            $Lead_data_array['ownership'] = $homeowner;
                            $Lead_data_array['electric_bill'] = $average_bill;
                            $Lead_data_array['electric_utility'] = $utility_provider;
                            $Lead_data_array['roof_shade'] = $roof_shade_data;
                            break;
                    }

                    if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
                        //Test Mode
                        $Lead_data_array['test'] = true;
                        $Lead_data_array['zip'] = "90001";
                        $Lead_data_array['state'] = "CA";
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['status'])) {
                        if ($result2['status'] == "accepted") {
                            return 1;
                        }
                    }
                    break;
                case 34:
                    //	BlueInkDigital

                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {

                        $TransactionId = $data_msg['ping_post_data']['TransactionId'];

                        // Determine Live or Test URL
                        $url_api_post = config('app.env') == "local"
                            ? "https://exchange.standardinformation.io/post_test?legacy=J"
                            : "https://exchange.standardinformation.io/post?legacy=J";

                        // Prepare Base Meta + Contact (common for all)
                        $base_meta = [
                            "landing_page_url"      => $OriginalURL2,
                            "source_id"             => $google_ts,
                            "lead_id_code"          => $LeadId,
                            "trusted_form_cert_url" => $trusted_form,
                            "tcpa_compliant"        => true,
                            "tcpa_consent_text"     => $TCPAText,
                            "user_agent"            => $UserAgent,
                            "originally_created"    => gmdate("Y-m-d\TH:i:s\Z")
                        ];

                        $base_contact = [
                            "first_name"      => $first_name,
                            "last_name"       => $last_name,
                            "email"           => $email,
                            "phone"           => $number1,
                            "phone_last_four" => substr($number1 ?? "", -4),
                            "address"         => $street,
                            "city"            => $city,
                            "state"           => $statename_code,
                            "zip_code"        => $zip,
                            "ip_address"      => $IPAddress,
                        ];

                        // Default header token - will change per case
                        $authToken = null;

                        // Service-specific data mapping
                        switch ($crm_details['service_id']) {

                            case 1: // Windows
                                $num = trim($crm_details['data']['number_of_window']);
                                $num = $num == "3-5" ? "5" : ($num == "6-9" ? "9" : ($num ?: "10"));
                                $projectType = trim($crm_details['data']['project_nature']) == "Repair"
                                    ? "Need repair services at this time"
                                    : "Interested in replacement windows";
                                $own_property = trim($crm_details['data']['homeOwn']) != "Yes";

                                $service_data = [
                                    "windows" => [
                                        "num_windows"  => $num,
                                        "project_type" => $projectType
                                    ]
                                ];

                                $authToken = "e373e16a-0bfb-4a2d-ad36-48794909cfe0";
                                break;

                            case 6: // Roofing
                                $roof_types = [
                                    "Asphalt Roofing" => "Asphalt shingle",
                                    "Wood Shake/Composite Roofing" => "Composite",
                                    "Metal Roofing" => "Metal",
                                    "Natural Slate Roofing" => "Natural slate",
                                    "Tile Roofing" => "Tile"
                                ];

                                $roof_selected = $roof_types[$crm_details['data']['roof_type']] ?? "Not Sure";
                                $project_type = trim($crm_details['data']['project_nature']) == "Repair existing roof" ? "Repair" : "New roof for new home";
                                $own_property = trim($crm_details['data']['property_type']) != "Rented";

                                $service_data = [
                                    "roof" => [
                                        "project_type" => $project_type,
                                        "roofing_type" => $roof_selected
                                    ]
                                ];

                                $authToken = "249c60d5-8890-460f-bdef-7b2cbcf33f45";
                                break;

                            case 7: // Home Siding
                                $map_siding = [
                                    "Vinyl Siding" => "Vinyl",
                                    "Brickface Siding" => "Brick or stone",
                                    "Stoneface Siding" => "Brick or stone",
                                    "Composite wood Siding" => "Wood"
                                ];

                                $project_type = trim($crm_details['data']['project_nature']) == "Repair section(s) of siding"
                                    ? "Siding repair" : "Replace siding";

                                $service_data = [
                                    "siding" => [
                                        "siding_type"  => $map_siding[$crm_details['data']['type_of_siding']] ?? "Other",
                                        "project_type" => $project_type,
                                    ]
                                ];

                                $own_property = trim($crm_details['data']['homeOwn']) == "Yes";

                                $authToken = "556bce96-7811-4739-b2ce-8a0bd72e9a08";
                                break;

                            case 9: // Bathroom
                                $map_bathroom = [
                                    "Full Remodel" => "Full bathroom",
                                    "Shower / Bath" => "Bathtub/Shower Updates",
                                    "Sinks" => "Bath sinks"
                                ];

                                $service_data = [
                                    "bathroom" => [
                                        "project_type" => $map_bathroom[$crm_details['data']['services']] ?? "Not Sure",
                                    ]
                                ];

                                $own_property = trim($crm_details['data']['homeOwn']) == "Yes";

                                $authToken = "e8ad25b7-3a32-4b9d-9b13-60733f60b46d";
                                break;
                        }

                        // Final Data
                        $Lead_data_array_post = [
                            "auth_code" => $TransactionId,
                            "data" => array_merge($service_data, [
                                "own_property"        => $own_property,
                                "best_call_time"      => "Anytime",
                                "purchase_time_frame" => "Immediately",
                                "credit_rating"       => "Good",
                            ]),
                            "meta"    => $base_meta,
                            "contact" => $base_contact
                        ];

                        $httpheader = [
                            "Authorization: Token $authToken",
                            'Content-Type: application/json',
                            'Accept: application/json',
                        ];

                        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
                        $result2 = json_decode($result, true);
                        if (!empty($result2['status'])) {
                            if ($result2['status'] == "success") {
                                return 1;
                            }
                        }

                    }
                    break;

            }
            return 0;
        } catch (Exception $e) {

        }
    }
}
