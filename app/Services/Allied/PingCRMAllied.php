<?php

namespace App\Services\Allied;

use App\Services\CrmApi;
use App\Jangle;
use App\LeadPortal;
use App\Leads_Pedia;
use App\leads_pedia_track;
use Illuminate\Support\Facades\Log;

class PingCRMAllied
{

    public function pingandpost($campaign, $data_msg, $numberOfCamp, $type, $is_pingandpost, $is_multi_api = 0)
    {
        try {
            $returns_data = 2;
            if ($is_pingandpost != 0) {
                $returns_data = 3;
            }

            $crm_api_file = new CrmApi();
            $TransactionId = '';
            $Payout = 0;
            $Result = 0;
            $multi_type = 0;
            $ping_crm_apis = array();

            $user_id = $campaign->user_id;
            $campaign_id = $campaign->campaign_id;
            $campaign_type = $campaign->campaign_Type;
            $campaign_name = $campaign->campaign_name;

            $lead_source_text = $data_msg['lead_source'];
            $lead_source_name = $data_msg['lead_source_name'];
            $traffic_source = $data_msg['traffic_source'];
            $google_ts = $data_msg['google_ts'];
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
            $request_date = trim(date('m/d/Y'));
            $lead_type_service_id = $data_msg['service_id'];

            //Lead Details Browser
            $UserAgent = $data_msg['UserAgent'];
            $OriginalURL = $data_msg['OriginalURL'];
            $OriginalURL2 = $data_msg['OriginalURL2'];
            $SessionLength = $data_msg['SessionLength'];
            $IPAddress = $data_msg['IPAddress'];
            $LeadId = $data_msg['LeadId'];
            $lead_browser_name = $data_msg['browser_name'];
            $TCPAText = $data_msg['TCPAText'];
            $leadCustomer_id = $data_msg['leadCustomer_id'];
            $Leaddatadetails = $data_msg['Leaddatadetails'];
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

            //CRM
            $compaign_crm_arr = json_decode($campaign->crm, true);
            if (in_array(3, $compaign_crm_arr)) {
                $Leads_PediaDetails = Leads_Pedia::where('campaign_id', $campaign_id)->first();
                if(empty($Leads_PediaDetails)){
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "Leads_PediaDetails"));
                    if($is_multi_api == 0) {
                        $data_response = array(
                            'TransactionId' => $TransactionId,
                            'Payout' => $Payout,
                            'Result' => $Result,
                            'multi_type' => $multi_type,
                            'campaign_id' => $campaign_id
                        );

                        return json_encode($data_response);
                    } else {
                        return $ping_crm_apis;
                    }
                }

                $lp_campaign_id = $Leads_PediaDetails['IP_Campaign_ID'];
                $lp_campaign_key = $Leads_PediaDetails['campine_key'];
                $lp_url = $Leads_PediaDetails['leads_pedia_url_ping'];

                $httpheader = array(
                    "cache-control: no-cache",
                    "Accept: application/json",
                    "content-type: application/json"
                );

                switch ($lead_type_service_id){
                    case 2:
                        //Solar
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);

                        switch ($user_id){
                            case 22:
                                //Lead Cactus
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
                                        $roof_shade_data = "A Lot Of Shade";
                                        break;
                                    case "Partial Shade":
                                        $roof_shade_data = "A Little Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Uncertain";
                                }

                                $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');

                                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&first_name=$first_name&last_name=$last_name&phone_cell=$number1&address=$street&city=$city&state=$state&zip_code=$zip&email_address=$email&average_bill=$average_bill&electricUtilityProviderText=$utility_provider&roof_shade=$roof_shade_data&homeowner=$homeowner&xxTrustedFormCertUrl=$trusted_form&jornaya_lead_id=$LeadId&ip_address=$IPAddress&lp_s1=$lead_source_text&lp_s2=$lead_source_text&lp_s3=$lead_source_text";
                                break;
                            case 185:
                                //snk media group 185
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

                                switch ($roof_shade){
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

                                $homeowner = ($property_type == 'Rented' ? 'No' : 'Yes');

                                if ($trusted_form == "NA" || $trusted_form == "N/A"
                                    || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                                    || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                                    || $trusted_form == "https://cert.trustedform.com") {
                                    $trusted_form = "";
                                }

                                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&lp_s2=$google_ts&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&roof_shade=$roof_shade_data&homeownership=$homeowner&electricity_bill=$average_bill&utility_provider=Other&jornaya_id=$LeadId&trusted_form_url=$trusted_form&landing_page_url=$OriginalURL&TCPA_Language=$TCPAText&TrustedForm_Yes_No=Yes&JornayaID_Yes_No=Yes&TF_Yes_No=Yes&Jornaya_Yes_No=Yes";
                                break;
                        }
                        break;
                }

                if (config('app.env', 'local') == "local") {
                    //Test Mode
                    $url_api .= "&lp_test=1";
                }

                $url_api = str_replace(" ", "%20", $url_api);

                $ping_crm_apis = array(
                    "url" => $url_api,
                    "header" => $httpheader,
                    "lead_id" => $leadCustomer_id,
                    "inputs" => '',
                    "method" => "GET",
                    "campaign_id" => $campaign_id,
                    "service_id" => $lead_type_service_id,
                    "user_id" => $user_id,
                    "returns_data" => $returns_data,
                    "crm_type" => 3
                );

                if($is_multi_api == 0){
                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, '', "GET", $returns_data, $campaign_id);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['result'])) {
                        if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                            $TransactionId = $result2['ping_id'];
                            $Payout = $result2['price'];
                            $multi_type = 0;
                            $Result = 1;
                        }
                    }
                }
            }
            else if (in_array(7, $compaign_crm_arr)) {
                $JangleDetails = Jangle::where('campaign_id', $campaign_id)->first();
                if(empty($JangleDetails)){
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "JangleDetails"));
                    if($is_multi_api == 0) {
                        $data_response = array(
                            'TransactionId' => $TransactionId,
                            'Payout' => $Payout,
                            'Result' => $Result,
                            'multi_type' => $multi_type,
                            'campaign_id' => $campaign_id
                        );

                        return json_encode($data_response);
                    } else {
                        return $ping_crm_apis;
                    }
                }

                $httpheader = array(
                    "Authorization: Token " . $JangleDetails->Authorization,
                    "content-type: application/json"
                );

                switch ($user_id) {
                    case 375:
                        //Blue ink digital	375
                        if (config('app.env', 'local') == "local" || !empty($data_msg['is_test'])) {
                            //Test Mode
                            $url_api = "https://test-api.gateway.blueinkanalytics.com/v2/home_improvement/ping";
                        } else {
                            $url_api = $JangleDetails->PingUrl;
                        }
                        break;
                    default:
                        $url_api = $JangleDetails->PingUrl;
                }

                $subid = $lead_source_text;
                $date = date('Y-m-d H:i:s');

                $Lead_data_array_ping = array(
                    "meta" => array(
                        "originally_created" => $date,
                        "source_id" => $subid,
                        "offer_id" => $leadCustomer_id,
                        "lead_id_code" => $LeadId,
                        "trusted_form_cert_url" => $trusted_form,
                        "user_agent" => $UserAgent,
                        "landing_page_url" => $OriginalURL2,
                        "tcpa_compliant" => $tcpa_compliant4,
                        "tcpa_consent_text" => $TCPAText
                    ),
                    "contact" => array(
                        "phone_last_four" => "",
                        "zip_code" => $zip,
                        "ip_address" => $IPAddress
                    ),
                );

                $best_call_time = "Any time";
                if ($campaign_type == 4 && $data_msg['appointment_date'] != "") {
                    $best_call_time = date('m/d/Y h:i A', strtotime($data_msg['appointment_date'])) . " EST";
                }
                switch ($lead_type_service_id) {
                    case 1:
                        //Windows
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $number_of_windows = trim($Leaddatadetails['number_of_window']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);

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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                        $Installation_Preferences = trim($Leaddatadetails['Installation_Preferences']);
                        $lead_have_item_before_it = trim($Leaddatadetails['lead_have_item_before_it']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $roof_type = trim($Leaddatadetails['roof_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $service_kitchen = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                        $service_kitchen_data = ($service_kitchen == "Full Kitchen Remodeling" ? "Floor plan" : "Cabinets");

                        $Lead_data_array_ping['data'] = array(
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
                        $bathroom_type_name = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                        switch ($bathroom_type_name){
                            case "Shower / Bath" || "Sinks" || "Toilet":
                                $bathroom_type_name_data = "Bath, sinks";
                                break;
                            default:
                                $bathroom_type_name_data = "Full bathroom";
                        }

                        $Lead_data_array_ping['data'] = array(
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
                        $stairs_type = trim($Leaddatadetails['stairs_type']);
                        $reason = trim($Leaddatadetails['reason']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                        $stairs_type_data = ($stairs_type == "Straight" ? "Straight staircase" : "Curved staircase");

                        $Lead_data_array_ping['data'] = array(
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
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                        $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New unit installed");
                        $type_of_heating_data = "Central AC";

                        $Lead_data_array_ping['data'] = array(
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                        $project_nature_data = ($project_nature == "Cabinet Refacing" ? "Reface existing cabinets" : "Install new custom cabinets");

                        $Lead_data_array_ping['data'] = array(
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
                        $services = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
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
                        $services = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

                        $SecurityUsage = "true";
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                        $Lead_data_array_ping['data'] = array(
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
                        $handyman_ammount_name = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                        $Lead_data_array_ping['data'] = array(
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
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $door_type = trim($Leaddatadetails['door_type']);
                        $number_of_door = trim($Leaddatadetails['number_of_door']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");
                        $project_nature_data = ($project_nature == "Repair" ? "Repair" : "New installation");

                        $Lead_data_array_ping['data'] = array(
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
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $service = trim($Leaddatadetails['service']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        $SecurityUsage = ($ownership == "Yes" ? "true" : "false");
                        $start_time_data = ($start_time == "Immediately" ? "Immediately" : "1-3 months");

                        $Lead_data_array_ping['data'] = array(
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
                        $service_type = trim($Leaddatadetails['service']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => $best_call_time,
                            "own_property" => $SecurityUsage,
                            "purchase_time_frame" => $start_time_data,
                            "painting" => array(
                                "project_type" => $service_type_data
                            )
                        );
                        break;
                }

                $ping_crm_apis = array(
                    "url" => $url_api,
                    "header" => $httpheader,
                    "lead_id" => $leadCustomer_id,
                    "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                    "method" => "POST",
                    "campaign_id" => $campaign_id,
                    "service_id" => $lead_type_service_id,
                    "user_id" => $user_id,
                    "returns_data" => $returns_data,
                    "crm_type" => 7
                );

                if($is_multi_api == 0){
                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                    $result2 = json_decode($result, true);
                    if (!empty($result2)) {
                        if (!empty($result2['status'])) {
                            if ($result2['status'] == "success") {
                                $TransactionId = $result2['auth_code'];
                                $Payout = $result2['price'];
                                $multi_type = 0;
                                $Result = 1;
                            }
                        }
                    }
                }
            }
            else if (in_array(12, $compaign_crm_arr)) {
                $LeadPortalDetails = LeadPortal::where('campaign_id', $campaign_id)->first();
                if(empty($LeadPortalDetails)){
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "leadPortalCrm"));
                    if($is_multi_api == 0) {
                        $data_response = array(
                            'TransactionId' => $TransactionId,
                            'Payout' => $Payout,
                            'Result' => $Result,
                            'multi_type' => $multi_type,
                            'campaign_id' => $campaign_id
                        );

                        return json_encode($data_response);
                    } else {
                        return $ping_crm_apis;
                    }
                }

                $httpheader = array(
                    "cache-control: no-cache",
                    "Accept: application/json",
                    "content-type: application/json"
                );

                $Repair_Project = "No";
                switch ($lead_type_service_id){
                    case 1:
                        //windows
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $number_of_windows = trim($Leaddatadetails['number_of_window']);

                        switch ($user_id){
                            case 553:
                                //conXpros 553
                                $Repair_Project = ($project_nature == "Repair" ? "Yes" : "No");
                                $Project = (($number_of_windows == 1 || $number_of_windows == 2) ? "Windows-2 or less" : "Windows-3+");
                                break;
//                            case 760:
//                                //Hello Project USA 760
//                                if ($project_nature == "Repair") {
//                                    $Project = "Windows Repair";
//                                } else {
//                                    $Project = ($number_of_windows == 1 ? "Windows Replace - Single" : "Windows Replace - Multiple");
//                                }
//                                break;
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
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);
                        $power_solution = trim($Leaddatadetails['power_solution']);

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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Repair_Project = ($project_nature == "Repair existing roof" ? "Yes" : "No");
                        $Project = "Roofing";
                        break;
                    case 7:
                        //Home Siding
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id){
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

                if ($lead_type_service_id == 2) {
                    $type = "26";
                    if (!empty($LeadPortalDetails->type)) {
                        $type = $LeadPortalDetails->type;
                    }
                    $Lead_data_array_ping = array(
                        "Request" => array(
                            "Key" => $LeadPortalDetails->key,
                            "API_Action" => "pingPostLead",
                            "Format" => "JSON",
                            "Mode" => "ping",
                            "Return_Best_Price" => "1",
                            "TYPE" => $type,
                            "TCPA_Consent" => $tcpa_compliant2,
                            "TCPA" => $tcpa_compliant2,
                            "TCPA_Language" => $TCPAText,
                            "LeadiD_Token" => $LeadId,
                            "IP_Address" => $IPAddress,
                            "SRC" => $LeadPortalDetails->SRC,
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
                            "Landing_Page" => $OriginalURL2,
                            "User_Agent" => $UserAgent,
                            "Trusted_Form_URL" => $trusted_form,
                            "xxTrustedFormCertUrl" => $trusted_form
                        )
                    );
                }
                else if ($lead_type_service_id == 24) {
                    $type = (!empty($LeadPortalDetails->type) ? $LeadPortalDetails->type : "33");
                    //Auto Insurance
                    $VehicleYear = trim($Leaddatadetails['VehicleYear']);
                    $VehicleMake = trim($Leaddatadetails['VehicleMake']);
                    $car_model = trim($Leaddatadetails['car_model']);
                    $more_than_one_vehicle = trim($Leaddatadetails['more_than_one_vehicle']);
                    $driversNum = trim($Leaddatadetails['driversNum']);
                    $birthday = trim($Leaddatadetails['birthday']);
                    $genders = trim($Leaddatadetails['genders']);
                    $married = trim($Leaddatadetails['married']);
                    $license = trim($Leaddatadetails['license']);
                    $InsuranceCarrier = trim($Leaddatadetails['InsuranceCarrier']);
                    $driver_experience = trim($Leaddatadetails['driver_experience']);
                    $number_of_tickets = trim($Leaddatadetails['number_of_tickets']);
                    $DUI_charges = trim($Leaddatadetails['DUI_charges']);
                    $SR_22_need = trim($Leaddatadetails['SR_22_need']);
                    $ownership = trim($Leaddatadetails['homeOwn']);

                    $Lead_data_array_ping = array(
                        "Request" => array(
                            "Key" => $LeadPortalDetails->key,
                            "API_Action" => "pingPostLead",
                            "Format" => "JSON",
                            "Mode" => "ping",
                            "Return_Best_Price" => "1",
                            "TYPE" => $type,
                            "IP_Address" => $IPAddress,
                            "SRC" => $LeadPortalDetails->SRC,
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
                            "Driver_1_DUI_DWI_In_The_Past_5_Years" => ($DUI_charges == "Yes" ? "Yes" : "No")
                        )
                    );
                }
                else {
                    if( !empty($Leaddatadetails['homeOwn']) ){
                        $ownership = ($Leaddatadetails['homeOwn'] != "Yes" ? "No" : "Yes");
                    } else {
                        if( !empty($Leaddatadetails['property_type']) ){
                            $ownership = ($Leaddatadetails['property_type'] == "Rented"  ? "No" : "Yes");
                        } else {
                            $ownership = "Yes";
                        }
                    }

                    $type = "18";
                    if (!empty($LeadPortalDetails->type)) {
                        $type = $LeadPortalDetails->type;
                    }
                    $Lead_data_array_ping = array(
                        "Request" => array(
                            "Key" => $LeadPortalDetails->key,
                            "API_Action" => "pingPostLead",
                            "Format" => "JSON",
                            "Mode" => "ping",
                            "Return_Best_Price" => "1",
                            "TYPE" => $type,
                            "IP_Address" => $IPAddress,
                            "SRC" => $LeadPortalDetails->SRC,
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
                            "Landing_Page" => $OriginalURL2,
                            "User_Agent" => $UserAgent,
                            "Trusted_Form_URL" => $trusted_form,
                            "xxTrustedFormCertUrl" => $trusted_form
                        )
                    );

                    switch($user_id){
                        case 553:
                            //conXpros 553
                            $Lead_data_array_ping['Request']['Repair'] = $Repair_Project;
                            break;
                        case 760:
                            //760 hello project USA
                            unset($Lead_data_array_ping['Request']['Return_Best_Price']);
                            $Lead_data_array_ping['Request']['Return_Lead_Cost'] = "1";
                            break;
                    }
                }

                $url_api = $LeadPortalDetails->api_url;

                if (config('app.env', 'local') == "local") {
                    //Test Mode
//                    if ($lead_type_service_id != 2) {
//                        $Lead_data_array_ping['Request']['Project'] = "Alarm/ security system - Install";
//                    }

                    $Lead_data_array_ping['Request']['State'] = "AK";
                    $Lead_data_array_ping['Request']['Zip'] = "99999";
                    $Lead_data_array_ping['Request']['Test_Lead'] = "1";
                }

                $ping_crm_apis = array(
                    "url" => $url_api,
                    "header" => $httpheader,
                    "lead_id" => $leadCustomer_id,
                    "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                    "method" => "POST",
                    "campaign_id" => $campaign_id,
                    "service_id" => $lead_type_service_id,
                    "user_id" => $user_id,
                    "returns_data" => $returns_data,
                    "crm_type" => 12
                );

                if($is_multi_api == 0) {
                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['response'])) {
                        $result3 = $result2['response'];
                        if (!empty($result3['status'])) {
                            if ($result3['status'] == "Matched") {
                                $TransactionId = $result3['lead_id'];
                                if ($user_id == 760){
                                    //hello project USA 760
                                    $Payout = $result3['cost'];
                                } else {
                                    $Payout = $result3['price'];
                                }
                                $multi_type = 0;
                                $Result = 1;
                            }
                        }
                    }
                }
            }
            else if (in_array(13, $compaign_crm_arr)) {
                $leads_pedia_track = leads_pedia_track::where('campaign_id', $campaign_id)->first();
                if(empty($leads_pedia_track)){
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "leads_pedia_track"));
                    if($is_multi_api == 0) {
                        $data_response = array(
                            'TransactionId' => $TransactionId,
                            'Payout' => $Payout,
                            'Result' => $Result,
                            'multi_type' => $multi_type,
                            'campaign_id' => $campaign_id
                        );

                        return json_encode($data_response);
                    } else {
                        return $ping_crm_apis;
                    }
                }

                $lp_campaign_id = $leads_pedia_track['lp_campaign_id'];
                $lp_campaign_key = $leads_pedia_track['lp_campaign_key'];
                $lp_url = $leads_pedia_track['ping_url'];

                $httpheader = array(
                    "cache-control: no-cache",
                    "Accept: application/json",
                    "content-type: application/json"
                );

                $timestamp = date('Y-m-d H:i:s');

                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=$lead_source_text&lp_response=JSON&city=$city&state=$statename_code&zip_code=$zip&ip_address=$IPAddress";

                switch ($user_id){
                    case 25:
                        //pbtp Powered by The People LLC 540
                        $type_data = ($type == 1 ? "Exclusive" : "Shared");
                        $source_id = "6461";
                        $source_key = "XP-6461";

                        $url_api .= "&leadtoken=$LeadId&type=$type_data&source_id=$source_id&source_key=$source_key&tcpa=$tcpa_compliant2&tcpa_text=$TCPAText&TrustedForm=$trusted_form";
                        break;
                }

                switch ($lead_type_service_id) {
                    case 1:
                        //Windows
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $number_of_windows = trim($Leaddatadetails['number_of_window']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id) {
                            case 25:
                                //pbtp Powered by The People LLC 540
                                if ($project_nature == "Repair") {
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

                                switch ($number_of_windows) {
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
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);
                        $power_solution = trim($Leaddatadetails['power_solution']);

                        switch ($user_id) {
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
                        $Installation_Preferences = trim($Leaddatadetails['Installation_Preferences']);
                        $lead_have_item_before_it = trim($Leaddatadetails['lead_have_item_before_it']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        switch ($user_id){
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
                        $roof_type = trim($Leaddatadetails['roof_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

                        switch ($user_id){
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
                        $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        switch ($user_id){
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
                        $service_kitchen = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
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
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $bathroom_type = trim($Leaddatadetails['services']);

                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        switch ($user_id){
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
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
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
                        $service = trim($Leaddatadetails['service']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
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

                $url_api = str_replace(" ", "%20", $url_api);

                $ping_crm_apis = array(
                    "url" => $url_api,
                    "header" => $httpheader,
                    "lead_id" => $leadCustomer_id,
                    "inputs" => "",
                    "method" => "POST",
                    "campaign_id" => $campaign_id,
                    "service_id" => $lead_type_service_id,
                    "user_id" => $user_id,
                    "returns_data" => $returns_data,
                    "crm_type" => 13
                );

                if($is_multi_api == 0) {
                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, "", "POST", $returns_data, $campaign_id);
                    $result2 = json_decode($result, true);
                    if (!empty($result2['result'])) {
                        if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                            $TransactionId = $result2['ping_id'];
                            $Payout = $result2['price'];
                            $multi_type = 0;
                            $Result = 1;
                        }
                    }
                }
            }
            else {
                switch ($user_id) {
                    case 15:
                        //RGR Marketing 15
                        $httpheader = array(
                            "cache-control: no-cache",
                            "Accept: application/json",
                            "content-type: application/json"
                        );

                       if (empty($IPAddress) || $IPAddress == null ) {
                            $IPAddress = "172.58.15.245";
                        }

                        switch ($lead_type_service_id) {
                            case 1:
                                //Window
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $ProjectType = ($project_nature == "Repair" ? "repair" : "replace");

                                $Lead_data_array_ping = array(
                                    "type" => "homeimprovementwindow",
                                    "publisher_id" => "494",
                                    "rcid" => "1158",
                                    "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                    "subid" => $google_ts,
                                    "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                    "tcpa" => $TCPAText,
                                    "state" => $statename_code,
                                    "zip" => $zip,
                                    "homeowner" => $homeowner, //Home Owner
                                    "projecttype" => $ProjectType, //project nature
                                    "windowcount" => $number_of_windows, //number of windows
                                    "ipaddress" => $IPAddress,
                                );
                                break;
                            case 2:
                                //Solar
                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);

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

                                $Lead_data_array_ping = array(
                                    "publisher_id" => "494",
                                    "rcid" => "1100",
                                    "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                    "type" => "solar",
                                    "state" => $statename_code,
                                    "zip" => $zip,
                                    "electricityprovider" => $utility_provider, //Electricity Provider
                                    "creditscore" => "good", //Credit Status
                                    "powerbill" => $monthly_bill, //Power Bill.
                                    "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                    "homeowner" => $homeowner, //home owner
                                    "roofshade" => $roof_shade_data, //Roof Shade
                                    "ipaddress" => $IPAddress,
                                );
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $property_type = trim($Leaddatadetails['property_type']);

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

                                $Lead_data_array_ping = array(
                                    "type" => "homeimprovementroofing",
                                    "publisher_id" => "494",
                                    "rcid" => "1159",
                                    "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                    "subid" => $google_ts,
                                    "ipaddress" => $IPAddress,
                                    "leadidtoken" => $LeadId,//Jornaya LeadiD Token
                                    "tcpa" => $TCPAText,
                                    "zip" => $zip,
                                    "homeowner" => "yes",//HomeOwner
                                    "projecttype" => $ProjectType, //project nature
                                    "roofmaterial" => $roofmaterial, //number of windows
                                );
                                break;
                            case 7:
                                //siding
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

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

                                $Lead_data_array_ping = array(
                                    "type" => "homeimprovementsiding",
                                    "publisher_id" => "494",
                                    "rcid" => "1160",
                                    "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                    "subid" => $google_ts,
                                    "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                    "tcpa" => $TCPAText,
                                    "zip" => $zip,
                                    "homeowner" => $homeowner, //Home Owner
                                    "projecttype" => $projecttype, //project nature
                                    "sidingtype" => $SidingType, //Siding Type
                                    "ipaddress" => $IPAddress,
                                );
                                break;
                            case 9:
                                //Bathroom
                                $bathroom_type_name = trim($Leaddatadetails['services']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $projecttype = "new bath";

                                $Lead_data_array_ping = array(
                                    "type" => "homeimprovementbath",
                                    "publisher_id" => "494",
                                    "rcid" => "1161",
                                    "apikey" => "3510f2c2-ddec-4935-9ca1-6949e7fc64cb",
                                    "subid" => $google_ts,
                                    "leadidtoken" => $LeadId, //Jornaya LeadiD Token
                                    "tcpa" => $TCPAText,
                                    "zip" => $zip,
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
                                    $Lead_data_array_ping['subid'] = "matched";
                                    $url_api = "https://api.reallygreatrate.com/test/pingpost/ping";
                                } else {
                                    $url_api = "https://api.reallygreatrate.com/lead/pingpost/ping";
                                }

                                $ping_crm_apis = array(
                                    "url" => $url_api,
                                    "header" => $httpheader,
                                    "lead_id" => $leadCustomer_id,
                                    "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                                    "method" => "POST",
                                    "campaign_id" => $campaign_id,
                                    "service_id" => $lead_type_service_id,
                                    "user_id" => $user_id,
                                    "returns_data" => $returns_data,
                                    "crm_type" => 0
                                );

                                if($is_multi_api == 0) {
                                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "matched") {
                                            $TransactionId = $result2['ping_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                }
                                break;
                        }
                        break;
                    case 18:
                        //HELLO PROJECT USA 760
                        $url_api = "https://helloprojectusa.leadportal.com/apiJSON.php";
                        $Key = "3c47b231abb663ec2b8266279033f56cd30def9c705ec71b1a2be17cd1c88072";
                        $API_Action = "pingPostLead";
                        $TYPE = 18;
                        $SRC = "Thryvea_Internal";
                        $Sub_ID = "Thry20";
                        $Pub_ID = "Thry20";
                        $Format = "JSON";
                        switch ($lead_type_service_id){
                            case 1:
                                $Project = "Windows";
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                break;
                            case 2:
                                $Project = "Solar";
                                $property_type = trim($Leaddatadetails['property_type']);
                                $ownership = ($property_type == 'Rented' ? 'No' : 'Yes');
                                break;
                            case 6:
                                $Project = "Roofing";
                                $ownership = "Yes";
                                break;
                            case 9:
                                $Project = "Bathroom Remodeling";
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                break;
                            default:
                                $Project = "";
                        }

                        if( config('app.env', 'local') == "local" ) {
                            //Test Mode
                            $statename_code = "IL";
                            $zip = "99999";
                            $IPAddress = "75.2.92.149";
                        }

                        $Lead_data_array_ping = array(
                            "Request" => array(
                                "Key" => $Key,
                                "API_Action" => $API_Action,
                                "Format" => $Format,
                                "Mode" => "ping",
                                "Return_Best_Price" => "1",
                                "TYPE" => $TYPE,
                                "SRC" => $SRC,
                                "Pub_ID" => $Pub_ID,
                                "Sub_ID" => $Sub_ID,
                                "Homeowner" => $ownership,
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
                            $Lead_data_array_ping['Test_Lead'] = "1";
                        }

                        $httpheader = array(
                            "cache-control: no-cache",
                            "Accept: application/json",
                            "content-type: application/json"
                        );

                        $ping_crm_apis = array(
                            "url" => $url_api,
                            "header" => $httpheader,
                            "lead_id" => $leadCustomer_id,
                            "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                            "method" => "POST",
                            "campaign_id" => $campaign_id,
                            "service_id" => $lead_type_service_id,
                            "user_id" => $user_id,
                            "returns_data" => $returns_data,
                            "crm_type" => 0
                        );

                        if($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['response'])) {
                                $result3 = $result2['response'];
                                if (!empty($result3['status'])) {
                                    if ($result3['status'] == "Matched") {
                                        $TransactionId = $result3['lead_id'];
                                        $Payout = $result3['price'];
                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                }
                            }
                        }
                        break;
                    case 23:
                        //HomeQuote 23

                        $httpheader = array(
                            "Content-Type: application/x-www-form-urlencoded",
                            "Accept: application/json",
                        );

                        if (empty($trusted_form) || $trusted_form == null ) {
                            $trusted_form = "https://cert.trustedform.com/b71a7be83072f19bdd6dc4945be25456759".rand(10000, 99999);
                        }

                        $Lead_data_ping = "ip=$IPAddress&useragent=$UserAgent&country_iso_2=US&region=$statename_code&referrer=$OriginalURL2&zipcode=$zip&trusted_form_cert_url=$trusted_form&trusted_form_cert_id=$trusted_form";

                        switch ($lead_type_service_id){
                            case 1:
                                //windows
                                $PingURLTest ="https://cdgtrck.com/api/v1/ping?a=419&c=340&cmp=5828&cmp_key=0hvb79ejmw&post_test=true";
                                $PingURL ="https://cdgtrck.com/api/v1/ping?a=419&c=340&cmp=5828&cmp_key=0hvb79ejmw";

                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $project_nature = trim($Leaddatadetails['project_nature']);

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

                                $Lead_data_ping .="&number=$number_of_windows_data&project=$project_nature_data&homeowner=$homeowner&authorized_to_make_changes=$authorized_to_make_changes";
                                break;
                            case 2:
                                //Solar
                                $PingURLTest ="https://cdgtrck.com/api/v1/ping?a=419&c=333&cmp=5826&cmp_key=8ks2to5r1l&post_test=true";
                                $PingURL ="https://cdgtrck.com/api/v1/ping?a=419&c=333&cmp=5826&cmp_key=8ks2to5r1l";

                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);
                                $power_solution = trim($Leaddatadetails['power_solution']);

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

                                $Lead_data_ping .= "&homeowner=$OwnHome&electric_bill=$average_bill&electric_utility_provider=$utility_provider&roof_shade=$roof_shade_data";
                                break;
                            case 6:
                                //Roofing
                                $PingURLTest ="https://cdgtrck.com/api/v1/ping?a=419&c=328&cmp=5827&cmp_key=p8uvjlkrwz&post_test=true";
                                $PingURL ="https://cdgtrck.com/api/v1/ping?a=419&c=328&cmp=5827&cmp_key=p8uvjlkrwz";

                                $roof_type = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);

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

                                $Lead_data_ping .= "&roof_material=$service&project=$project_nature_data&homeowner=$ownership_data&type_material=$type_material";
                                break;
                            case 9:
                                //bathroom
                                $PingURLTest ="https://cdgtrck.com/api/v1/ping?a=419&c=339&cmp=5825&cmp_key=pdvyf0knse&post_test=true";
                                $PingURL ="https://cdgtrck.com/api/v1/ping?a=419&c=339&cmp=5825&cmp_key=pdvyf0knse";

                                $bathroom_type = trim($Leaddatadetails['services']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

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

                                $Lead_data_ping .= "&project=$bathroom_type_data&homeowner=$ownership&timeframe=$BuyTimeframe";
                                break;
                        }

                        //$Lead_data_ping = str_replace(" ", "+", $Lead_data_ping);

                        if (config('app.env', 'local') == "local") {
                            $url_api_ping = $PingURLTest;
                        } else {
                            $url_api_ping = $PingURL;
                        }

                        $ping_crm_apis = array(
                            "url" => $url_api_ping,
                            "header" => $httpheader,
                            "lead_id" => $leadCustomer_id,
                            "inputs" => $Lead_data_ping,
                            "method" => "POST",
                            "campaign_id" => $campaign_id,
                            "service_id" => $lead_type_service_id,
                            "user_id" => $user_id,
                            "returns_data" => $returns_data,
                            "crm_type" => 0
                        );

                        if($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api_ping, $httpheader, $leadCustomer_id, $Lead_data_ping, "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['Result'])) {
                                if ($result2['Result'] == 'Success') {
                                    $TransactionId = $result2['PingId'];
                                    $Payout = $result2['Payout'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 29:
                        //Clean Energy Authoroty 29
                        switch ($lead_type_service_id) {
                            case 1:
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

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
                                    $url_api_ping = "https://uat.sbbnetinc.com/rest/api/windows/submit-inquiry-json";
                                    $source_name = "TESTTCPA";
                                } else {
                                    //Live Mode
                                    $url_api_ping = "https://live.sbbnetinc.com/rest/api/windows/submit-inquiry-json";
                                    $source_name = "thrwi";
                                }

                                if (empty($trusted_form) || $trusted_form == null ) {
                                    $trusted_form = "";
                                }

                                $Lead_data_array_ping = array(
                                    "Request" => array(
                                        "API_Action" => "pingPostLead",
                                        "Mode" => "ping",
                                        "Return_Best_Price" => "1",
                                        "TYPE" => "18",
                                        "IP_Address" => $IPAddress,
                                        "SRC" => $source_name,
                                        "Landing_Page" => $OriginalURL2,
                                        "Sub_ID" => $leadCustomer_id,
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
                                        "Tcpa_Language" => $TCPAText,
                                        "vendor_lead_id" => $leadCustomer_id
                                    )
                                );
                                $httpheader = array(
                                    'Authorization: Basic cmVzdC11c2VyOjVTOGNCRHEmRWYha3BMKk5XNXVM',
                                    'Content-Type: application/json',
                                    //'Accept: application/xml',
                                );

                                $ping_crm_apis = array(
                                    "url" => $url_api_ping,
                                    "header" => $httpheader,
                                    "lead_id" => $leadCustomer_id,
                                    "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                                    "method" => "POST",
                                    "campaign_id" => $campaign_id,
                                    "service_id" => $lead_type_service_id,
                                    "user_id" => $user_id,
                                    "returns_data" => $returns_data,
                                    "crm_type" => 0
                                );

                                if($is_multi_api == 0) {
                                    $result = $crm_api_file->api_send_data($url_api_ping, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['Status']) && $result2['Status'] === 'Match') {
                                        $TransactionId = $result2['PingId'];
                                        // Find the offer with the highest price
                                        $bestOffer = collect($result2['Offers'])->sortByDesc('Price')->first();
                                        // Extract details from best offer
                                        $Price = $bestOffer['Price'];
                                        $OfferId = $bestOffer['OfferId'];
                                        $Payout = $OfferId . '|' . $Price;

                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                }
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $property_type = trim($Leaddatadetails['property_type']);
                                $start_time = trim($Leaddatadetails['start_time']);

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
                                    $url_api_ping = "https://uat.sbbnetinc.com/rest/api/roofing/submit-inquiry-json";
                                    $source_name = "TESTTCPA";
                                } else {
                                    //Live Mode
                                    $url_api_ping = "https://live.sbbnetinc.com/rest/api/roofing/submit-inquiry-json";
                                    $source_name = "thrro";
                                }

                                $Lead_data_array_ping = array(
                                    "Request" => array(
                                        "API_Action" => "pingPostLead",
                                        "Format" => "JSON",
                                        "Mode" => "ping",
                                        "Return_Best_Price" => "1",
                                        "TYPE" => "16",
                                        "IP_Address" => $IPAddress,
                                        "SRC" => $source_name,
                                        "Landing_Page" => $OriginalURL2,
                                        "Sub_ID" => $leadCustomer_id,
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
                                        "Tcpa_Language" => $TCPAText,
                                        "vendor_lead_id" => $leadCustomer_id
                                    )
                                );

                                $httpheader = array(
                                    'Authorization: Basic cmVzdC11c2VyOjVTOGNCRHEmRWYha3BMKk5XNXVM',
                                    'Content-Type: application/json',
                                );

                                $ping_crm_apis = array(
                                    "url" => $url_api_ping,
                                    "header" => $httpheader,
                                    "lead_id" => $leadCustomer_id,
                                    "inputs" => stripslashes(json_encode($Lead_data_array_ping)),
                                    "method" => "POST",
                                    "campaign_id" => $campaign_id,
                                    "service_id" => $lead_type_service_id,
                                    "user_id" => $user_id,
                                    "returns_data" => $returns_data,
                                    "crm_type" => 0
                                );

                                if($is_multi_api == 0) {
                                    $result = $crm_api_file->api_send_data($url_api_ping, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['Status']) && $result2['Status'] === 'Match') {
                                        $TransactionId = $result2['PingId'];
                                        // Find the offer with the highest price
                                        $bestOffer = collect($result2['Offers'])->sortByDesc('Price')->first();
                                        // Extract details from best offer
                                        $Price = $bestOffer['Price'];
                                        $OfferId = $bestOffer['OfferId'];
                                        $Payout = $OfferId . '|' . $Price;

                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                }
                                break;
                        }
                        break;
                    case 32:
                        //Energy Pal 1291
                        $url_api = "https://api.energypal.com/api/v1/leads/ping";
                        $httpheader = array(
                            "Accept: application/json",
                            "Content-Type: application/json",
                        );

                        $Lead_data_array = array(
                            "cid" => "zroimatoeai5b5br",
                            "country" => "US",
                            "state" => $statename_code,
                            "zip" => $zip,
                            "universal_leadid" => $LeadId,
                        );

                        switch ($lead_type_service_id){
                            case 2:
                                //Solar
                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);

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


                                $Lead_data_array['electric_bill'] = $average_bill;
                                $Lead_data_array['electric_utility'] = $utility_provider;
                                break;
                        }

                        if( config('app.env', 'local') == "local" || !empty($data_msg['is_test']) ) {
                            //Test Mode
                            $Lead_data_array['test'] = true;
                            $Lead_data_array['zip'] = "90001";
                            $Lead_data_array['state'] = "CA";
                        }

                        $ping_crm_apis = array(
                            "url" => $url_api,
                            "header" => $httpheader,
                            "lead_id" => $leadCustomer_id,
                            "inputs" => stripslashes(json_encode($Lead_data_array)),
                            "method" => "POST",
                            "campaign_id" => $campaign_id,
                            "service_id" => $lead_type_service_id,
                            "user_id" => $user_id,
                            "returns_data" => $returns_data,
                            "crm_type" => 0
                        );

                        if($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['status'])) {
                                if ($result2['status'] == "accepted") {
                                    $TransactionId = $result2['ping_id'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                }
            }

            if($is_multi_api == 0) {
                $data_response = array(
                    'TransactionId' => $TransactionId,
                    'Payout' => $Payout,
                    'Result' => $Result,
                    'multi_type' => $multi_type,
                    'campaign_id' => $campaign->campaign_id
                );

                return json_encode($data_response);
            } else {
                return $ping_crm_apis;
            }
        } catch (Exception $e) {

        }
    }
}
