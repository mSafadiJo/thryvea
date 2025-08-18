<?php

namespace App\Services\Zone;

use App\Services\CrmApi;
use Illuminate\Support\Facades\Log;

class PostCRMZone
{
    public function callTools($data_msg, $crm_details)
    {
        if (empty($crm_details['callTollsDetails'])) {
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "callTollsDetails"));
            return 0;
        }
        $crm_api_file = new CrmApi();

        $Lead_data_array = array(
            'auth_token' => $crm_details['callTollsDetails']['api_key'],
            'title' => ucfirst($crm_details['service_campaign_name']),
            'company_name' => ucfirst($crm_details['service_campaign_name']),
            'first_name' => $data_msg['first_name'],
            'last_name' => $data_msg['last_name'],
            'email_address' => $data_msg['LeadEmail'],
            'zip_code' => $data_msg['Zipcode'],
            'address' => $data_msg['street'],
            'city' => $data_msg['City'],
            'state' => $data_msg['State'],
            'phone_number' => $data_msg['LeadPhone'],
            'mobile_number' => $data_msg['LeadPhone'],
            'dial_duplicate' => 1,
            'note' => "Lead From " .  config('app.name', '')
        );

        if (!empty($crm_details['callTollsDetails']['file_id'])) {
            $Lead_data_array['file'] = $crm_details['callTollsDetails']['file_id'];
            $Lead_data_array['hot_lead'] = 1;
        }

        $url_api = "https://app.calltools.com/api/contacts/";

        $leadsCustomerCampaign_id = $data_msg['leadCustomer_id'];

        $url_api = str_replace(" ", "%20", $url_api);

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        $from_job = (!empty($crm_details['from_job']) ? 1 : 0);

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id'], $from_job);
        return 1;
    }

    public function five9Crm($data_msg, $crm_details)
    {
        if (empty($crm_details['Five9Details'])) {
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "Five9Details"));
            return 0;
        }
        //Five9 CRM
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
        $url_api = str_replace(" ", "+", $url_api);

        $httpheader = array();

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "GET", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function leadsPedia($data_msg, $crm_details)
    {
        if (empty($crm_details['Leads_PediaDetails'])) {
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

        $lp_campaign_id = $crm_details['Leads_PediaDetails']['IP_Campaign_ID'];
        $lp_campaign_key = $crm_details['Leads_PediaDetails']['campine_key'];
        $lp_url = $crm_details['Leads_PediaDetails']['leads_pedia_url'];

        $LeadId = $data_msg['LeadId'];
        $IPAddress = $data_msg['IPAddress'];

        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_home=$number1&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&county=$county&email_address=$email&trusted_form=$trusted_form";

        if ($crm_details['is_ping_account'] == 1) {
            if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                $TransactionId = $data_msg['ping_post_data']['TransactionId'];
                $url_api .= "&lp_ping_id=$TransactionId";
            } else {
                return 0;
            }
        }

        if (config('app.env', 'local') == "local") {
            //Test Mode
            $url_api .= "&lp_test=1";
        }

        $leadsCustomerCampaign_id = '';
        if (!empty($crm_details['leadsCustomerCampaign_id'])) {
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        }

        $url_api = str_replace(" ", "%20", $url_api);

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, '', "GET",  1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        $status = 0;
        if (!empty($result2['result'])) {
            if ($result2['result'] == 'success' || $result2['msg'] == 'Lead Accepted') {
                $status = 1;
            }
        }
        return $status;
    }

    public function hubspot($data_msg, $crm_details)
    {
        if (empty($crm_details['hubspotDetails'])) {
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "hubspotDetails"));
            return 0;
        }

        $crm_api_file = new CrmApi();
        $first_name = $data_msg['first_name'];
        $last_name = $data_msg['last_name'];
        $email_address = $data_msg['LeadEmail'];
        $address = $data_msg['street'];
        $zip_code = $data_msg['Zipcode'];
        $city = $data_msg['City'];
        $state = $data_msg['State'];
        $phone_number = $data_msg['LeadPhone'];
        $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
        $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
        $app_name =  config('app.name', '');
        $sourceAndBidType = "$app_name $listOFCampainDB_type";

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
        if ($crm_details['hubspotDetails']['key_type'] == 0) {
            $url_api .= "?hapikey=" . $crm_details['hubspotDetails']['Api_Key'];
        }

        $httpheader = array(
            "cache-control: no-cache",
            "Accept: application/json",
            "content-type: application/json"
        );

        if ($crm_details['hubspotDetails']['key_type'] == 1) {
            $httpheader[] = "Authorization: Bearer " . $crm_details['hubspotDetails']['Api_Key'];
        }

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if (!empty($result2['status'])) {
            if ($result2['status'] == "error") {
                return 0;
            }
        }
        return 1;
    }

    public function Pipdrive($data_msg, $crm_details)
    {
        if (empty($crm_details['pipedriveDetails'])) {
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
            'first_name' => $data_msg['first_name'],
            'last_name' => $data_msg['last_name'],
            'email_address' => $data_msg['LeadEmail'],
            'phone_number' => $data_msg['LeadPhone'],
        );

        $first_name = ucwords($data_msg['first_name']);
        $last_name = ucwords($data_msg['last_name']);
        $mail = $data_msg['LeadEmail'];
        $phone = $data_msg['LeadPhone'];

        $street = ucwords(trim($data_msg['street']));
        $city = ucwords(trim($data_msg['City']));
        $state = ucwords(trim($data_msg['State']));
        $state_code = trim($data_msg['state_code']);
        $Zipcode = trim($data_msg['Zipcode']);

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
        if ($is_person == 1) {
            $Lead_data_array = array(
                'name'  => $name,
                'email' => $mail,
                'phone' => $phone,
            );

            // ================================== //
            // API PERSONS domain
            // ================================== //
            $url_api = 'https://' . $person_domain . '.pipedrive.com/v1/persons?api_token=' . $api_token;

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
                            'person_id' => $person_id
                        );

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

    public function Jangle($data_msg, $crm_details)
    {
        if (empty($crm_details['JangleDetails'])) {
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
            if ($tcpa_compliant == 1) {
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

            switch ($crm_details['service_id']) {
                case 1:
                    //windows
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $number_of_windows = trim($crm_details['data']['number_of_window']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                    $project_nature_data = ($project_nature == "Repair" ? "Need repair services at this time" : "Interested in replacement windows");

                    switch ($number_of_windows) {
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
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $SecurityUsage = ($property_type == "Owned" ? "true" : "false");

                    switch ($monthly_electric_bill) {
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

                    switch ($roof_shade) {
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
                    //home Security
                    $Installation_Preferences = trim($crm_details['data']['Installation_Preferences']);
                    $lead_have_item_before_it = trim($crm_details['data']['lead_have_item_before_it']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($property_type) {
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

                    switch ($Type_OfFlooring) {
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
                    //Roofing
                    $roof_type = trim($crm_details['data']['roof_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                    switch ($roof_type) {
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

                    switch ($project_nature) {
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

                    switch ($type_of_siding) {
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

                    switch ($bathroom_type_name) {
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

                    switch ($type_of_heating) {
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

                    switch ($type_of_heating) {
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

                    switch ($services) {
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

                    switch ($service_type) {
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

    public function leadPerfectionCrm($data_msg, $crm_details)
    {
        //leadperfection
        if (empty($crm_details['leadPerfectionCrm'])) {
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

        $sender =  config('app.name', '');
        if (!empty($sender_data)) {
            $sender = $sender_data;
        }

        $url_api = "$url_link?firstname=$first_name&lastname=$last_name&address1=$street&city=$city&state=$statename_code&zip=$zip&phone1type=$phone1type&phone1=$number1&phone2type=$phone2type&phone3type=$phone3type&email=$email&LogNumber=$LogNumber&sender=$sender&sentto=$sentto&srs_id=$srs_id";
        if (!empty($pro_id)) {
            $url_api .= "&pro_id=$pro_id&productid=$pro_id";
        }
        if (!empty($pro_desc)) {
            $url_api .= "&proddescr=$pro_desc";
        }

        $httpheader = array(
            "content-type: application/json"
        );
        $url_api = str_replace(" ", "%20", $url_api);
        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . strtolower($result), 'ok') == true) {
            return 1;
        }
        return 0;
    }

    public function Improveit360Crm($data_msg, $crm_details)
    {
        //Improveit360Crm
        if (empty($crm_details['Improveit360Crm'])) {
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

        $source = $crm_details['Improveit360Crm']['improveit360_source'];
        $url_link = $crm_details['Improveit360Crm']['improveit360_url'];
        $market_segment = $crm_details['Improveit360Crm']['market_segment'];
        $source_type_imp = $crm_details['Improveit360Crm']['source_type'];
        $project_imp = $crm_details['Improveit360Crm']['project'];

        $source_type = "Websites";
        if (!empty($source_type_imp)) {
            $source_type = $source_type_imp;
        }

        $project = $crm_details['service_campaign_name'];
        if (!empty($project_imp)) {
            $project = $project_imp;
        }

        $url_api = "$url_link?FirstName=$first_name&LastName=$last_name&StreetAddress=$street&City=$city&State=$statename_code&Zip=$zip&Email=$email&Phone1=$number1&Phone1Type=$phone1type&Product=$project&Date=$date&Source=$source&SourceType=$source_type";

        if (!empty($market_segment)) {
            $url_api .= "&i360__Market_Segment_Editable__c=$market_segment&MarketSegment=$market_segment";
        }

        switch ($crm_details['buyer_id']) {
            case 396:
                // 396 For Energy
                $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
                if ($listOFCampainDB_type == "Exclusive") {
                    $sharedexclusive = true;
                    $url_api .= "&Exclusive__c=$sharedexclusive";
                }
                break;
        }

        $httpheader = array(
            "content-type: application/json"
        );

        $url_api = str_replace(" ", "%20", $url_api);
        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . strtolower($result), 'success') == true) {
            return 1;
        }
    }

    public function LeadConduitCrm($data_msg, $crm_details)
    {
        //LeadConduitCrm
        if (empty($crm_details['LeadConduitCrm'])) {
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

        switch ($crm_details['service_campaign_name']) {
            case "Window":
                $service_name = "windows";
                break;
            case "Home Siding":
                $service_name = "siding";
                break;
            case "Bathroom":
                $service_name = "Bath";
                break;
            default:
                $service_name = strtolower($crm_details['service_campaign_name']);
                break;
        }

        $Lead_data_array = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "address_1" => $street,
            "city" => $city,
            "state" => $statename_code,
            "postal_code" => $zip,
            "trustedform_cert_url" => $trusted_form,
            "phone_1" => $number1,
            "universal_leadid" => $LeadId,
            "product_id_dabel" => $service_name,
            "log_number_dabel" => $service_name,
            "ip_address" => $IPAddress,
            "user_agent" => $UserAgent,
        );

        switch ($crm_details['buyer_id']) {
            case 40:
                //Long Roofing 145
                $Lead_data_array['product'] = $service_name;
                break;
            case 74:
                // StateWide Remodeling 74
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $service_name = "Windows";
                        break;
                    case "Bathroom":
                        $service_name = "WetAreaBth";
                        break;
                }
                $Lead_data_array['price'] = $Lead_Cost;
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['original_source'] = $lead_source_text;
                $Lead_data_array['2819'] = "SubSource";
                $Lead_data_array['SubSource'] = "2819";
                $Lead_data_array['srs_id'] = "2819";
                break;
            case 75:
                // Paradise Home improvement 75
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $service_name = "Win";
                        break;
                }

                $Lead_data_array['lead_cost_type_para'] = "CPL";
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['285'] = "SubSource";
                $Lead_data_array['SubSource'] = "285";
                $Lead_data_array['srs_id'] = "285";
                break;
            case 76:
                // FHIA Remodeling 76
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $service_name = "W/D";
                        break;
                }

                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['cpl_amount_fhiar'] = "CPL";
                $Lead_data_array['original_source'] = $lead_source_text;
                $Lead_data_array['938'] = "SubSource";
                $Lead_data_array['SubSource'] = "938";
                $Lead_data_array['srs_id'] = "938";
                break;
            case 29:
            case 227:
            case 318:
                //C Michael Exteriors 29
                //CM Exteriors 227
                //C Michael's 318
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $srs_id = "647";
                        $service_name = "Win";
                        break;
                    case "Bathroom":
                        $srs_id = "648";
                        $service_name = "Bath";
                        break;
                    case "Home Siding":
                        $srs_id = "725";
                        $service_name = "Sid";
                        break;
                    default:
                        $srs_id = "";
                }

                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['srs_id'] = $srs_id;
                $Lead_data_array['notes'] = $leadsCustomerCampaign_id;
                $Lead_data_array['product_id_dabel'] = "";
                $Lead_data_array['log_number_dabel'] = "";
                break;
            case 58:
                //florida window and doors 58
                switch ($crm_details['campaign_id']) {
                    case 1329:
                        $marketSegm = "Florida Window and Door - Tampa";
                        break;
                    case 1328:
                        $marketSegm = "Florida Window and Door - Daytona-NewSmyrna";
                        break;
                    case 1327:
                        $marketSegm = "Florida Window and Door - PalmBeach-Melbourne";
                        break;
                    case 1326:
                        $marketSegm = "Florida Window and Door - Miami-FtLauderdale";
                        break;
                    case 1325:
                        $marketSegm = "Florida Window and Door - FM";
                        break;
                }

                $Lead_data_array['marketsegment_flwd'] = $marketSegm;
                $Lead_data_array['sourcetype_flwd'] = "Internet";
                break;
            case 115:
                //Improveit USA	115
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $service_name = "windows";
                        $number_of_windows = trim($crm_details['data']['number_of_window']);
                        $project_nature = trim($crm_details['data']['project_nature']);
                        $comments = "$number_of_windows, $project_nature";
                        break;
                    case "Bathroom":
                        $service_name = "baths";
                        $bathroom_type_name = trim($crm_details['data']['services']);
                        $comments = "$bathroom_type_name";
                        break;
                    default:
                        $comments = "";
                }

                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['price'] = $Lead_Cost;
                $Lead_data_array['vendor_lead_id_impro'] = $google_ts;
                $Lead_data_array['comments'] = $comments;
                break;
            case 137:
                //Erus Energy 137
                switch ($crm_details['service_id']) {
                    case 2:
                        //Solar
                        $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                        $utility_provider = trim($crm_details['data']['utility_provider']);

                        switch ($monthly_electric_bill) {
                            case '$0 - $50':
                            case '$51 - $100':
                            case '$101 - $150':
                                $average_bill = '$150';
                                break;
                            case '$151 - $200':
                                $average_bill = '150-200';
                                break;
                            default:
                                $average_bill = '$200+';
                        }
                        $Lead_data_array['utility.electric.monthly_amount'] = $average_bill;
                        $Lead_data_array['utility.electric.company.name'] = $utility_provider;
                        break;
                }
                break;
            case 146:
                //Aesops Gables 146
                $Lead_data_array['srs_id'] = "511";
                switch ($crm_details['service_id']) {
                    case 8:
                        //Kitchen
                        $Lead_data_array['product'] = "REFACE";
                        break;
                    case 9:
                        //Bathroom
                        $Lead_data_array['product'] = "BPBATH";
                        break;
                }
                break;
            case 42:
                //Harley Exteriors Inc. 42
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['srs_id'] = "1488";
                break;
            case 205:
                // 205 Zintex remodeling group
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['original_source'] = $lead_source_text;
                $Lead_data_array['source_zintk'] = "Zone1 Remodeling";
                $Lead_data_array['source_group_zintk'] = "Website Aggregators";
                $Lead_data_array['source_type_zintk'] = "Lead Aggregators";
                $Lead_data_array['lead_id_zintk'] = $leadsCustomerCampaign_id;
                $Lead_data_array['phone_1.is_tollfree'] = false;
                break;
            case 215:
                //Alenco Inc 215
                switch ($crm_details['service_campaign_name']) {
                    case "Window":
                        $product_id_alen = "WIN";
                        break;
                    case "Bathroom":
                    default:
                        $product_id_alen = "LUX";
                }

                $Lead_data_array['product_id_alen'] = $product_id_alen;
                $Lead_data_array['notes_alen'] = $service_name;
                $Lead_data_array['sender_alen'] = "Zone1Remodeling";
                break;
            case 208:
            case 376:
                //PosiGen 208
                //Prime Energy Solar, LLC 376
                switch ($crm_details['service_id']) {
                    case 2:
                        //Solar
                        $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                        $utility_provider = trim($crm_details['data']['utility_provider']);
                        $roof_shade = trim($crm_details['data']['roof_shade']);

                        switch ($roof_shade) {
                            case "Full Sun":
                                $roof_shade_data = "full sun";
                                break;
                            case "Mostly Shaded":
                                $roof_shade_data = "mostly shaded";
                                break;
                            case "Partial Sun":
                                $roof_shade_data = "partial sun";
                                break;
                            default:
                                $roof_shade_data = "low sun";
                        }

                        switch ($monthly_electric_bill) {
                            case '$0 - $50':
                            case '$51 - $100':
                            case '$101 - $150':
                                $average_bill = '$150';
                                break;
                            case '$151 - $200':
                                $average_bill = '150-200';
                                break;
                            default:
                                $average_bill = '$200+';
                        }

                        $Lead_data_array['utility.electric.monthly_amount'] = $average_bill;
                        $Lead_data_array['utility.electric.company.name'] = $utility_provider;
                        $Lead_data_array['property.sun_exposure'] = $roof_shade_data;
                        $Lead_data_array['country'] = "United States";
                        $Lead_data_array['leadid_id'] = $LeadId;
                        $Lead_data_array['price'] = $Lead_Cost;
                        $Lead_data_array['ip_address'] = "";
                        break;
                }
                break;
            case 219:
                // 219 Florida Windows Door
                $Lead_data_array['marketsegment_flwd'] = $campaign_name;
                $Lead_data_array['sourcetype_flwd'] = "Internet";
                $Lead_data_array['product'] = $crm_details['service_campaign_name'];
                $Lead_data_array['campaign_id'] = $leadsCustomerCampaign_id;
                $Lead_data_array['service'] = (!empty($crm_details['data']['project_nature']) ? trim($crm_details['data']['project_nature']) : "");
                break;
            case 231:
                //American Standard 231
                $seo_source = array("Search", "Verified", "SEO", "SEM", "SEM-A", "Youtube");
                switch ($crm_details['service_id']) {
                    case 5:
                        if (in_array($lead_source_name, $seo_source)) {
                            $subId_stc = "7850";
                            $pubid_stc = "Hosted-Zone1 Remodel";
                        } else {
                            $subId_stc = "7851";
                            $pubid_stc = "Hosted-Zone1 Remodel-Tier2";
                        }
                        break;
                    case 9:
                        if (in_array($lead_source_name, $seo_source)) {
                            $subId_stc = "7854";
                            $pubid_stc = "BR-Host-Zone1 Remodel";
                        } else {
                            $subId_stc = "7855";
                            $pubid_stc = "BR-Host-Zone1 Remodel-Tier2";
                        }
                        break;
                    default:
                        $subId_stc = "";
                        $pubid_stc = "";
                }

                $Lead_data_array['subid_stc'] = $subId_stc;
                $Lead_data_array['pubid_stc'] = $pubid_stc;
                $Lead_data_array['leadid_stc'] = $LeadId;
                $Lead_data_array['program_type_stc'] = "CPL";
                $Lead_data_array['cpl_stc'] = $Lead_Cost;
                $Lead_data_array['terms_stc'] = $TCPAText;
                $Lead_data_array['product_id_dabel'] = "";
                $Lead_data_array['log_number_dabel'] = "";
                break;
            case 243:
                //Expo Home 243
                $serviceName = "";
                $serviceDescription = "";
                switch ($crm_details['service_id']) {
                    case 1:
                        $serviceName = "Windows";
                        $project_nature = trim($crm_details['data']['project_nature']);
                        $serviceDescription = ($project_nature == 'Install' ? 'Install' : 'Replace');
                        break;
                    case 5:
                        $serviceName = "Walk-In Tubs";
                        break;
                    case 9:
                        $serviceName = "Bathroom - Remodel";
                        $bathroom_type_name = trim($crm_details['data']['services']);
                        switch ($bathroom_type_name) {
                            case "Cabinets / Vanity":
                                $serviceDescription = "cabinets_vanity";
                                break;
                            case "Countertops":
                                $serviceDescription = "countertops";
                                break;
                            case "Flooring":
                                $serviceDescription = "flooring";
                                break;
                            case "Shower / Bath":
                                $serviceDescription = "shower_bath";
                                break;
                            case "Full Remodel":
                            default:
                                $serviceDescription = "full_remodel";
                        }
                        break;
                }

                $Lead_data_array['product_name_dve'] = $serviceName;
                $Lead_data_array['url_dve'] = $OriginalURL2;
                $Lead_data_array['ext_campaign_id_dve'] = $leadsCustomerCampaign_id;
                $Lead_data_array['vendor_lead_campaign_dve'] = $campaign_name;
                $Lead_data_array['cost_dve'] = $Lead_Cost;
                $Lead_data_array['taskname_dve'] = $serviceName;
                $Lead_data_array['leaddescription_dve'] = $serviceDescription;
                $Lead_data_array['spcompanyname_dve'] = "Z1 Remodeling";
                $Lead_data_array['price'] = $Lead_Cost;
                break;
            case 273:
                //273 Homefix Custom Remodeling
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['generated_date_hfix'] = date('Y-d-m H:i:s');
                $Lead_data_array['lead_source_hfix'] = "Zone 1 Remodel";
                $Lead_data_array['campaign_name'] = "Zone 1 Remodel";
                $Lead_data_array['company.name'] = "Zone 1 Remodel";
                $Lead_data_array['country'] = "United States";

                if ($crm_details['campaign_Type'] == 4) {
                    if (!empty($data_msg['appointment_date'])) {
                        $appointment_date = date('Y-m-d', strtotime($data_msg['appointment_date']));
                        $appointment_time = date('h:i A', strtotime($data_msg['appointment_date']));
                        $appointment_datetime = date('m/d/Y h:i:s A', strtotime($data_msg['appointment_date']));

                        $Lead_data_array['appointment_time_hfix'] = $appointment_time;
                        $Lead_data_array['appointment_date_hfix'] = $appointment_date;
                        $Lead_data_array['appointment'] = $appointment_datetime;
                    }
                }
                break;
            case 292:
                // 292 Bath Planet of Des Moines
                $Lead_data_array['srs_id'] = "5133";
                break;
            case 293:
                // 293 Bath Planet of Cedar Rapids
                $Lead_data_array['srs_id'] = "5131";
                break;
            case 294:
                // 294 Bath Planet of Grand Rapids
                $Lead_data_array['srs_id'] = "5132";
                break;
            case 295:
                // 295 Bath Planet of Quad Cities
                $Lead_data_array['srs_id'] = "5134";
                break;
            case 301:
                //Joyce Factory Direct 301
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['price'] = $Lead_Cost;
                $Lead_data_array['original_source'] = $google_ts;
                break;
            case 257:
                //East Coast Roofing 257
                switch ($crm_details['service_id']) {
                    case 1:
                        $service_name = "Windows";
                        break;
                    case 6:
                        $service_name = "Roofing";
                        break;
                    case 7:
                        $service_name = "Siding";
                        break;
                }
                $Lead_data_array['service_type_ecrsw'] = $service_name;
                $Lead_data_array['cost_ecrsw'] = $Lead_Cost;
                break;
            case 291:
                //LEI Home Enhancements 291
                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['original_source'] = $google_ts;
                $Lead_data_array['market_segment_lei'] = "Richmond";
                break;
            case 320:
                // Empire Today 320
                $Lead_Cost_float = number_format($Lead_Cost, 1);
                $owns_home_emp = "yes";
                if (!empty($crm_details['data']['homeOwn'])) {
                    $owns_home_emp = ($crm_details['data']['homeOwn'] == "Yes" ? "yes" : "no");
                }

                $Lead_data_array['product'] = $service_name;
                $Lead_data_array['comments'] = "Zone1remodeling";
                $Lead_data_array['price'] = "$$Lead_Cost_float";
                $Lead_data_array['exclusivity_emp'] = $Lead_Cost_type;
                $Lead_data_array['country'] = 'US';
                $Lead_data_array['owns_home_emp'] = $owns_home_emp;
                $Lead_data_array['original_source'] = $google_ts;
                break;
            case 321:
                if (strpos("-" . strtolower($campaign_name), 'FULL') == true) {
                    $Lead_data_array['product_id_dabel'] = "Bath Full";
                } else {
                    $Lead_data_array['product_id_dabel'] = "Bath";
                }
                break;
            case 415:
                // 415 All American Roofing and Remodeling
                $Lead_Cost_float = number_format($Lead_Cost, 1);
                $Lead_data_array['pro_id'] = "Roof";
                $Lead_data_array['product_id_aarw'] = "Roof";
                $Lead_data_array['reference'] = $google_ts;
                $Lead_data_array['comments'] = "Zone1Remodeling";
                $Lead_data_array['price'] = "$$Lead_Cost_float";
                $Lead_data_array['original_source'] = $lead_source_text;
                break;

            case 449:
                //Ion Solar
                $Lead_data_array['reference'] = $google_ts;
                $Lead_data_array['original_source'] = $lead_source_text;
                $Lead_data_array['company.name'] = "Zone 1 Remodel";
                $Lead_data_array['lead_source_ion'] = "Zone 1 Remodel";
                $Lead_data_array['state_ion'] = $statename_code;
                $Lead_data_array['price'] = $Lead_Cost;
                switch ($crm_details['service_id']) {
                    case 2:
                        //Solar
                        $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                        $utility_provider = trim($crm_details['data']['utility_provider']);
                        $Lead_data_array['avg_mo_electric_bill_ion'] = $monthly_electric_bill;
                        $Lead_data_array['utility_company_text_ion'] = $utility_provider;
                        break;
                }
                break;
        }

        if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
            //Test Mode
            $Lead_data_array['is_test'] = true;
        }

        $httpheader = array(
            "content-type: application/json"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . $result, 'success') == true) {
            return 1;
        }
        return 0;
    }

    public function MarketsharpmCrm($data_msg, $crm_details)
    {
        if (empty($crm_details['MarketsharpmCrm'])) {
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

        switch ($crm_details['buyer_id']) {
            case 133:
                //Eco View Windows of San Antonio 133
                switch ($crm_details['service_id']) {
                    case 1:
                        //windows
                        $number_of_windows = trim($crm_details['data']['number_of_window']);
                        $MSM_custom_Interests = $number_of_windows;
                        break;
                }
                break;
            case 131:
                //Clear Choice Home Improvements 131
                switch ($crm_details['service_id']) {
                    case 1:
                        //windows
                        $MSM_custom_Interests = "Windows";
                        break;
                    case 6:
                        //Roofing
                        $roofingType = trim($crm_details['data']['roof_type']);
                        $MSM_custom_Interests = $roofingType;
                        break;
                }
                break;
        }

        $url_api = "https://haaws.marketsharpm.com/LeadCapture/MarketSharp/LeadCapture.ashx?MSM_source=$MSM_source&MSM_coy=$MSM_coy&MSM_formId=$MSM_formId&MSM_leadCaptureName=$MSM_leadCaptureName&MSM_firstname=$MSM_firstname&MSM_lastname=$MSM_lastname&MSM_homephone=$MSM_homephone&MSM_cellphone=$MSM_homephone&MSM_zip=$MSM_zip&MSM_address1=$MSM_address1&MSM_city=$MSM_city&MSM_email=$MSM_email&MSM_state=$MSM_state&MSM_custom_Interests=$MSM_custom_Interests";

        if (config('app.env', 'local') == "local" || strtolower($MSM_firstname) == "test" || strtolower($MSM_lastname) == "test") {
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

    public function leadPortalCrm($data_msg, $crm_details)
    {
        if (empty($crm_details['leadPortalCrm'])) {
            Log::info('Campaign Error Massage', array('campaign_id' => $crm_details['campaign_id'], 'CRM_type' => "leadPortalCrm"));
            return 0;
        }
        try {
            $crm_api_file = new CrmApi();

            $Lead_data_array = array(
                'PostUrl' => $crm_details['leadPortalCrm']['api_url'],
                'SRC' => $crm_details['leadPortalCrm']['SRC'],
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
            if ($tcpa_compliant == 1) {
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

            switch ($crm_details['service_id']) {
                case 1:
                    //windows
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $number_of_windows = trim($crm_details['data']['number_of_window']);

                    if ($project_nature == "Repair") {
                        $Project = "Window Repair";
                    } else {
                        $Project = ($number_of_windows == 1 ? "Window Install - Single" : "Window Install - Multiple");
                    }
                    break;
                case 2:
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

                    switch ($crm_details['buyer_id']) {
                        case 214:
                            //214 Colossus Solar
                            switch ($monthly_electric_bill) {
                                case '$0 - $50':
                                case '$51 - $100':
                                    $average_bill = '$0-$99';
                                    break;
                                case '$101 - $150':
                                    $average_bill = '$100-$150';
                                    break;
                                case '$151 - $200':
                                    $average_bill = '$151-$200';
                                    break;
                                case '$201 - $300':
                                    $average_bill = '$201-$300';
                                    break;
                                case '$301 - $400':
                                    $average_bill = '$301-$400';
                                    break;
                                case '$401 - $500':
                                    $average_bill = '$401-$500';
                                    break;
                                default:
                                    $average_bill = '$501-$600';
                            }
                            break;
                        default:
                            switch ($monthly_electric_bill) {
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
                            break;
                    }

                    switch ($roof_shade) {
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
                    //Home security
                    $Project = "Home Security";
                    break;
                case 4:
                    //Flooring
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Project = "Flooring";
                    break;
                case 5:
                    //WALK-IN TUBS
                    $Project = "Walk-in Tub";
                    break;
                case 6:
                    //Roofing
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Project = "Roofing";
                    break;
                case 7:
                    //Home Siding
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Project = "Siding - Install or Replace";
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
                    $Project = ($project_nature == "Repair" ? "Furnace / Heating System - Repair/Service" : "Furnace / Heating System - Install/Replace");
                    break;
                case 12:
                    //Boiler
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Project = ($project_nature == "Repair" ? "Boiler or Radiator System - Service/Repair" : "Boiler Or Radiator System - Install/Replace");
                    break;
                case 13:
                    //Central A/C
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $Project = ($project_nature == "Repair" ? "Central A/C - Repair/Service" : "Central A/C - Install/Replace");
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
                    $Project = "Gutter Install/Repair";
                    break;
                case 23:
                    //Painting
                    $Project = "Painting";
                    break;
            }

            if ($crm_details['service_id'] == 2) {
                $type = "26";
                if (!empty($Lead_data_array['type'])) {
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
            } else if ($crm_details['service_id'] == 24) {
                $type = (!empty($Lead_data_array['type']) ? $Lead_data_array['type'] : "33");
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
                        "Driver_1_Marital_Status" => ($married == "Yes" ? "Married" : "Single"),
                        "Driver_1_Credit_Rating" => "Good",
                        "Driver_1_License_Status" => ($license == "Yes" ? "Active" : "Expired"),
                        "Driver_1_Licensed_State" => $statename_code,
                        "Driver_1_Education" => "Unknown",
                        "Driver_1_Occupation" => "Other/Not Listed",
                        "Driver_1_Age_When_First_Licensed" => 18,
                        "Driver_1_Filing_Required" => ($SR_22_need == "Yes" ? "SR-22" : "None"),
                        "Driver_1_Current_Residence" => ($ownership == "Yes" ? "Own" : "Rent"),
                        "Driver_1_Tickets_Accidents_Claims_Past_3_Years" => ($number_of_tickets == "0" ? "No" : "Yes"),
                        "Driver_1_Insured_Past_30_Days" => "Yes",
                        "Driver_1_Bankruptcy_In_Past_5_Years" => "No",
                        "Driver_1_Additional_Drivers" => $driversNum,
                        "Driver_1_Additional_Vehicles" => $more_than_one_vehicle,
                        "Driver_1_Reposessions_In_The_Past_5_Years" => "Unknown",
                        "Driver_1_DUI_DWI_In_The_Past_5_Years" => ($DUI_charges == "Yes" ? "Yes" : "No"),

                        "Driver_1_First_Name" => $first_name,
                        "Driver_1_Last_Name" => $last_name,
                        "Lead_ID" => $TransactionId,
                        "Driver_1_Email" => $email,
                        "Driver_1_Daytime_Phone" => $number1,
                        "Driver_1_City" => $city,
                        "Driver_1_Address" => $street
                    )
                );
            } else {
                if (!empty($crm_details['data']['homeOwn'])) {
                    $ownership = ($crm_details['data']['homeOwn'] != "Yes" ? "No" : "Yes");
                } else {
                    if (!empty($crm_details['data']['property_type'])) {
                        $ownership = ($crm_details['data']['property_type'] == "Rented"  ? "No" : "Yes");
                    } else {
                        $ownership = "Yes";
                    }
                }

                $type = "18";
                if (!empty($Lead_data_array['type'])) {
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
            }

            if (config('app.env', 'local') == "local") {
                //Test Mode
                if ($crm_details['service_id'] != 2) {
                    $Lead_data_array_post['Request']['Project'] = "Alarm/ security system - Install";
                }

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

    public function leads_pedia_track($data_msg, $crm_details)
    {
        if (empty($crm_details['leads_pedia_track'])) {
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
            $timestamp = date('Y-m-d H:i:s');
            $leadsCustomerCampaign_id = $crm_details['leadsCustomerCampaign_id'];
            $tcpa_compliant = $data_msg['tcpa_compliant'];
            $google_ts = $data_msg['google_ts'];
            $lead_source_name = $data_msg['lead_source_name'];
            if ($tcpa_compliant == 1) {
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

            $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=$lead_source_text&lp_response=JSON&city=$city&state=$state&zip_code=$zip&first_name=$first_name&last_name=$last_name&address=$street&phone_home=$number1&phone_cell=$number1&email_address=$email&ip_address=$IPAddress";

            switch ($crm_details['buyer_id']) {
                case 61:
                    //Trinity Solar 61
                    $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
                    if ($listOFCampainDB_type == "Exclusive") {
                        $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
                        $Lead_Cost_type = "Exclusive";
                    } else {
                        $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
                        $Lead_Cost_type = "Shared";
                    }

                    $url_api .= "&lp_cost=$Lead_Cost";

                    //Check if it is warm transfer
                    if ($crm_details['campaign_Type'] == 6) {
                        $url_api .= "&lp_caller_id=$number1";
                    }
                    break;
                case 234:
                    //Strategic Solar Solutions 234
                    $UserAgent = (!empty($UserAgent) ? $UserAgent : "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36");
                    $url_api .= "&lead_source=$google_ts&user_agent=$UserAgent&tf_url=$trusted_form&jornaya_lead_id=$LeadId&lp_caller_id=$number1";
                    break;
                case 11:
                    //Allied Digital Media 11
                    $url_api .= "&tcpa_language=$TCPAText&tcpa_consent=$tcpa_compliant2&landing_page_url=$OriginalURL2&user_agent=$UserAgent&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId";
                    break;
                case 217:
                    // 217	Solar Direct Marketing
                    $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&credit_score=Good&source_page_url=$OriginalURL2&landing_page=$OriginalURL2&tcpa=$tcpa_compliant2&tcpaDisclosure=$tcpa_compliant2&tcpaText=$TCPAText&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&user_agent=$UserAgent&lp_s2=$lead_source_text&traffic_source=$lead_source_name&first_name=$first_name&last_name=$last_name&address=$street&phone_home=$number1&phone_cell=$number1&email_address=$email&address_1=$street";
                    break;
            }

            switch ($crm_details['service_id']) {
                case 1:
                    //windows
                    $number_of_windows = trim($crm_details['data']['number_of_window']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    switch ($crm_details['buyer_id']) {
                        case 217:
                            $url_api .= "&lp_s1=236W&repair_or_replace=Replace&window_count=$number_of_windows";
                            break;
                    }
                    break;
                case 2:
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

                    switch ($crm_details['buyer_id']) {
                        case 61:
                            //Trinity Solar 61
                            switch ($monthly_electric_bill) {
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

                            switch ($roof_shade) {
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

                            $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');
                            $product_category = "Solar";

                            $url_api .= "&average_monthly_utility_bill=$average_bill&roof_shade=$roof_shade&homeowner=$homeowner&product_category=$product_category";
                            break;
                        case 234:
                            //Strategic Solar Solutions 234
                            $credit_score = "good";
                            $homeowner = ($property_type == 'Rented' ? "No" : "Yes");

                            switch ($monthly_electric_bill) {
                                case '$0 - $50':
                                    $average_bill = 'below 50';
                                    break;
                                case '$51 - $100':
                                    $average_bill = '51-100';
                                    break;
                                case '$101 - $150':
                                    $average_bill = '101-150';
                                    break;
                                case '$151 - $200':
                                    $average_bill = '151-200';
                                    break;
                                case '$201 - $300':
                                    $average_bill = '201-300';
                                    break;
                                case '$301 - $400':
                                    $average_bill = '301-400';
                                    break;
                                case '$401 - $500':
                                    $average_bill = '401-500';
                                    break;
                                default:
                                    $average_bill = 'more than 500';
                            }

                            switch ($roof_shade) {
                                case "Full Sun":
                                    $roof_shade_data = "full_sun";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "mostly_shaded";
                                    break;
                                case "Partial Sun":
                                    $roof_shade_data = "partial_sun";
                                    break;
                                default:
                                    $roof_shade_data = "not_sure";
                            }

                            $url_api .= "&electric_provider=$utility_provider&homeowner=$homeowner&roof_shade=$roof_shade_data&credit=$credit_score&electric_bill=$average_bill";
                            break;
                        case 11:
                            //Allied Digital Media 11
                            $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');
                            $monthly_electric_bill = ($monthly_electric_bill == "$500+" ? "$401 - $500" : $monthly_electric_bill);

                            switch ($power_solution) {
                                case "Solar for my Business":
                                    $power_solution_data = "Business";
                                    break;
                                case "Solar Electricity for my Home":
                                    $power_solution_data = "Residential Electricity";
                                    break;
                                case "Solar Electricity & Water Heating for my Home":
                                    $power_solution_data = "Residential Electricity and Water Heating";
                                    break;
                                default:
                                    $power_solution_data = "Residential Water Heating";
                            }

                            $url_api .= "&roof_shade=$roof_shade&monthly_electric_bill=$monthly_electric_bill&owner=$homeowner&utility_provider=$utility_provider&credit=Good&solution_type=$power_solution_data&property_type=Single Family&timeframe=Flexible";
                            break;
                        case 217:
                            // 217	Solar Direct Marketing
                            $home_owner = ($property_type == "Owned" ? "Yes" : "No");

                            switch ($monthly_electric_bill) {
                                case '$51 - $100':
                                case '$101 - $150':
                                    $average_bill = '$100-$150';
                                    break;
                                case '$151 - $200':
                                    $average_bill = "$151-$200";
                                    break;
                                case '$201 - $300':
                                    $average_bill = '$251-$300';
                                    break;
                                case '$301 - $400':
                                    $average_bill = '$301-$400';
                                    break;
                                case '$401 - $500':
                                    $average_bill = '$401-$500';
                                    break;
                                default:
                                    $average_bill = '$401-$500';
                            }

                            switch ($roof_shade) {
                                case "Full Sun":
                                    $roof_shade_data = "No Shade";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "A Lot Of Shade";
                                    break;
                                case "Partial Sun":
                                    $roof_shade_data = "A Little Shade";
                                    break;
                                default:
                                    $roof_shade_data = "Not Sure";
                            }

                            switch ($power_solution) {
                                case "Solar Electricity for my Home":
                                case "Solar Electricity & Water Heating for my Home":
                                    $power_solution_data = "Yes";
                                    break;
                                case "Solar Water Heating for my Home":
                                    $power_solution_data = "No";
                                    break;
                                default:
                                    $power_solution_data = "No";
                            }

                            $url_api .= "&lp_s1=236&homeowner=$home_owner&property_type=Single Family&roof_shade=$roof_shade_data&utility_electric_monthly_amount=$average_bill&utility_electric_company_name=$utility_provider&solar_electric=$power_solution_data";
                            break;
                    }
                    break;
                case 3:
                    //Home Security
                    $Installation_Preferences = trim($crm_details['data']['Installation_Preferences']);
                    $lead_have_item_before_it = trim($crm_details['data']['lead_have_item_before_it']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $property_type = trim($crm_details['data']['property_type']);

                    break;
                case 4:
                    //Flooring
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $Type_OfFlooring = trim($crm_details['data']['flooring_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    break;
                case 5:
                    //WALK-IN TUBS
                    $ownership = trim($crm_details['data']['homeOwn']);

                    break;
                case 6:
                    //roofing
                    $roof_type = trim($crm_details['data']['roof_type']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $start_time = trim($crm_details['data']['start_time']);

                    switch ($crm_details['buyer_id']) {
                        case 61:
                            //Trinity Solar 61
                            $homeowner = 'Yes';
                            $product_category = "Roof";

                            $url_api .= "&homeowner=$homeowner&product_category=$product_category";
                            break;
                        case 217:
                            // 217	Solar Direct Marketing
                            switch ($project_nature) {
                                case "Install roof on new construction":
                                    $replace_or_repair = "Replace";
                                    $taskId = "New";
                                    break;
                                case "Completely replace roof":
                                    $replace_or_repair = "Replace";
                                    $taskId = "Replace";
                                    break;
                                default:
                                    $replace_or_repair = "Repair";
                                    $taskId = "Repair";
                            }

                            switch ($roof_type) {
                                case "Asphalt Roofing":
                                    $roofType = "Asphalt";
                                    break;
                                case "Metal Roofing":
                                    $roofType = "Metal";
                                    break;
                                case "Natural Slate Roofing":
                                    $roofType = "Natural Slate";
                                    break;
                                case "Tile Roofing":
                                    $roofType = "Tile";
                                    break;
                                default:
                                    $roofType = "Other";
                            }

                            switch ($start_time) {
                                case 'Immediately':
                                    $start_time_data = "Ready Now";
                                    break;
                                default:
                                    $start_time_data = "4 To 6 Months";
                            }

                            $url_api .= "&lp_s1=236R&roof_type=$roofType&replace_or_repair=$replace_or_repair&taskId=$taskId&home_type=Single Family Home&timeFrame=$start_time_data";
                            break;
                    }
                    break;
                case 7:
                    //Home Siding
                    $type_of_siding = trim($crm_details['data']['type_of_siding']);
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    break;
                case 8:
                    //Kitchen
                    $service_kitchen = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $demolishing_walls = trim($crm_details['data']['demolishing_walls']);

                    break;
                case 9:
                    //bathroom
                    $bathroom_type_name = trim($crm_details['data']['services']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);
                    switch ($crm_details['buyer_id']) {
                        case 217:
                            // 217	Solar Direct Marketing
                            $url_api .= "&lp_s1=236B&repair_or_replace=Replace";
                            break;
                    }
                    break;
                case 11:
                    //Furnace
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    break;
                case 12:
                    //Boiler
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $type_of_heating = trim($crm_details['data']['type_of_heating']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    break;
                case 13:
                    //Central A/C
                    $project_nature = trim($crm_details['data']['project_nature']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $ownership = trim($crm_details['data']['homeOwn']);

                    break;
                case 14:
                    //Cabinet
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    break;
                case 16:
                    //Bathtubs
                    $ownership = trim($crm_details['data']['homeOwn']);

                    break;
                case 18:
                    //Handyman
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);

                    break;
                case 20:
                    //Doors
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    break;
                case 21:
                    //Gutter
                    $ownership = trim($crm_details['data']['homeOwn']);
                    $start_time = trim($crm_details['data']['start_time']);
                    $project_nature = trim($crm_details['data']['project_nature']);

                    break;
                case 24:
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

                    break;
            }

            if (config('app.env', 'local') == "local") {
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
            if (!empty($result2['result'])) {
                if ($result2['result'] == 'success' || $result2['msg'] == 'Lead Accepted') {
                    $status = 1;
                }
            }
            return $status;
        } catch (Exception $e) {
        }
    }

    public function AcculynxCrm($data_msg, $crm_details)
    {
        //AcculynxCrm
        if (empty($crm_details['AcculynxCrm'])) {
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
        if (!empty($crm_details['data']['start_time'])) {
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
        if (!empty($crm_details['data']['project_nature'])) {
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
            "companyName" =>  config('app.name', ''),
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
        if (!empty($result2['success'])) {
            if ($result2['success'] == true) {
                $status = 1;
            }
        }
        return $status;
    }

    public function ZohoCrm($data_msg, $crm_details)
    {
        //Zoho CRM
        if (empty($crm_details['ZohoCrm'])) {
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
        switch ($service_name) {
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
        if (!empty($result_token2['access_token'])) {
            $access_token = $result_token2['access_token'];

            $Company = $crm_details['ZohoCrm']['Lead_Source'];
            $Lead_Source = $crm_details['ZohoCrm']['Lead_Source'];
            $Country = "US";
            $lead_status = "New";
            $url_Leads = "https://www.zohoapis.com/crm/v2/Leads";

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

    public function HatchCrm($data_msg, $crm_details)
    {
        //Hatch CRM
        if (empty($crm_details['HatchCrm'])) {
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

        $dep_id = $crm_details['HatchCrm']['dep_id'];
        $org_token = $crm_details['HatchCrm']['org_token'];
        $urlSub = $crm_details['HatchCrm']['URL_sub'];

        //Hatch API ======================================================
        if (!empty($urlSub)) {
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
            "source" =>  config('app.name', ''),
            "id" => $leadsCustomerCampaign_id
        );

        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if (strpos("-" . $result, 'true') == true) {
            return 1;
        }
        return 0;
    }

    public function SalesforceCrm($data_msg, $crm_details)
    {
        //Salesforce CRM
        if (empty($crm_details['salesforceCRM'])) {
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

        $url_api = (!empty($crm_details['salesforceCRM']['url']) ? $crm_details['salesforceCRM']['url'] : "https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8");
        $lead_source = (!empty($crm_details['salesforceCRM']['lead_source']) ? $crm_details['salesforceCRM']['lead_source'] :  "");
        $retURL = $crm_details['salesforceCRM']['retURL'];
        $oid = $crm_details['salesforceCRM']['oid'];

        $Lead_data_array = "oid=$oid&retURL=$retURL&first_name=$first_name&last_name=$last_name&email=$email&phone=$number1&street=$street&city=$city&zip=$zip&state_code=$statename_code&state=$state&lead_source=$lead_source&country_code=US";

        switch ($crm_details['buyer_id']) {
            case 105:
                //Ad Energy 105
                $Lead_data_array .= "&00NG0000008dFiF=$number1&00NG0000008dFiE=$email&00NG0000008dFiI=$street&00NG0000008dFiC=$city&00NG0000008dFiH=$statename_code&00NG0000008dFiN=$zip&00N1M00000FHJQ7=Zone1Remodeling";
                break;
            case 166:
                //EZ Solar & Electric 166
                $Lead_data_array .= "&company=$first_name $last_name";
                break;
            case 195:
                //195 Venture Solar
                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                $utility_provider = trim($crm_details['data']['utility_provider']);

                switch ($monthly_electric_bill) {
                    case '$0 - $50':
                    case '$51 - $100':
                        $average_bill = "0-$100";
                        break;
                    case '$101 - $150':
                    case '$151 - $200':
                        $average_bill = "$100-$200";
                        break;
                    case '$201 - $300':
                        $average_bill = "$200-$300";
                        break;
                    case '$301 - $400':
                        $average_bill = "$300-$400";
                        break;
                    case '$401 - $500':
                    default:
                        $average_bill = "$400+";
                }

                $Lead_data_array .= "&recordType=01261000000REYX&00N61000006qX6a=$average_bill&00N61000006qXc8=$utility_provider";
                break;
            case 198:
                //Xando Energy 198
                $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
                if ($listOFCampainDB_type == "Exclusive") {
                    $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
                    $Lead_Cost_type = "Exclusive";
                } else {
                    $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
                    $Lead_Cost_type = "Non Exclusive";
                }

                $lead_portal_source = "Z1";
                $portal_return_email = "tech@thryvea.co";

                $Lead_data_array .= "&00N5f00000MS2rX=$lead_portal_source&00N6T000009jBmY=$portal_return_email&00N5f00000MUDNN=$Lead_Cost_type&00N5f00000MUCss=$leadsCustomerCampaign_id&00N5f00000MUDIq=$Lead_Cost";
                break;
            case 278:
                //Sunation 278
                $property_type = trim($crm_details['data']['property_type']);
                $OwnHome = ($property_type == "Owned" ? "YES" : "NO");

                $Lead_data_array .= "&mobile=$number1&00N4X00000Bpycp=$OwnHome&00N0h000006eGHH=No";
                break;
        }

        $Lead_data_array = str_replace(" ", "%20", $Lead_data_array);

        $httpheader = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Cookie: BrowserId=cDZghvzqEeqVPLVV8x7_Fg"
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function BuilderPrimeCRM($data_msg, $crm_details)
    {
        if (empty($crm_details['Builder_Prime_CRM'])) {
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
            "leadSourceName" => "Lead Form",
            "secretKey" => $secretKey,
        );

        $result = $crm_api_file->api_send_data($postURL, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if (str_contains(strtolower($result), 'success')) {
            return 1;
        }
        return 0;
    }

    public function ZapierCRM($data_msg, $crm_details)
    {
        //ZapierCrm
        if (empty($crm_details['ZapierCrm'])) {
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
            "Zip" => $zip
        );

        switch ($crm_details['service_id']) {
            case 2:
                //Solar
                $utility_provider = $crm_details['data']['utility_provider'];

                $Lead_data_array['Utility_Company'] = $utility_provider;
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

        $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
        $result2 = json_decode($result, true);
        if (!empty($result2['status'])) {
            if ($result2['status'] == "success") {
                return 1;
            }
        }
        return 0;
    }

    public function SetShapeCrm($data_msg, $crm_details)
    {
        //SetShapeCrm
        if (empty($crm_details['SetShapeCrm'])) {
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

    public function jobNimbusCrm($data_msg, $crm_details)
    {
        //jobNimbusCrm
        if (empty($crm_details['SetJobNimbusCrm'])) {
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

        $api_key = $crm_details['SetJobNimbusCrm']['api_key'];
        $url_api = "https://app.jobnimbus.com/api1/contacts";

        $httpheader = array(
            'Accept: application/json',
            'Content-Type: application/json',
            "Authorization: Bearer " . $api_key
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
            "mobile_phone" => $number1
        );

        $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array_post)), "POST", 1, $crm_details['campaign_id']);
        return 1;
    }

    public function SunBaseDataCrm($data_msg, $crm_details)
    {
        //Sunbase CRM
        if (empty($crm_details['set_sunbase_crm'])) {
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

        switch ($crm_details['service_id']) {
            case 2:
                //Solar
                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                $utility_provider = trim($crm_details['data']['utility_provider']);

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

                $Lead_data_array['customField1'] = $utility_provider;
                $Lead_data_array['customField37'] = $average_bill;
                break;
        }

        $httpheader = array(
            "Accept: application/json",
            "content-type: application/x-www-form-urlencoded"
        );

        $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, http_build_query($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
        if (str_contains(strtolower($result), 'successfully')) {
            return 1;
        }
        return 0;
    }

    public function googleSheets($spreadsheetId, $range, $values)
    {
        try {
            $client = new \Google_Client();
            $client->setApplicationName('GoogleSheetZone1');
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

    public function CustomCrm($data_msg, $crm_details, $user_id)
    {
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
            if ($tcpa_compliant == 1) {
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
                case 18:
                    //Lend Home Improvements 18
                    $dialed_tollfree = "0000000006";
                    $utm_source = "ADMs2";
                    $UserID = "zone1";
                    $Password = "m!@gdQ!hh29";
                    $FinanceProduct = "";
                    switch ($lead_type_service_id) {
                        case 1:
                            $FinanceProduct = "Window";
                            break;
                        case 6:
                            $FinanceProduct = "Roof";
                            break;
                        case 7:
                            $FinanceProduct = "Siding";
                            break;
                        case 9:
                            $FinanceProduct = "Bathroom";
                            break;
                        case 20:
                            $FinanceProduct = "Door";
                            break;
                        case 21:
                            $FinanceProduct = "Gutter";
                            break;
                    }

                    $url_api = "https://lendhi.leadperfection.com/batch/leadformgeneric.asp?FirstName=$first_name&LastName=$last_name&Address=$street&City=$city&State=$statename_code&Zip=$zip&Phone=$number1&Email=$email&LogNumber=$leadsCustomerCampaign_id&dialed_tollfree=$dialed_tollfree&utm_source=$utm_source&FinanceProduct=$FinanceProduct&UserID=$UserID&Password=$Password";
                    $url_api = str_replace(" ", "%20", $url_api);

                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2)) {
                        if (!empty($result2['message'])) {
                            if ($result2['message'] == "ok") {
                                return 1;
                            }
                        }
                    }
                    break;
                case 34:
                    //Jacuzzi 34
                    $dialed_tollfree = "0000001065";
                    $UserID = "zone1remodeling";
                    $Password = "zone123";

                    $url_api = "https://jbr.leadperfection.com/batch/leadformgeneric.asp?FirstName=$first_name&LastName=$last_name&Address=$street&City=$city&State=$statename_code&Zip=$zip&Phone=$number1&Email=$email&lead_transactionid=$leadsCustomerCampaign_id&dialed_tollfree=$dialed_tollfree&utm_source=$lead_source_text&weblead_url=$OriginalURL2&UserID=$UserID&Password=$Password";
                    $url_api = str_replace(" ", "%20", $url_api);

                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
                    if (str_contains(strtolower($result), 'ok')) {
                        return 1;
                    }
                    break;
                case 99:
                    //EnergyPal 99
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

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
                            $roof_shade_data = "A Lot Of Shade";
                            break;
                        case "Partial Sun":
                            $roof_shade_data = "A Little Shade";
                            break;
                        default:
                            $roof_shade_data = "Unsure";
                    }

                    $cid = "";
                    switch ($statename_code) {
                        case "CA":
                            $cid = "380zlh6gk28ejell";
                            break;
                        case "CT":
                            $cid = "i0avlrs2cwspvl81";
                            break;
                        case "MA":
                            $cid = "kv29heoxaujtnxga";
                            break;
                        case "NJ":
                            $cid = "m6n5uynj8zd9yb7w";
                            break;
                    }

                    $Lead_data_array = array(
                        "cid" => $cid,
                        "sid" => $google_ts,
                        "lid" => $leadsCustomerCampaign_id,
                        "price" => $Lead_Cost,
                        "ip_address" => $IPAddress,
                        "universal_leadid" => $LeadId,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone" => $number1,
                        "email" => $email,
                        "electric_utility" => $utility_provider,
                        "electric_bill" => $average_bill,
                        "roof_shade" => $roof_shade_data,
                        "address" => array(
                            "street" => $street,
                            "city" => $city,
                            "state" => $statename_code,
                            "zip" => $zip,
                            "country" => "US",
                        )
                    );

                    $url_api = "https://api.energypal.com/api/v1/leads";
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/x-www-form-urlencoded"
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, http_build_query($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2)) {
                        if (!empty($result2['status'])) {
                            if ($result2['status'] == "accepted") {
                                return 1;
                            }
                        }
                    }
                    break;
                case 100:
                case 128:
                    //Orbit Energy & Power LLC 100
                    //O Energy & Power 128
                    $url_api = "https://orbitenergy.us/oep/inboundzone1.php";
                    $Lead_data_array = array(
                        "key" => "Vg3F7xVn0icH",
                        "source" => "Zone1",
                        "list_id" => "90006",
                        "dedupe" => "L",
                        "add_to_hopper" => "Y",
                        "phone_code" => "1",
                        "phone_number" => $number1,
                        "postal_code" => $zip,
                        "state" => $statename_code,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "city" => $city,
                        "address1" => $street
                    );
                    $httpheader = array(
                        "content-type: application/json",
                        "accept: application/json"
                    );
                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['success'])) {
                        if ($result2['success'] == true) {
                            return 1;
                        }
                    }
                    break;
                case 24:
                    //Nessco Construction LLC 24
                    $zapikey = "1001.26360d7ee91f3669f495e8f3f5e836d1.ee834c3e2751c37353de82aa8e2b71cb";
                    $isdebug = "false";
                    $Status = "new";

                    switch ($crm_details['service_id']) {
                        case 1:
                            //windows
                            $Job_type = "windows";
                            break;
                        case 6:
                            //Roofing
                            $Job_type = "roof";
                            break;
                        default:
                            $Job_type = $crm_details['service_campaign_name'];
                    }

                    $listOFCampainDB_type = $crm_details['listOFCampainDB_type'];
                    if ($listOFCampainDB_type == "Exclusive") {
                        $Lead_Cost = $crm_details['campaign_budget_bid_exclusive'] - $crm_details['virtual_price'];
                        $Lead_Cost_type = "Exclusive";
                    } else {
                        $Lead_Cost = $crm_details['campaign_budget_bid_shared'] - $crm_details['virtual_price'];
                        $Lead_Cost_type = "Shared";
                    }

                    $url_api = "https://flow.zoho.com/686192442/flow/webhook/incoming?zapikey=$zapikey&isdebug=$isdebug&First name=$first_name&Last name=$last_name&Lead number=$leadsCustomerCampaign_id&Phone number=$number1&Email=$email&Street=$street&city=$city&zip=$zip&Job type=$Job_type&Lead cost=$Lead_Cost&Status=$Status";
                    $url_api = str_replace(" ", "%20", $url_api);
                    $Lead_data_array = "";
                    $httpheader = array("cache-control: no-cache");

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "GET", 1, $crm_details['campaign_id']);
                    return 1;
                    break;
                case 121:
                    //ION Solar 121
                    $method = "zone_1_remodeling";
                    $api_key = "92d466ce-8ba7-4a79-9992-639c4634456a";

                    $url_api = "https://api.iondeveloper.com/rep/lead-external/lead/?api_key=$api_key&method=$method&first_name=$first_name&last_name=$last_name
                    &email=$email&phone=$number1&street=$street&city=$city&state=$state&zip=$zip";
                    $url_api = str_replace(" ", "%20", $url_api);

                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['success'])) {
                        if ($result2['success'] == "true") {
                            return 1;
                        }
                    }
                    break;
                case 126:
                    //Home Forever Baths 126
                    $url_api = "https://homeforeverbathscompany.secure.force.com/genericeleads/services/apexrest/i360/eLeadv2";

                    $Lead_data_array = array(
                        "i360__First_Name__c" => $first_name,
                        "i360__Last_Name__c" => $last_name,
                        "i360__Address_1__c" => $street,
                        "i360__City__c" => $city,
                        "i360__State_Province__c" => $statename_code,
                        "i360__Zip__c" => $zip,
                        "i360__phone__c" => $number1,
                        "i360__email__c" => $email,
                        "i360__Source__c" => "Zone1",
                        "i360__Source_Type__c" => "Lead Provider",
                        "offer__c" => $crm_details['service_campaign_name']
                    );

                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['status'])) {
                        if ($result2['status'] == "success") {
                            return 1;
                        }
                    }
                    break;
                case 158:
                    //Crain 1 Inc 158
                    $url_api = "https://leads.crain1.com/Leads/LeadPost.aspx";
                    $Lead_data_array = "";
                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/xml"
                    );

                    $CampaignId = "B9C509C334F1E4DD3EAE26E365E75B0E";
                    $is_testl = false;
                    if (config('app.env', 'local') == "local") {
                        //Test Mode
                        $is_testl = true;
                    }

                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);

                    $OwnHome = ($property_type == "Owned" ? "Yes" : "No");
                    switch ($monthly_electric_bill) {
                        case '$0 - $50':
                        case "$51 - $100":
                            $monthly_bill = "$0-100";
                            break;
                        case "$101 - $150":
                            $monthly_bill = "$101-150";
                            break;
                        case "$151 - $200":
                            $monthly_bill = "$151-200";
                            break;
                        case "$201 - $300":
                            $monthly_bill = "$201-300";
                            break;
                        case "$301 - $400":
                            $monthly_bill = "$301-400";
                            break;
                        default:
                            $monthly_bill = "$401+";
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
                            $roof_shade_data = "Uncertain";
                    }

                    $url_api .= "?CampaignId=$CampaignId&IsTest=$is_testl&FirstName=$first_name&LastName=$last_name&HomePhone=$number1&Email=$email&Address1=$street&City=$city&State=$statename_code&Zip=$zip&RoofShade=$roof_shade_data&ElectricCompany=$utility_provider&AverageBill=$monthly_bill&OwnHome=$OwnHome&TCPA=$tcpa_compliant2";
                    $url_api = str_replace(" ", "%20", $url_api);

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "POST", 1, $crm_details['campaign_id']);
                    try {
                        libxml_use_internal_errors(true);
                        $result2 = simplexml_load_string($result);
                        $result3 = json_encode($result2);
                        $result4 = json_decode($result3, TRUE);
                        if (!empty($result4['IsValid'])) {
                            if ($result4['IsValid'] == "True") {
                                return 1;
                            }
                        }
                    } catch (Exception $e) {
                    }
                    break;
                case 187:
                    //Gutter Guards America LLC 187
                    if (config('app.env', 'local') == "local") {
                        //Test
                        $url_api = "https://7575520-sb1.extforms.netsuite.com/app/site/hosting/scriptlet.nl?script=821&deploy=1&compid=7575520_SB1&h=7a08c78630b60c482ed8";
                    } else {
                        //Live
                        $url_api = "https://7575520.extforms.netsuite.com/app/site/hosting/scriptlet.nl?script=821&deploy=1&compid=7575520&h=eab87992765d318ca65c";
                    }

                    $url_api .= "&firstName=$first_name&lastName=$last_name&primaryPhone=$number1&email=$email&address=$street&city=$city&postalCode=$zip&stateProvince=$statename_code&Source=Zone 1 Remodeling";

                    switch ($crm_details['service_id']) {
                        case 21:
                            //Gutter
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $taskName = ($project_nature != "Repair" ? "Gutter Install" : "");
                            $url_api .= "&taskName=$taskName";
                            break;
                    }

                    $url_api = str_replace(" ", "%20", $url_api);
                    $Lead_data_array = "";
                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json'
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_array, "GET", 1, $crm_details['campaign_id']);
                    if (!empty($result)) {
                        if (str_contains(strtolower($result), 'saved')) {
                            return 1;
                        }
                    }
                    break;
                case 188:
                case 249:
                    //Sunergy Solutions 188 / 249
                    //Solar
                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);
                    $power_solution = trim($crm_details['data']['power_solution']);

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

                    $Lead_data_array = array(
                        "schema_name" => "sunergysolutions",
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "address1" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "zip_code" => $zip,
                        "phone" => $number1,
                        "email" => $email,
                        "lead_other" => $leadsCustomerCampaign_id,
                        "lead_source" => "Zone1",
                        "lead_cost" => $Lead_Cost,
                        "customField1" => $utility_provider,
                        "customField37" => $average_bill,
                    );

                    $url_api = "http://server2.sunbasedata.com/sunbase/portal/api/lead_post.jsp";
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/x-www-form-urlencoded"
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, http_build_query($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    if (str_contains(strtolower($result), 'successfully')) {
                        return 1;
                    }
                    break;
                case 181:
                    //Tephra Solar 181
                    //monday CRM
                    $url_api = "https://forms.monday.com/forms/6ef90860ee258627072b087cddc40d20/submit_form?r=use1";

                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json'
                    );

                    $Lead_data_array = array(
                        "answers" => array(
                            "name" => "$first_name $last_name",
                            "phone0" => $number1,
                            "address" => "$street $zip",
                            "zipcode" => $zip,
                            "text" => $Lead_Cost_type
                        ),
                        "address" => "$street $zip",
                        "bid" => $Lead_Cost,
                        "created_date" => date("m/d/Y"),
                        "email_address" => array(
                            "email" => $email,
                            "text" => $email,
                        ),
                        "name" => "$first_name $last_name",
                        "phone0" => $number1,
                        "text" => $Lead_Cost_type,
                        "zipcode" => $zip,
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['creation_result'])) {
                        if ($result2['creation_result'] == "true") {
                            return 1;
                        }
                    }
                    break;
                case 194:
                    //PJ Fitzpatrick 194
                    $url_token = "https://auth.servicetitan.io/connect/token";
                    $clientID = "cid.1csrmh0bicmih9wver82kt2a3";
                    $clientSecret = "cs1.fy3urg4zolffdq268gtpgf42283lw6sjkbs1k07wx1oauwakis";
                    $input_token = "grant_type=client_credentials&client_id=$clientID&client_secret=$clientSecret";
                    $httpheader_token = array(
                        'Content-Type: application/x-www-form-urlencoded',
                        "Accept: application/json"
                    );

                    $result_token = $crm_api_file->api_send_data($url_token, $httpheader_token, $leadsCustomerCampaign_id, $input_token, "POST", 1, $crm_details['campaign_id']);
                    $result_token2 = json_decode($result_token, true);
                    if (!empty($result_token2['access_token'])) {
                        $access_token = $result_token2['access_token'];
                        $tenantID = "889499837";
                        $bookingProvider = "240875553";
                        $url_api = "https://api.servicetitan.io/crm/v2/tenant/$tenantID/booking-provider/$bookingProvider/bookings";
                        $appKey = "ak1.s1qjpw1igjfhx6zv5lq0yh9wz";
                        $httpheader = array(
                            "Authorization: $access_token",
                            "ST-App-Key: $appKey",
                            "Content-Type: application/json",
                            "Accept: application/json",
                        );

                        $serviceReq = "";
                        switch ($lead_type_service_id) {
                            case 1:
                                // windows
                                $number_of_windows = trim($crm_details['data']['number_of_window']);
                                switch ($number_of_windows) {
                                    case '6-9':
                                        $serviceReq = "6 to 9 Windows Installation Or Replacement";
                                        break;
                                    case "10+":
                                        $serviceReq = "10 + Windows Installation Or Replacement";
                                        break;
                                }
                                break;
                            case 6:
                                // roofing
                                $roof_type = trim($crm_details['data']['roof_type']);
                                switch ($roof_type) {
                                    case "Asphalt Roofing":
                                        $serviceReq = "Asphalt Roofing Installation Or Replacement";
                                        break;
                                }
                                break;
                            case 7:
                                // home siding
                                $type_of_siding = trim($crm_details['data']['type_of_siding']);
                                switch ($type_of_siding) {
                                    case "Vinyl Siding":
                                        $serviceReq = "Vinyl Siding Installation Or Replacement";
                                        break;
                                }
                                break;
                        }

                        $Lead_data_post = array(
                            "source" => "Zone 1 Remodeling",
                            "name" => "$first_name $last_name",
                            "address" => array(
                                "street" => $street,
                                "city" => $city,
                                "state" => $statename_code,
                                "zip" => $zip,
                                "country" => "The United States of America"
                            ),
                            "contacts" => array(
                                array(
                                    "type" => "Phone",
                                    "value" => $number1
                                ),
                                array(
                                    "type" => "Email",
                                    "value" => $email
                                )
                            ),
                            "summary" => $serviceReq,
                            "isFirstTimeClient" => true,
                            "externalId" => $leadsCustomerCampaign_id
                        );

                        $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_post), "POST", 1, $crm_details['campaign_id']);
                        $result2 = json_decode($result, true);
                        if (!empty($result2['id'])) {
                            return 1;
                        }
                    }
                    break;
                case 70:
                    //exact customer 70
                    $SourceDescr = 'Zone1Remodeling';
                    $project = $crm_details['service_campaign_name'];
                    $CampaignId = '2010'; // Pay Per Lead

                    $url_api = "https://Leads.EcDashboard.com/HomePost.aspx?Campaignid=$CampaignId&SRCID=$google_ts&SourceDescr=$SourceDescr&FirstName=$first_name&LastName=$last_name&Address1=$street&City=$city&State=$statename_code&Zipcode=$zip&HomePhone=$number1&Email=$email&Project=$project&LeadPrice=$Lead_Cost&TrustedForm=$trusted_form&Universalleadid=$LeadId";

                    if (config('app.env', 'local') == "local") {
                        //Test
                        $url_api .= "&TestMode=1";
                    }

                    $url_api = str_replace(" ", "%20", $url_api);

                    $Lead_data_array = array();
                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    if (strpos("-" . $result, 'Success') == true) {
                        return 1;
                    }
                    break;
                case 217:
                    //Lifetime Windows and Siding 217
                    //To Generate Token
                    $url_api_post_token = 'https://login.salesforce.com/services/oauth2/token?grant_type=password&client_id=3MVG9LBJLApeX_PAIKNAzmy03HHv0VZVQCRvCzr8WHDvK2ftOlXLAXeqUQomS094Q1LlwzOHYEd96X8LRLSl9&client_secret=D0BB9A11C96C051BB41B561C2A53013DC190115FA354D6C0A9CC259519C623C1&username=leadagintegration@sidekickus.com&password=I8Leads4Breakfast&redirect_uri=https://login.salesforce.com/services/oauth2/callback';
                    $Lead_data_array_token = array();
                    $httpheader_token = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                    );

                    $result_token = $crm_api_file->api_send_data($url_api_post_token, $httpheader_token, $leadsCustomerCampaign_id, $Lead_data_array_token, "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result_token, true);
                    if (!empty($result2['access_token'])) {
                        $access_token = $result2['access_token'];
                        //Send Lead Info
                        $url_api_post = "https://sidekickus.my.salesforce.com/services/apexrest/AccountCreation/";

                        $Lead_data_array = array(
                            "firstname" => $first_name,
                            "lastname" => $last_name,
                            "accountowner" => "0056g000004za80AAA",
                            "accountrecordtype" => "0124V000001GvzEQAS",
                            "addedtodialer" => "FALSE",
                            "billingstreet" => $street,
                            "billingcity" => $city,
                            "billingpostalcode" => $zip,
                            "phone" => $number1,
                            "donotcall" => "FALSE",
                            "email" => $email,
                            "emailoptout" => "FALSE",
                            "faxoptout" => "FALSE",
                            "idealcandidate" => "FALSE",
                            "originalleadsource" => "zone1",
                            "reachedpc" => "FALSE",
                            "targetskill" => "18506189",
                            "removefromdialer" => "FALSE",
                        );

                        switch ($lead_type_service_id) {
                            case 1:
                                $Lead_data_array['product'] = "window";
                                break;
                            case 9:
                                $Lead_data_array['product'] = "bath";
                                break;
                        }

                        if (config('app.env', 'local') == "local") {
                            //Test
                            $Lead_data_array['addtodialer'] = "FALSE";
                        } else {
                            //Live
                            $Lead_data_array['addtodialer'] = "TRUE";
                        }

                        $httpheader = array(
                            'Accept: application/json',
                            'Content-Type: application/json',
                            "Authorization: Bearer $access_token"
                        );

                        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                        if (str_contains($result, 'success') == true) {
                            return 1;
                        }
                    }
                    break;
                case 209:
                    //Neighborhood Windows & Doors. 209
                    //jobnimbus CRM API Integration
                    $url_api = "https://app.jobnimbus.com/api1/contacts";

                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        "Authorization: Bearer ldj0q1noc8nlmln5"
                    );

                    $Lead_data_array = array(
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "record_type_name" => "Customer",
                        "status_name" => "Lead",
                        "address_line1" => $street,
                        "city" => $city,
                        "state_text" => $statename_code,
                        "zip" => $zip,
                        "email" => $email,
                        "number" => $number1,
                        "home_phone" => $number1,
                        "mobile_phone" => $number1
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    return 1;
                    break;
                case 264:
                    //264 Omniya Solar
                    //jobnimbus CRM API Integration
                    $url_api = "https://app.jobnimbus.com/api1/contacts";

                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        "Authorization: Bearer lg3qg5vpmae9dwxj"
                    );

                    $Lead_data_array = array(
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
                        "mobile_phone" => $number1
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    return 1;
                    break;
                case 271:
                    //Solar Energy World 271
                    $monthly_electric_bill = $crm_details['data']['monthly_electric_bill'];
                    $utility_provider = $crm_details['data']['utility_provider'];

                    switch ($monthly_electric_bill) {
                        case '$0 - $50':
                            $MonthlyPowerBill = "50";
                            break;
                        case '$51 - $100':
                            $MonthlyPowerBill = "100";
                            break;
                        case '$101 - $150':
                            $MonthlyPowerBill = "150";
                            break;
                        case '$151 - $200':
                            $MonthlyPowerBill = "200";
                            break;
                        case '$201 - $300':
                            $MonthlyPowerBill = "300";
                            break;
                        case '$301 - $400':
                            $MonthlyPowerBill = "400";
                            break;
                        case '$401 - $500':
                            $MonthlyPowerBill = "500";
                            break;
                        default:
                            $MonthlyPowerBill = "600";
                    }

                    switch ($Lead_Cost_type) {
                        case 'Shared':
                            $Lead_Cost_type_data = "Non-Exclusive";
                            break;
                        default:
                            $Lead_Cost_type_data = "Exclusive";
                    }

                    $url_api = "https://prod-92.eastus.logic.azure.com/workflows/fc7ed960dffe40a4a67624c4f8d7d638/triggers/manual/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=bp9Lo67rUmnSH5ms2ZYcTvTwPuK8qFoboCk6vKMtr68";
                    if (config('app.env', 'local') == "local") {
                        $url_api = "https://prod-01.northcentralus.logic.azure.com/workflows/e276100cdcdf4576b4ba4095bd04d220/triggers/manual/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=UFvxo9bSfVzpQL3cZEpdOA6sXWIhy8wtFvw12dJ3z5I";
                    }

                    $Lead_data_array = array(
                        "body" => array(
                            "firstname" => $first_name,
                            "lastname" => $last_name,
                            "address1_line1" => $street,
                            "address1_city" => $city,
                            "address1_state" => $statename_code,
                            "address1_postalcode" => $zip,
                            "mobilephone" => $number1,
                            "emailaddress1" => $email,
                            "dc_highestutilitybill" => $MonthlyPowerBill,
                            "dc_utilitycompanyid" => $utility_provider,
                            "campaignname" => "Zone1",
                            "VendorLeadGUID" => $leadsCustomerCampaign_id,
                            "LeadCost" => $Lead_Cost,
                            "LeadType" => $Lead_Cost_type_data
                        )
                    );

                    $Lead_data_post = json_encode($Lead_data_array);
                    $content_lengthe = strlen($Lead_data_post);

                    $httpheader = array(
                        "Content-Type: application/json; charset=utf-8",
                        "Content-Length: $content_lengthe",
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, $Lead_data_post, "POST", 1, $crm_details['campaign_id']);
                    if (strpos("-" . strtolower($result), 'accepted') == true) {
                        return 1;
                    }
                    break;
                case 283:
                    //Kitchen Magic 283
                    switch ($crm_details['service_id']) {
                        case 8:
                            //Kitchen service
                            $service_kitchen = trim($crm_details['data']['services']);
                            $service_id_data = ($service_kitchen == 'Cabinet Refacing' ? "1" : "2");
                            $work_type = "Kitchen";
                            break;
                        case 9:
                            //Bathroom service
                            $service_id_data = "11";
                            $work_type = "Elements";
                            break;
                    }

                    $url_api_post = "https://kmapis.herokuapp.com/api/leads/ad685165-c16b-5aa3-85b7";
                    if (config('app.env', 'local') == "local") {
                        //Test Mode
                        $url_api_post .= "/test";
                    }

                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                    );

                    $Lead_data_array = array(
                        "firstname" => $first_name,
                        "lastname" => $last_name,
                        "address" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "zip" => $zip,
                        "email" => $email,
                        "phone" => $number1,
                        "services" => $service_id_data,
                        "vendor_id" => $leadsCustomerCampaign_id,
                        "work_type" => $work_type
                    );

                    $result =  $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    if (!strpos("-" . $result, 'error') == true) {
                        return 1;
                    }
                    break;
                case 306:
                    //LGCY Power 306
                    $url_api = "http://lgcy-marketplace.com/api/createlead/?username=zone1&api_key=oafnfaofhjkhrts5487s8689865423qiGadfssertegDFEU3";
                    $httpheader = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                    );

                    $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                    $utility_provider = trim($crm_details['data']['utility_provider']);
                    $roof_shade = trim($crm_details['data']['roof_shade']);
                    $property_type = trim($crm_details['data']['property_type']);

                    switch ($roof_shade) {
                        case "Full Sun":
                            $roof_shade_data = "none";
                            break;
                        case "Mostly Shaded":
                            $roof_shade_data = "heavy";
                            break;
                        case "Partial Shade":
                        default:
                            $roof_shade_data = "little";
                    }

                    $is_homeowner = ($property_type == "Owned" ? "True" : "False");

                    $Lead_data_array = array(
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "street" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "zip" => $zip,
                        "email" => $email,
                        "phone" => $number1,
                        "provider" => "Zone 1 Remodeling",
                        "recipient" => "LGCY Power",
                        "external_id" => $leadsCustomerCampaign_id,
                        "shade_amount" => $roof_shade_data,
                        "is_homeowner" => $is_homeowner,
                        "utility_bill" => $monthly_electric_bill,
                        "utility_company" => $utility_provider,
                        "lead_cost" => $Lead_Cost,
                        "sub_lead_source" => $google_ts,
                    );

                    $result =  $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                    if (!strpos("-" . $result, 'error_message') == true) {
                        return 1;
                    }
                    break;
                case 312:
                    //Astoria 312
                    if (config('app.env', 'local') == "local") {
                        $lead_mode = 0;
                    } else {
                        $lead_mode = 1;
                    }

                    $lead_type = 18;
                    $vendor_id = 46744;
                    $sub_id = $google_ts;
                    $tcpa_optin = $tcpa_compliant;
                    $origination_datetime = date('Y-m-d H:i:s');
                    $origination_timezone = 4;
                    $timeframe = 1;
                    $project_status = 1;

                    $home_owner = 1;
                    if (isset($crm_details['data']['property_type'])) {
                        $property_type = trim($crm_details['data']['property_type']);
                        $home_owner = ($property_type != "Owned" ? 0 : 1);
                    } else if (isset($crm_details['data']['homeOwn'])) {
                        $ownership = trim($crm_details['data']['homeOwn']);
                        $home_owner = ($ownership == "No" ? 0 : 1);
                    }

                    $url_api_post = "https://api.astoriacompany.com/v2/post/";
                    $httpheader = array(
                        "Content-Type: application/x-www-form-urlencoded"
                    );

                    if (!empty($data_msg['ping_post_data']['TransactionId'])) {
                        $confirmation_id = $data_msg['ping_post_data']['TransactionId'];

                        $Lead_data_post = "confirmation_id=$confirmation_id&lead_type=$lead_type&lead_mode=$lead_mode&vendor_id=$vendor_id&sub_id=$sub_id&tcpa_optin=$tcpa_optin&tcpa_text=$TCPAText&universal_leadid=$LeadId&origination_datetime=$origination_datetime&origination_timezone=$origination_timezone&ipaddress=$IPAddress&user_agent=$UserAgent&vendor_lead_id=$leadsCustomerCampaign_id&url=$OriginalURL2&first_name=$first_name&last_name=$last_name&email=$email&address=$street&zip=$zip&primary_phone=$number1&timeframe=$timeframe&home_owner=$home_owner&project_status=$project_status&xxtrustedformcerturl=$trusted_form";

                        //Services
                        switch ($crm_details['service_id']) {
                            case 1:
                                //windows
                                $project_nature = trim($crm_details['data']['project_nature']);
                                if ($project_nature != "Repair") {
                                    $project = 96;
                                    $task_ID = 128;
                                    $Lead_data_post .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_post .= "&project=$project";
                                }
                                break;
                            case 2:
                                //Solar
                                $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                                $utility_provider = trim($crm_details['data']['utility_provider']);
                                $roof_shade = trim($crm_details['data']['roof_shade']);
                                $property_type = trim($crm_details['data']['property_type']);

                                $current_provider = 1;
                                $roof_type = 1;
                                $credit_rating = 2;
                                $property_type_data = 1;
                                $project = 91;

                                switch ($monthly_electric_bill) {
                                    case '$0 - $50':
                                        $average_bill = 1;
                                        break;
                                    case '$51 - $100':
                                        $average_bill = 2;
                                        break;
                                    case '$101 - $150':
                                        $average_bill = 3;
                                        break;
                                    case '$151 - $200':
                                        $average_bill = 4;
                                        break;
                                    case '$201 - $300':
                                        $average_bill = 5;
                                        break;
                                    case '$301 - $400':
                                        $average_bill = 6;
                                        break;
                                    case '$401 - $500':
                                        $average_bill = 7;
                                        break;
                                    default:
                                        $average_bill = 8;
                                }

                                switch ($roof_shade) {
                                    case "Full Sun":
                                        $roof_shade_data = 0;
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = 2;
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = 1;
                                        break;
                                    default:
                                        $roof_shade_data = 3;
                                }

                                $Lead_data_post .= "&project=$project&current_provider=$current_provider&monthly_bill=$average_bill&property_type=$property_type_data&roof_type=$roof_type&roof_shade=$roof_shade_data&credit_rating=$credit_rating";
                                break;
                            case 3:
                                //Home Security
                                $project = 97;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 4:
                                //Flooring
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $project = ($project_nature != "Repair Existing Flooring" ? 77 : 90);
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($crm_details['data']['roof_type']);
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $property_type = trim($crm_details['data']['property_type']);

                                $project = 88;
                                if ($project_nature == "Repair existing roof") {
                                    $task_ID = 108;
                                } else {
                                    switch ($Type_OfRoofing) {
                                        case "Asphalt Roofing":
                                            $task_ID = 101;
                                            break;
                                        case "Wood Shake/Composite Roofing":
                                            $task_ID = 107;
                                            break;
                                        case "Metal Roofing":
                                            $task_ID = 104;
                                            break;
                                        case "Natural Slate Roofing":
                                            $task_ID = 105;
                                            break;
                                        case "Tile Roofing":
                                            $task_ID = 106;
                                            break;
                                        default:
                                            $task_ID = 109;
                                    }
                                }
                                $Lead_data_post .= "&project=$project&task=$task_ID";
                                break;
                            case 7:
                                //Siding
                                $type_of_siding = trim($crm_details['data']['type_of_siding']);
                                $project_nature = trim($crm_details['data']['project_nature']);

                                if ($project_nature != "Repair section(s) of siding") {
                                    $project = 89;

                                    switch ($type_of_siding) {
                                        case "Vinyl Siding":
                                            $task_ID = 113;
                                            break;
                                        case "Brickface Siding" || "Stoneface Siding":
                                            $task_ID = 110;
                                            break;
                                        case "Composite wood Siding" || "Fiber Cement Siding":
                                            $task_ID = 114;
                                            break;
                                        default:
                                            $task_ID = 115;
                                    }

                                    $Lead_data_post .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_post .= "&project=$project";
                                }
                                break;
                            case 8:
                                //Kitchen
                                $project = 82;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 9:
                                //Bathroom
                                $project = 63;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 11 || 12 || 13:
                                //HVAC
                                $project = 81;

                                $project_nature = trim($crm_details['data']['project_nature']);
                                if ($project_nature == "Repair") {
                                    $task_ID = 78;
                                } else {
                                    switch ($crm_details['service_id']) {
                                        case 11:
                                            $type_of_heating = trim($crm_details['data']['type_of_heating']);

                                            switch ($type_of_heating) {
                                                case "Do Not Know":
                                                    $task_ID = 78;
                                                    break;
                                                case "Oil":
                                                    $task_ID = 76;
                                                    break;
                                                default:
                                                    $task_ID = 73;
                                            }
                                            break;
                                        case 12:
                                            $type_of_heating = trim($crm_details['data']['type_of_heating']);

                                            switch ($type_of_heating) {
                                                case "Do Not Know":
                                                    $task_ID = 78;
                                                    break;
                                                case "Oil":
                                                    $task_ID = 75;
                                                    break;
                                                default:
                                                    $task_ID = 72;
                                            }
                                            break;
                                        case 13:
                                            $task_ID = 71;
                                            break;
                                    }
                                }

                                $Lead_data_post .= "&project=$project&task=$task_ID";
                                break;
                            case 14:
                                //Cabinet
                                $project = 64;
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $task_ID = ($project_nature == "Cabinet Install" ? 14 : 15);
                                $Lead_data_post .= "&project=$project&task=$task_ID";
                                break;
                            case 15:
                                //Plumbing
                                $project = 87;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 17:
                                //Sunrooms
                                $project = 92;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 18:
                                //Handyman
                                $project = 80;
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 19:
                                //CounterTops
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $project = ($project_nature != "Repair" ? 64 : 90);
                                $Lead_data_post .= "&project=$project";
                                break;
                            case 20:
                                //Doors
                                $door_type = trim($crm_details['data']['door_type']);
                                $project_nature = trim($crm_details['data']['project_nature']);
                                if ($project_nature != "Repair") {
                                    $project = 72;
                                    $task_ID = ($door_type == "Exterior" ? 49 : 50);
                                    $Lead_data_post .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_post .= "&project=$project";
                                }
                                break;
                            case 21:
                                //Gutters
                                $project = 88;
                                $project_nature = trim($crm_details['data']['project_nature']);
                                $task_ID = ($project_nature == "Repair" ? 108 : 103);
                                $Lead_data_post .= "&project=$project&task=$task_ID";
                                break;
                            case 23:
                                //Painting
                                $project = 85;
                                $service_type = trim($crm_details['data']['service']);

                                switch ($service_type) {
                                    case "Exterior Home or Structure - Paint or Stain":
                                        $task_ID = 90;
                                        break;
                                    case "Interior Home or Surfaces - Paint or Stain":
                                        $task_ID = 91;
                                        break;
                                    case "Specialty Painting - Textures":
                                        $task_ID = 93;
                                        break;
                                    default:
                                        $task_ID = 94;
                                }

                                $Lead_data_post .= "&project=$project&task=$task_ID";
                                break;
                        }

                        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, $Lead_data_post, "POST", 1, $crm_details['campaign_id']);
                        try {
                            libxml_use_internal_errors(true);
                            $result2 = simplexml_load_string($result);
                            $result3 = json_encode($result2);
                            $result4 = json_decode($result3, TRUE);

                            if (!empty($result4)) {
                                if (!empty($result4['Response'])) {
                                    if ($result4['Response'] == "Accepted") {
                                        return 1;
                                    }
                                }
                            }
                        } catch (Exception $e) {
                        }
                    }
                    break;
                case 375:
                case 374:
                case 373:
                case 372:
                case 371:
                case 370:
                case 369:
                case 368:
                case 367:
                case 366:
                case 365:
                case 364:
                case 363:
                case 362:
                case 361:
                case 360:
                case 359:
                case 358:
                case 357:
                case 356:
                case 355:
                case 354:
                case 353:
                case 352:
                case 350:
                case 349:
                case 348:
                case 347:
                case 346:
                case 345:
                case 378:
                case 344:
                case 343:
                    // 375 Floor Coverings International - wa
                    // 374 Floor Coverings International - or
                    // 373 Floor Coverings International - nv
                    // 372 Floor Coverings International - az
                    // 371 Floor Coverings International - ut
                    // 370 Floor Coverings International - id
                    // 369 Floor Coverings International - ca
                    // 368 Floor Coverings International - co
                    // 367 Floor Coverings International - tx
                    // 366 Floor Coverings International - ok
                    // 365 Floor Coverings International - ar
                    // 364 Floor Coverings International - la
                    // 363 Floor Coverings International - ks
                    // 362 Floor Coverings International - mo
                    // 361 Floor Coverings International - il
                    // 360 Floor Coverings International - mn
                    // 359 Floor Coverings International - wi
                    // 358 Floor Coverings International - mi
                    // 357 Floor Coverings International - in
                    // 356 Floor Coverings International - oh
                    // 355 Floor Coverings International - KY
                    // 354 Floor Coverings International - TN
                    // 353 Floor Coverings International - AL
                    // 352 Floor Coverings International - FL
                    // 350 Floor Coverings International - SC
                    // 349 Floor Coverings International - NC
                    // 348 Floor Coverings International - VA
                    // 347 Floor Coverings International - PA
                    // 346 Floor Coverings International - NY
                    // 345 Floor Coverings International - NJ
                    // 344 Floor Coverings International - CT
                    // 343 Floor Coverings International - MA
                    $api_key = "2gdLX0D354WKaZ3dezEq7zxNB9MOnrwG";
                    $url_api = "https://leads.pipes.ai/api/lead";
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $Lead_data = array(
                        "api_key" => $api_key,
                        "phone_number" => $number1,
                        "postal_code" => $zip,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "email" => $email,
                        "address_1" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "subid_1" => $google_ts,
                        "subid_2" => $leadsCustomerCampaign_id,
                        "cost" => $Lead_Cost,
                        "subid_10" => 35,
                        "tcpa_consent" => 1,
                        "tcpa_consent_date" => date("Y-m-d"),
                        "jornaya_leadid" => $LeadId
                    );

                    switch ($lead_type_service_id) {
                        case 1:
                            $Lead_data_array['subid_3'] = "Window";
                            break;
                        case 4:
                            $Type_OfFlooring = trim($crm_details['data']['flooring_type']);
                            $Lead_data_array['subid_4'] = $Type_OfFlooring;
                            $Lead_data_array['subid_3'] = "Flooring";
                            break;
                        case 6:
                            $Lead_data_array['subid_3'] = "Roofing";
                            break;
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['success'])) {
                        if ($result2['success'] == "true") {
                            return 1;
                        }
                    }
                    break;
                case 351:
                    // 351	Floor Coverings International - GA
                    $Type_OfFlooring = trim($crm_details['data']['flooring_type']);

                    $url_api = "https://leads.pipes.ai/api/lead";

                    $api_key = "eWLG8rq74k9MmpnoYmZVBjga2wy0xONX";

                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $Lead_data = array(
                        "api_key" => $api_key,
                        "phone_number" => $number1,
                        "ip_address" => $IPAddress,
                        "postal_code" => $zip,
                        "tcpa_consent" => $tcpa_compliant5,
                        "tcpa_consent_date" => date("Y-m-d"),
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "email" => $email,
                        "city" => $city,
                        "state" => $statename_code,
                        "jornaya_leadid" => $LeadId,
                        "subid_1" => $lead_source_text,
                        "subid_2" => $Lead_Cost,
                        "subid_3" => "Flooring",
                        "address_1" => $street,
                        "subid_4" => "New",
                        "subid_10" => 45
                    );

                    if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
                        //Test Mode
                        $Lead_data['test_mode'] = 1;
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data), "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['success'])) {
                        if ($result2['success'] == "true") {
                            return 1;
                        }
                    }
                    break;
                case 387:
                    //Nex-Gen Windows & Doors 387
                    //To Generate Token
                    $url_api_post_token = 'https://login.salesforce.com/services/oauth2/token?grant_type=password&client_id=3MVG9LBJLApeX_PAIKNAzmy03HHv0VZVQCRvCzr8WHDvK2ftOlXLAXeqUQomS094Q1LlwzOHYEd96X8LRLSl9&client_secret=D0BB9A11C96C051BB41B561C2A53013DC190115FA354D6C0A9CC259519C623C1&username=leadagintegration@sidekickus.com&password=I8Leads4Breakfast&redirect_uri=https://login.salesforce.com/services/oauth2/callback';
                    $Lead_data_array_token = array();
                    $httpheader_token = array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                    );

                    $result_token = $crm_api_file->api_send_data($url_api_post_token, $httpheader_token, $leadsCustomerCampaign_id, $Lead_data_array_token, "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result_token, true);
                    if (!empty($result2['access_token'])) {
                        $access_token = $result2['access_token'];
                        //Send Lead Info
                        $url_api_post = "https://sidekickus.my.salesforce.com/services/apexrest/AccountCreation/";

                        $Lead_data_array = array(
                            "firstname" => $first_name,
                            "lastname" => $last_name,
                            "accountowner" => "0056g000004za80AAA",
                            "accountrecordtype" => "0124V000001SjEUQA0",
                            "addedtodialer" => "FALSE",
                            "billingstreet" => $street,
                            "billingcity" => $city,
                            "billingpostalcode" => $zip,
                            "phone" => $number1,
                            "donotcall" => "FALSE",
                            "email" => $email,
                            "emailoptout" => "FALSE",
                            "faxoptout" => "FALSE",
                            "idealcandidate" => "FALSE",
                            "originalleadsource" => "Zone 1 Remodeling",
                            "reachedpc" => "FALSE",
                            "targetskill" => "18596008",
                            "removefromdialer" => "FALSE",
                            "product" => "bath/shower/whatever",
                            "exclusive" => ($Lead_Cost_type == "Exclusive" ? "true" : "false"),
                        );

                        if (config('app.env', 'local') == "local") {
                            //Test
                            $Lead_data_array['addtodialer'] = "FALSE";
                        } else {
                            //Live
                            $Lead_data_array['addtodialer'] = "TRUE";
                        }

                        $httpheader = array(
                            'Accept: application/json',
                            'Content-Type: application/json',
                            "Authorization: Bearer $access_token"
                        );

                        $result = $crm_api_file->api_send_data($url_api_post, $httpheader, $leadsCustomerCampaign_id, json_encode($Lead_data_array), "POST", 1, $crm_details['campaign_id']);
                        if (str_contains($result, 'success') == true) {
                            return 1;
                        }
                    }
                    break;
                case 439:
                    //Momentum Solar 439
                    $url_api = "https://app.leadconduit.com/flows/602d48ebd2fadd5d883d7a7f/sources/65d518b0d82f8cd30adfd295/submit";
                    $httpheader = array("content-type: application/json");

                    $Lead_data_array = array(
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone_1" => $number1,
                        "email" => $email,
                        "address_1" => $street,
                        "city" => $city,
                        "state" => $statename_code,
                        "postal_code" => $zip,
                        "trustedform_cert_url" => $trusted_form,
                        "universal_leadid" => $LeadId,
                        "lead_cost_solar" => "$Lead_Cost",
                        "price" => "$Lead_Cost",
                        "original_source" => $google_ts,
                        "reference" => $leadsCustomerCampaign_id,
                        "campaign_type_solar" => "Solar Exclusive",
                        "list_id_solar" => "3543",
                        "comments" => "From Zone-1-Remodeling",
                    );

                    switch ($crm_details['service_id']) {
                        case 2:
                            //Solar
                            $monthly_electric_bill = trim($crm_details['data']['monthly_electric_bill']);
                            $utility_provider = trim($crm_details['data']['utility_provider']);
                            $roof_shade = trim($crm_details['data']['roof_shade']);
                            $property_type = trim($crm_details['data']['property_type']);

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

                            $Lead_data_array["utility.electric.monthly_amount"] = $monthly_bill;
                            $Lead_data_array["utility.electric.company.name"] = $utility_provider;
                            $Lead_data_array["roof_shade_solar"] = $roof_shade;
                            $Lead_data_array["home_type_solar"] = $property_type;
                            break;
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, stripslashes(json_encode($Lead_data_array)), "POST", 1, $crm_details['campaign_id']);
                    if (strpos("-" . $result, 'success') == true) {
                        return 1;
                    }
                    break;
                case 118:
                    //Select Home Improvements
                    switch ($crm_details['service_id']) {
                        case 1:
                            //Windows
                            $project_nature = trim($crm_details['data']['project_nature']);
                            $taskName = ($project_nature != "Repair" ? "Yes" : "No");
                            break;
                    }
                    $auth_token = "cmunl6b5cf2felow2fix475owyhsc9fe";
                    $url_api = "https://api.convoso.com/v1/leads/insert?auth_token=$auth_token&adaptor_id=&list_id=35905&check_dup=0&check_dup_archive=0&check_dnc=0&check_wireless=0&hopper=1&hopper_priority=99&hopper_expires_in=0&update_if_found=&update_order_by_last_called_time=DESC&blueinkdigital_token=&reject_by_carrier_type=&filter_phone_code=&lead_id=$LeadId&phone_code=1&created_by=&email=$email&last_modified_by=&owner_id=&first_name=$first_name&last_name=$last_name&phone_number=$number1&alt_phone_1=&alt_phone_2=&address1=$street&address2=&city=$city&state=$state&province=$statename_code&postal_code=$zip&country=US&gender=&date_of_birth=&product_need=Windows&tsr_name=&mr_work_schedule=&mrs_work_schedule=&tsr_id=&home_age=&years_owned=&spouse_first_name=&cross_streets=&marital_status=&spouse_lastname=&comments=&lead_appointmenttime=&lead_appointmentdate=&lead_spoketosalutationid=&appointment_month=&appointment_day=&zip_code=$zip&has_original_windows=&needed_windows_to_replace=$taskName&last_time_painted_home=&story_size=&ac_old_or_new=&ac_type=&roof_old_or_new=&roof_type_and_problems=&mobile_phone=&leadsource=&need_count=&company_name=Zone1Remodeling&appointment_date=&transfer=";
                    $url_api = str_replace(" ", "%20", $url_api);
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadsCustomerCampaign_id, "", "POST", 1, $crm_details['campaign_id']);
                    $result2 = json_decode($result, true);
                    if (!empty($result2)) {
                        if (!empty($result2['success'])) {
                            if ($result2['success'] == true) {
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
