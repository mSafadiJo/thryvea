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
                    case 303:
                        //TradeMarc Global LLC 303
                        $url_api .= "&lp_s2=$google_ts&trustedform_url=$trusted_form&universal_leadid=$LeadId&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId&tcpa=$TCPAText&landing_page=$OriginalURL2&landing_url=$OriginalURL2&landing_page_url=$OriginalURL2&tcpa_language=$TCPAText";
                        break;
                    case 312:
                        //Purified Leads/ Sigora Solar 312
                        $url_api .= "&lp_s2=$lead_source_text&xx_trusted_form_cert_url=$trusted_form&leadid_token=$LeadId&tcpa_language=$TCPAText&tcpa_consent=$tcpa_compliant2&landingpage_url=$OriginalURL2";
                        break;
                    case 22:
                        //Lead Cactus LLC 22
                        $url_api .= "&lp_s2=$lead_source_text&lp_s4=$OriginalURL2&landing_page_url=$OriginalURL2&universal_leadid=$LeadId&xxTrustedFormCertUrl=$trusted_form&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&tcpa_text=$TCPAText&user_agent=$UserAgent";
                        break;
                    case 384:
                        //Encompass Leads LLC 384
                        $url_api .= "&lp_s2=$lead_source_text&source_url=$OriginalURL2&source_page=$OriginalURL2&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&tcpa_optin=$tcpa_compliant2&tcpa_text=$TCPAText&timestamp=$timestamp&user_agent=$UserAgent";
                        break;
                    case 394:
                        //Advertising Results 394
                        if ($trusted_form == "NA" || $trusted_form == "N/A" || empty($trusted_form)
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com"
                            || empty($OriginalURL2)) {
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
                        }

                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=ACS37&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress";
                        $UserAgent = (!empty($UserAgent) ? $UserAgent : "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36");
                        $url_api .= "&lp_s2=$google_ts&lp_s3=$lead_source_text&landing_page_url=$OriginalURL2&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&tcpa_consent=$tcpa_compliant3&tcpa_compliant=$tcpa_compliant3&user_agent=$UserAgent";

                        switch ($lead_type_service_id){
                            case 6:
                            case 9:
                            case 11:
                            case 12:
                            case 13:
                            case 21:
                                $url_api .= "&tcpa_statement=$TCPAText&tcpa_opt_in=$tcpa_compliant2";
                                break;
                            case 8:
                                $url_api .= "&tcpa_text=$TCPAText";
                                break;
                            default:
                                $url_api .= "&tcpa_consent_text=$TCPAText";
                        }
                        break;
                    case 400:
                        //Sunpro 400
                        $url_api .= "&landing_page=$OriginalURL2&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&lp_s2=$google_ts&lp_s3=PPS";
                        break;
                    case 425:
                        //Envyus Media Corp. 425
                        $url_api .= "&affid=26725&campid=38612&language=en_US&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form";
                        break;
                    case 497:
                        //Airo Marketing Inc. 497
                        $source_type= "Paid Search";
                        $url_api .= "&Universal_Lead_ID=$LeadId&trusted_form_url=$trusted_form&trusted_form_cert_id=$trusted_form&tcpa_consent=$tcpa_compliant2&tcpa_language=$TCPAText&landing_page=$OriginalURL2&source_type=$source_type";
                        break;
                    case 185:
                        //snk media group 185
                        if ($trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com") {
                            $trusted_form = "";
                        }

                        $url_api .= "&jornaya_id=$LeadId&trusted_form_url=$trusted_form&user_agent=$UserAgent&lp_s2=$google_ts";
                        if ($lead_type_service_id == 24) {
                            $url_api .= "&landing_page_url=$OriginalURL2&tcpa_consent=$tcpa_compliant2&TCPA_Language=$TCPAText";
                        } else {
                            $url_api .= "&consentLang=$TCPAText&sumbission_url=$OriginalURL2";
                        }
                        break;
                    case 540:
                        //pbtp Powered by The People LLC 540
                        $type_data = ($type == 1 ? "Exclusive" : "Shared");
                        $source_id = "400";
                        $source_key = "XP-ADM890";

                        $url_api .= "&leadtoken=$LeadId&type=$type_data&source_id=$source_id&source_key=$source_key&tcpa=$tcpa_compliant2&tcpa_text=$TCPAText&TrustedForm=$trusted_form";
                        break;
                    case 605:
                        //Grow My Firm Online 605
                        $url_api .= "&user_agent=$UserAgent&tcpa_consent=$tcpa_compliant2&tcpa_language=$TCPAText&landing_page=$OriginalURL2";
                        break;
                    case 636:
                        //The Lead Giant 636
                        $url_api .= "&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&tcpa_text=$TCPAText&tcpa_opt_in=$tcpa_compliant2";
                        break;
                    case 276:
                        //ETN AMERICA 276
                        $url_api .= "&landing_page=$OriginalURL2&tcpa_consent=$tcpa_compliant2&tcpa_language=$TCPAText&user_agent=$UserAgent&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&lp_s1=$google_ts";
                        break;
                    case 546:
                        //Remodel Well 546
                        if ( empty($OriginalURL2) || empty($TCPAText) || $tcpa_compliant2 == "No" || empty($LeadId)
                            || $trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com" ) {
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
                        }

                        if ($trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com") {
                            $trusted_form = "";
                        }

                        $url_api .= "&lp_s2=$google_ts&landing_page_url=$OriginalURL2&tcpa_consent_text=$TCPAText&tcpa_consent=$tcpa_compliant2&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form";
                        break;
                    case 939:
                        //939 Turtle Leads LLC
                        if ( empty($OriginalURL2) || empty($LeadId) || $trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com" ) {
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
                        }

                        $url_api.="&user_agent=$UserAgent&landing_page=$OriginalURL2&landing_page_url=$OriginalURL2&tcpa_compliant=$tcpa_compliant4&tcpa_consent=$tcpa_compliant2&tcpa_consent_text=$TCPAText&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form";
                        break;
                    case 959:
                        // 959 pointer leads
                        $url_api.="&trustedform_url=$trusted_form&tcpa_language=$TCPAText&landing_page=$OriginalURL2&jornaya=$LeadId";
                        break;
                    case 974:
                        // 974 OnCore Leads
                        $url_api .= "&tcpa=$tcpa_compliant2&tcpa_text=$TCPAText&landing_page_url=$OriginalURL2&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId";
                        break;
                    case 1025:
                        //MILI Group LLC 1025
                        $type_data = ($type == 1 ? "Exclusive" : "Shared");

                        if ($trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com") {
                            $trusted_form = "";
                        }

                        $trusted_form_cert_id = "";
                        if(!empty($trusted_form)){
                            $trusted_form_cert_arr = explode("/", $trusted_form);
                            $trusted_form_cert_id = (!empty($trusted_form_cert_arr[3]) ? $trusted_form_cert_arr[3] : "");
                        }

                        $pub_id = "AL424";

                        $tcpa_compliant2 = strtoupper($tcpa_compliant2);
                        $tcpa_compliant2 = ($tcpa_compliant2 == "Yes" ? "YES" : "NO");

                        $url_api .= "&universal_leadid=$LeadId&type=$type_data&pub_id=$pub_id&tcpa_language=$TCPAText&TCPA=$tcpa_compliant2&trusted_form=$trusted_form&trusted_form_cert_url=$trusted_form&trusted_form_cert_id=$trusted_form_cert_id&landing_page=$OriginalURL2&user_agent=$UserAgent";
                        break;
                    case 1045:
                        //UptownLeads LLC  1045
                        $url_api .= "&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId&tcpa_consent_language=$TCPAText&landing_page_url=$OriginalURL2";
                        if (strtolower($lead_source_name) == "affiliate") {
                            $url_api .="&lp_s2=$google_ts";
                        }
                        break;
                    case 1240:
                        //Rexdirect 1240
                        $url_api .= "&affkey=112890&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId";
                        break;
                    case 1249:
                        // 1249	Lead2contractors
                        $url_api .= "&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId&tcpa_consent=$tcpa_compliant2&tcpa_language=$TCPAText&user_agent=$UserAgent";
                        break;
                    case 1303:
                        // 1303	Solar Direct Marketing
                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=115&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&credit_score=Good&source_page_url=$OriginalURL2&landing_page=$OriginalURL2&tcpa=$tcpa_compliant2&tcpaDisclosure=$tcpa_compliant2&tcpa_text=$TCPAText&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&user_agent=$UserAgent&lp_s2=$lead_source_text&traffic_source=$lead_source_name";
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
                            case 394:
                                //Advertising Results 394
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $replace_repair = ($project_nature == "Repair" ? "repair" : "install");
                                $project_timeframe = ($start_time == 'Immediately' ? "immediate" : "over_2_weeks");
                                $property_type = "residential";
                                $best_call_time = "Anytime";

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

                                $url_api .= "&number_of_windows=$number_of_windows_data&time_frame=$project_timeframe&owner=$homeowner&service_type=$replace_repair&property_type=$property_type&best_call_time=$best_call_time";
                                break;
                            case 497:
                                //Airo Marketing Inc. 497
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                if ($project_nature != "Repair") {
                                    switch ($number_of_windows) {
                                        case "1":
                                            $Trade = "Windows - Replace 1 Window";
                                            break;
                                        case "2":
                                            $Trade = "Windows - Replace 2 Windows";
                                            break;
                                        case "3-5":
                                            $Trade = "Windows - Replace 3-5 Windows";
                                            break;
                                        case "6-9":
                                            $Trade = "Windows - Replace 6-9 Windows";
                                            break;
                                        default:
                                            $Trade = "Windows - Replace 10%2b Windows";
                                    }
                                } else {
                                    $Trade = "Windows Repair - Service Call";
                                }

                                $url_api .= "&Trade=$Trade&Homeowner=$homeowner";
                                break;
                            case 185:
                                //snk media group 185
                                $ProjectType = ($project_nature != "Repair" ? "4" : "12");
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($number_of_windows) {
                                    case "1":
                                        $number_of_windows_data = 1;
                                        break;
                                    case "2":
                                        $number_of_windows_data = 2;
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = 4;
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = 7;
                                        break;
                                    default:
                                        $number_of_windows_data = 10;
                                }

                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&windows=$number_of_windows_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
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
                            case 636:
                                //The Lead Giant 636
                                $number_of_windows_data = ($number_of_windows == "10+" ? "6-9" : $number_of_windows);
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $replace_repair = ($project_nature == "Repair" ? "Repair" : "Install");
                                $property_type = "Single Family Home";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Unsure";
                                        break;
                                    default:
                                        $project_timeframe = "1-2 Weeks";
                                }

                                $url_api .= "&property_owner=$homeowner&property_type=$property_type&project_type=$replace_repair&project_timeframe=$project_timeframe&number_of_windows=$number_of_windows_data";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_call_time = "Anytime";

                                if($project_nature == "Repair"){
                                    $category = "Windows Repair";
                                } else {
                                    $category = ($number_of_windows == 1 ? "Windows Install - Single" : "Windows Install - Multiple");
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $url_api .= "&category=$category&timeframe=$project_timeframe&homeowner=$homeowner&window_number=$number_of_windows&best_time_to_call=$best_call_time";
                                break;
                            case 22:
                                //Lead Cactus LLC Windows 22
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $replace_repair = ($project_nature == "Repair" ? "repair" : "install");

                                switch ($number_of_windows) {
                                    case "3-5":
                                        $number_of_windows_data = "3_5";
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = "6_9";
                                        break;
                                    case "10+":
                                        $number_of_windows_data = "10_and_more";
                                        break;
                                    default:
                                        $number_of_windows_data = $number_of_windows;
                                }

                                switch ($start_time) {
                                    case 'Immediately':
                                        $project_timeframe = "immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1_6_months";
                                        break;
                                    default:
                                        $project_timeframe = "not_sure";
                                }

                                $url_api .= "&number_of_windows=$number_of_windows_data&time_frame=$project_timeframe&homeowner=$homeowner&service_type=$replace_repair&best_time_to_call=Morning";
                                break;
                            case 605:
                                //Grow My Firm Online 605
                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "Immediate";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "1-3 Months";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                switch ($ownership) {
                                    case 'Yes':
                                        $homeowner = "Owner";
                                        break;
                                    case "No":
                                        $homeowner = "Unauthorized Renter";
                                        break;
                                    default:
                                        $homeowner = "Authorized Renter";
                                }

                                $project_details = "$number_of_windows Windows";
                                $property_type_data = "Home";
                                $service_need = "Windows";

                                $url_api .= "&project_timeframe=$start_time_data&own_rent=$homeowner&property_type=$property_type_data&service_need=$service_need&project_details=$project_details&project_nature=$project_nature&unit_count=$number_of_windows";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($number_of_windows) {
                                    case "1":
                                        $number_of_windows_data = 1;
                                        break;
                                    case "2":
                                        $number_of_windows_data = 2;
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = 4;
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = 7;
                                        break;
                                    default:
                                        $number_of_windows_data = 10;
                                }

                                switch ($project_nature){
                                    case 'Repair':
                                        $proNature = "Need repair services at this time";
                                        $category = "Window Repair";
                                        break;
                                    default:
                                        $proNature = "Interested in replacement windows";
                                        switch ($number_of_windows) {
                                            case "1":
                                                $category = "Windows Install - Single";
                                                break;
                                            default:
                                                $category = "Windows Install - Multiple";
                                        }
                                }

                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $windows_material = "Wood";
                                $project_type = "Windows Project Type";
                                $property_type = "Other";

                                $url_api.="&project_time_frame=$project_timeframe&homeowner=$homeowner&windows_project_type=$proNature&num_windows=$number_of_windows_data&category=$category&best_call_time=$best_call_time&windows_material=$windows_material&project_type=$project_type&property_type=$property_type";
                                break;
                            case 959:
                                // 959 pointer leads
                                switch ($number_of_windows) {
                                    case "1":
                                    case "2":
                                        $number_of_windows_data = "1-2";
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = "3-5";
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = "6-9";
                                        break;
                                    default:
                                        $number_of_windows_data = "10+";
                                }

                                $url_api.="&windows_service=$project_nature&windows_number=$number_of_windows_data";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $replace_repair = ($project_nature == "Repair" ? "Repair" : "Install/Replace");
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediate";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Timing is Flexible";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 Month";
                                }
                                switch ($number_of_windows) {
                                    case "1":
                                        $number_of_windows_data = "1";
                                        break;
                                    case "2":
                                        $number_of_windows_data = "2";
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = "3-5";
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = "6-9";
                                        break;
                                    default:
                                        $number_of_windows_data = "10%2B";
                                }

                                $url_api.="&time_frame=$project_timeframe&home_owner=$homeowner&number_of_windows=$number_of_windows_data&window_project_type=$replace_repair&window_material=Not Sure";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                if ($project_nature == "Repair") {
                                    $type_of_work = "Window Repair";
                                } else {
                                    $type_of_work = ($number_of_windows == "1" ? "Window Install Single" : "Windows Install Multiple");
                                }
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                switch ($number_of_windows) {
                                    case "1":
                                    case "2":
                                        $service = ($project_nature == "Repair" ? "Windows Repair 2 Wood Windows" : "Windows Install 2 Wood windows");
                                        break;
                                    case "3-5":
                                        $service = ($project_nature == "Repair" ? "Windows Repair 3-5 Wood Windows" : "Windows Install 3-5 Wood Windows");
                                        break;
                                    default:
                                        $service = ($project_nature == "Repair" ? "Windows Repair 6-9 Wood Windows" : "Windows Install 6-9 Wood windows");
                                }

                                $url_api .= "&service=$service";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                if ($project_nature == "Repair") {
                                    $project = "A01-Window Glass - Repair";
                                }
                                else {
                                    switch ($number_of_windows) {
                                        case "1":
                                            $project ="A01-Window Install - Single";
                                            break;
                                        case "2":
                                        case "3-5":
                                            $project = "A01-Windows Install - 2 to 5";
                                            break;
                                        default:
                                            $project = "A01-Windows Install - 6 or more";
                                    }
                                }
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");

                                $url_api .= "&project=$project&property_type=Residential&homeowner=$homeowner&contractor_ID=A01";
                                break;
                            case 303:
                                //TradeMarc Global LLC 303
                                $residence_type = ($ownership == "Yes" ? "Own" : "Rent");
                                $property_type = "Residential";
                                $task_id = ($project_nature == "Install" ? "New" : $project_nature);

                                $url_api .= "&property_type=$property_type&time_frame=$start_time&residence_type=$residence_type&task_id=$task_id";
                                break;
                            case 1240:
                                //Rexdirect 1240
                                switch ($number_of_windows) {
                                    case "1":
                                    case "2":
                                        $number_of_windows_data = "1-2";
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = "3-5";
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = "6-9";
                                        break;
                                    default:
                                        $number_of_windows_data = "10+";
                                }

                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "1-6 month";
                                        break;
                                    default:
                                        $start_time_data = "NA";
                                }

                                switch ($project_nature) {
                                    case 'Install':
                                        $project_nature_data = "install";
                                        break;
                                    case "Replace":
                                        $project_nature_data = "replace";
                                        break;
                                    default:
                                        $project_nature_data = "Repair";
                                }

                                $url_api .= "&project_type=$project_nature_data&purchase_timeframe=$start_time_data&number_of_windows=$number_of_windows_data&own_home=$ownership&tcpa_consent_language=$TCPAText";
                                break;
                            case 1249:
                                // 1249	Lead2contractors
                                $homeType = "Single Family";

                                switch ($project_nature) {
                                    case 'Install':
                                        $project_nature_data = "Install";
                                        break;
                                    case "Replace":
                                        $project_nature_data = "Replace";
                                        break;
                                    default:
                                        $project_nature_data = "Repair";
                                }
                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "Immediately";
                                        $timeFrame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "Within 6 months";
                                        $timeFrame = "3-6 Months";
                                        break;
                                    default:
                                        $start_time_data = "Not Sure";
                                        $timeFrame = "Flexible";
                                }
                                switch ($ownership) {
                                    case 'Yes':
                                        $homeowner = "Yes";
                                        break;
                                    case "No":
                                        $homeowner = "No";
                                        break;
                                    default:
                                        $homeowner = "No, But Authorized to Make Changes";
                                }
                                switch ($number_of_windows) {
                                    case "1":
                                        $number_of_windows_data = "1";
                                        break;
                                    case "2":
                                        $number_of_windows_data = "2";
                                        break;
                                    case "3-5":
                                        $number_of_windows_data = "3-5";
                                        break;
                                    case "6-9":
                                        $number_of_windows_data = "6-9";
                                        break;
                                    default:
                                        $number_of_windows_data = "10+";
                                }
                                $url_api .= "&timeframe=$timeFrame&homeowner=$homeowner&type_of_home=$homeType&project_nature=$project_nature_data&number_of_windows=$number_of_windows_data&landing_page_url=$OriginalURL&priority=$start_time_data";

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
                            case 303:
                                //TradeMarc Global LLC 303
                                $residence_type = ($property_type == 'Own' ? "Yes" : "No");
                                $property_type = ($property_type == 'Business' ? "No" : "Yes");
                                $start_time = "Not Sure";

                                $url_api .= "&shade=$roof_shade&electric_bill_monthly=$monthly_electric_bill&electric_provider=$utility_provider&time_frame=$start_time&residential=$property_type&home_owner=$residence_type";
                                if (config('app.env', 'local') == "local") {
                                    $url_api .= "&test_lead=Yes";
                                }
                                break;
                            case 312:
                                //Purified Leads 312
                                $CreditRating = "Good";
                                $homeowner = ($property_type == 'Rented' ? "Rent" : "Own");
                                $SolarElectric = ($power_solution == "Solar Water Heating for my Home" ? "No" : "Yes");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_bill = '$0-$50';
                                        break;
                                    case '$51 - $100':
                                        $average_bill = '$51-$100';
                                        break;
                                    case '$101 - $150' || '$151 - $200':
                                        $average_bill = '$101-$200';
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

                                switch ($roof_shade){
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
                                        $roof_shade_data = "Uncertain";
                                }

                                $url_api .= "&electric_bill=$average_bill&credit_rating=$CreditRating&homeowner=$homeowner&electric_provider=$utility_provider&roof_shade=$roof_shade_data&solar_electric=$SolarElectric";
                                break;
                            case 22:
                                //Lead Cactus LLC 22
                                $homeowner = ($property_type == 'Rented' ? "No" : "Yes");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_bill = '$0-$50';
                                        break;
                                    case '$51 - $100':
                                        $average_bill = '$51-$100';
                                        break;
                                    case '$101 - $150' || '$151 - $200':
                                        $average_bill = '$151-200';
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

                                switch ($roof_shade){
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
                                        $roof_shade_data = "Uncertain";
                                }

                                $url_api .= "&average_bill=$average_bill&homeowner=$homeowner&electricUtilityProviderText=$utility_provider&roof_shade=$roof_shade_data";
                                break;
                            case 384:
                                //Encompass Leads LLC 384
                                $credit_score = "Good";
                                $homeowner = ($property_type == 'Rented' ? "No" : "Yes");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                    case '$51 - $100':
                                        $average_bill = '0-99';
                                        break;
                                    case '$101 - $150':
                                    case '$151 - $200':
                                        $average_bill = '100-200';
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
                                        $average_bill = '501+';
                                }

                                switch ($roof_shade){
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

                                $url_api .= "&utility_electric_monthly_amount=$average_bill&homeowner=$homeowner&electric_provider=$utility_provider&roof_shade=$roof_shade_data&credit_score=$credit_score";
                                break;
                            case 394:
                                //Advertising Results 394
                                $credit_score = "Good";
                                $best_call_time = "Anytime";
                                $property_type = "Single Family";
                                $service_type = "Install New Panels";
                                $purchase_time_frame = "Within 1 months";
                                $homeowner = ($property_type == 'Rented' ? "No" : "Yes");
                                $SolarElectric = ($power_solution == "Solar Water Heating for my Home" ? "Solar Thermal" : "Solar Electrical");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_bill = '50';
                                        break;
                                    case '$51 - $100':
                                        $average_bill = '100';
                                        break;
                                    case '$101 - $150':
                                        $average_bill = '150';
                                        break;
                                    case '$151 - $200':
                                        $average_bill = '200';
                                        break;
                                    case '$201 - $300':
                                        $average_bill = '300';
                                        break;
                                    case '$301 - $400':
                                        $average_bill = '400';
                                        break;
                                    case '$401 - $500':
                                        $average_bill = '500';
                                        break;
                                    default:
                                        $average_bill = '600';
                                }

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "no shade";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "full shade";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "some shade";
                                        break;
                                    default:
                                        $roof_shade_data = "not sure";
                                }

                                $url_api .= "&best_call_time=$best_call_time&source_id=$lead_source_text&utility_provider=$utility_provider&own_property=$homeowner&roof_shade=$roof_shade_data&solar_service=$SolarElectric&credit_rating=$credit_score&monthly_electric_bill=$average_bill&purchase_time_frame=$purchase_time_frame&property_type=$property_type&service_type=$service_type";
                                break;
                            case 425:
                                //Envyus Media Corp. 425
                                $homeowner = ($property_type == 'Rented' ? "Rent" : "Own");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_bill = '$0-$50';
                                        break;
                                    case '$51 - $100':
                                        $average_bill = '$51-$100';
                                        break;
                                    case '$101 - $150':
                                        $average_bill = '$101-150';
                                        break;
                                    case '$151 - $200':
                                        $average_bill = '$151-200';
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

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "no shade";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "A Lot Of Shade";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "A Little Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Uncertain";
                                }

                                $url_api .= "&utility_provider=$utility_provider&property_ownership=$homeowner&roof_shade=$roof_shade_data&electric_bill=$average_bill";
                                break;
                            case 497:
                                //Airo Marketing Inc. 497
                                $Trade = "Solar - Installation";

                                switch ($monthly_electric_bill){
                                    case '$0 - $50' || '$51 - $100' || '$101 - $150':
                                        $energy_bill = '$0 - $150';
                                        break;
                                    case '$151 - $200':
                                        $energy_bill = '$150 - $200';
                                        break;
                                    case '$201 - $300':
                                        $energy_bill = '$200 - $300';
                                        break;
                                    case '$301 - $400' || '$401 - $500':
                                        $energy_bill = '$300 - $500';
                                        break;
                                    default:
                                        $energy_bill = '$500+';
                                }

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "No Shade";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Very Shaded";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Some Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Not Sure";
                                }

                                switch ($property_type){
                                    case 'Rented':
                                        $homeowner = 'No';
                                        $residential = "Yes";
                                        break;
                                    case 'Business':
                                        $homeowner = '';
                                        $residential = "No";
                                        break;
                                    default:
                                        $homeowner = 'Yes';
                                        $residential = "Yes";
                                }

                                $url_api .= "&energy_bill=$energy_bill&Roof_shade=$roof_shade_data&Trade=$Trade&Residential=$residential&Homeowner=$homeowner";
                                break;
                            case 400:
                                //Sunpro 400
                                $url_api .= "&electric_bill_amount=$monthly_electric_bill&electric_company=$utility_provider&roof_shade=$roof_shade";
                                break;
                            case 540:
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
                            case 605:
                                //Grow My Firm Online 605
                                $credit = "Good";

                                switch ($property_type){
                                    case "Owned":
                                        $home_owner = "Yes";
                                        $residential_commercia = "Residential";
                                        break;
                                    case "Rented":
                                        $home_owner = "No";
                                        $residential_commercia = "Residential";
                                        break;
                                    default:
                                        $home_owner = "No";
                                        $residential_commercia = "Commercial";
                                }

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_bill = '50';
                                        break;
                                    case '$51 - $100':
                                        $average_bill = '100';
                                        break;
                                    case '$101 - $150':
                                        $average_bill = '150';
                                        break;
                                    case '$151 - $200':
                                        $average_bill = '200';
                                        break;
                                    case '$201 - $300':
                                        $average_bill = '300';
                                        break;
                                    case '$301 - $400':
                                        $average_bill = '400';
                                        break;
                                    case '$401 - $500':
                                        $average_bill = '500';
                                        break;
                                    default:
                                        $average_bill = '600';
                                }

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "Full Sun";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "No Sun";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Partial Sun";
                                        break;
                                    default:
                                        $roof_shade_data = "Unknown";
                                }

                                $url_api .= "&homeowner=$home_owner&PropertyUsage=$residential_commercia&utilityProvider=$utility_provider&utilityBill=$average_bill&sunLevel=$roof_shade_data&credit=$credit";
                                break;
                            case 546:
                                //Remodel Well 546
                                $credit = "Good";
                                $home_owner = ($property_type == "Owned" ? "Yes" : "No");

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
                                        $average_bill = '+$401';
                                }

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "No Shade";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Full Shade";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Partial Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Not Sure";
                                }

                                $url_api .= "&homeowner=$home_owner&electric_provider=$utility_provider&roof_shade=$roof_shade_data&credit_score=$credit&electric_bill=$average_bill";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $powerSol = ($power_solution == "Solar Electricity for my Home") ? "Yes" : "No";

                                switch ($property_type){
                                    case "Owned":
                                        $homeowner = "Yes";
                                        break;
                                    case "Rented":
                                        $homeowner = "No";
                                        break;
                                    default:
                                        $homeowner = "Yes";
                                }

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
                                    case "Partial Sun":
                                        $roof_shade_data = "A Little Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Uncertain";
                                }

                                $url_api .= "&home_owner=$homeowner&roof_shade=$roof_shade_data&credit_rating=Good&solar_electric=$powerSol&electric_bill=$average_bill&electric_supplier=$utility_provider&property_type=Single Family";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                $home_owner = ($property_type == "Owned" ? "Yes" : "No");
                                $best_call_time = "Any Time";
                                $project_time_frame = "Immediately";
                                $property_type_data = "Other";
                                $category = "Solar Panels";
                                $estimate_timeline = "Timing Flexible";

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

                                $url_api .= "&credit=Good&roof_shade=$roof_shade_data&monthly_electric_bill=$average_bill&current_utility_provider=$utility_provider&estimate_timeline=$estimate_timeline&homeowner=$home_owner&project_time_frame=$project_time_frame&best_call_time=$best_call_time&property_type=$property_type_data&category=$category";
                                break;
                            case 959:
                                // 959 pointer leads
                                $credit_scorePnt = "Good";

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
                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "No Shade";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Full Shade";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Partial Shade";
                                        break;
                                    default:
                                        $roof_shade_data = "Not Sure";
                                }
                                $url_api.="&credit_score=$credit_scorePnt&utility_provider=$utility_provider&electric_bill=$average_bill&roof_shade=$roof_shade_data";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                    case '$51 - $100':
                                        $average_bill = "Less than $100";
                                        break;
                                    case '$101 - $150':
                                    case '$151 - $200':
                                        $average_bill = "From $100 to $200";
                                        break;
                                    case '$201 - $300':
                                        $average_bill = "From $200 to $300";
                                        break;
                                    default:
                                        $average_bill = "More than $300";
                                }

                                $home_owner = ($property_type == "Owned" ? "Yes" : "No");

                                $url_api.="&house_size=2-3 Bedroom&monthly_bill=$average_bill&time_frame=Timing is Flexible&home_owner=$home_owner";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                $credit_rating = "good";
                                $electric_provider = "1";
                                $property_type = "single";
                                $service = "install";

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_electric_bill = '$0-50';
                                        break;
                                    case '$101 - $150':
                                        $average_electric_bill = '$101-150';
                                        break;
                                    case '$151 - $200':
                                        $average_electric_bill = '$151-200';
                                        break;
                                    case '$201 - $300':
                                        $average_electric_bill = '$201-300';
                                        break;
                                    case '$301 - $400':
                                        $average_electric_bill = '$301-400';
                                        break;
                                    case '$401 - $500':
                                        $average_electric_bill = '$401-500';
                                        break;
                                    default:
                                        $average_electric_bill = '$501-600';
                                }

                                switch ($roof_shade){
                                    case "Mostly Shaded":
                                        $roof_shade_data = "full";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "partial";
                                        break;
                                    default:
                                        $roof_shade_data = "none";
                                }

                                $url_api .= "&service=$service&electric_provider=$electric_provider&average_electric_bill=$average_electric_bill&shade=$roof_shade_data&property_type=$property_type&credit_rating=$credit_rating";
                                break;
                            case 1249:
                                // 1249	Lead2contractors
                                $credit = "Good";
                                $houseSize = "2-3 Bedroom";
                                $home_owner = ($property_type == "Owned" ? "Yes" : "No");
                                $propertyTType = "Single Family";
                                $timeFrame = "Flexible";

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "Full Sun";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Mostly Shaded";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Partial Sun";
                                        break;
                                    default:
                                        $roof_shade_data = "Not Sure";
                                }

                                switch ($power_solution){
                                    case "Solar Electricity for my Home":
                                        $power_solution_data = "Residential Electricity";
                                        break;
                                    case "Solar Water Heating for my Home":
                                        $power_solution_data = "Residential Water Heating";
                                        break;
                                    case "Solar Electricity & Water Heating for my Home":
                                        $power_solution_data = "Residential Electricity and Water Heating";
                                        break;
                                    default:
                                        $power_solution_data = "Business";
                                }

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                        $average_electric_bill = '$0 - $50';
                                        break;
                                    case '$51 - $100':
                                        $average_electric_bill = '$51 - $100';
                                        break;
                                    case '$101 - $150':
                                        $average_electric_bill = '$101 - $150';
                                        break;
                                    case '$151 - $200':
                                        $average_electric_bill = '$151 - $200';
                                        break;
                                    case '$201 - $300':
                                        $average_electric_bill = '$201 - $300';
                                        break;
                                    case '$301 - $400':
                                        $average_electric_bill = '$301 - $400';
                                        break;
                                    case '$401 - $500':
                                        $average_electric_bill = '$401 - $500';
                                        break;
                                    default:
                                        $average_electric_bill = '$500+';
                                }

                                $url_api .= "&roof_shade=$roof_shade_data&monthly_electric_bill=$average_electric_bill&owner=$home_owner&utility_provider=$utility_provider&credit=$credit&landing_page_url=$OriginalURL&solution_type=$power_solution_data&property_type=$propertyTType&house_size=$houseSize&timeframe=$timeFrame";

                                break;
                            case 1303:
                                // 1303	Solar Direct Marketing
                                $home_owner = ($property_type == "Owned" ? "Yes" : "No");

                                switch ($monthly_electric_bill){
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

                                switch ($roof_shade){
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

                                switch ($power_solution){
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

                                $url_api .= "&homeowner=$home_owner&property_type=Single Family&roof_shade=$roof_shade_data&utility_electric_monthly_amount=$average_bill&utility_electric_company_name=$utility_provider&solar_electric=$power_solution_data";
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
                            case 185:
                                //snk media group 185
                                $Trade = "08 - Home Security - New Install";
                                $ProjectType = "8";

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                switch ($property_type){
                                    case "Owned":
                                        $homeowner = 1;
                                        $residential = 1;
                                        $home_security_use_type = "Residential";
                                        break;
                                    case "Rented":
                                        $homeowner = 0;
                                        $residential = 1;
                                        $home_security_use_type = "Residential";
                                        break;
                                    default:
                                        $homeowner = 0;
                                        $residential = 0;
                                        $home_security_use_type = "Commercial";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&home_security_use_type=$home_security_use_type&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
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
                            case 636:
                                //The Lead Giant 636
                                $homeowner = ($property_type == 'Owned' ? "Yes" : "No");
                                $url_api .= "&homeowner=$homeowner";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($property_type == "Owned" ? "Yes" : "No");
                                $category = ($property_type == "Business" ? "Home Security - Commercial" : "Home Security - Residential");
                                $best_call_time = "Anytime";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $url_api .= "&category=$category&timeframe=$project_timeframe&homeowner=$homeowner&best_time_to_call=$best_call_time";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = "Alarm Security System Install";
                                $homeowner = ($property_type == "Owned" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 959:
                                // 959 pointer leads
                                $homesecurity_service = "Install";
                                $url_api .= "&homesecurity_service=$homesecurity_service";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                switch ($property_type){
                                    case "Owned":
                                        $homeowner = "Own";
                                        $residential_commercia = "Residential";
                                        $project = "L10-Alarm/security system Install";
                                        break;
                                    case "Rented":
                                        $homeowner = "Rent";
                                        $residential_commercia = "Residential";
                                        $project = "L10-Alarm/security system Install";
                                        break;
                                    default:
                                        $homeowner = "Rent";
                                        $residential_commercia = "Commercial";
                                        $project = "L10-Business Alarm or Security System - Install";
                                }

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=L10";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $security_property_type = "Residential";
                                switch ($property_type) {
                                    case "Rented":
                                        $homeowner = "False";
                                        $security_property_type = "Residential";
                                        $category = "Home Security System - Install";
                                        break;
                                    case "Business":
                                        $homeowner = "True";
                                        $security_property_type = "Commercial";
                                        $category = "Business Security System - Install";
                                        break;
                                    default:
                                        $homeowner = "True";
                                        $security_property_type = "Commercial";
                                        $category = "Business Security System - Install";
                                }

                                $property_type = "Other";
                                $best_call_time = "Any Time";
                                $project_time_frame = "Immediately";
                                $property_type_data = "Other";
                                $estimate_timeline = "Timing Flexible";
                                $home_security_building_type = "Other";

                                $url_api .= "&home_security_building_type=$home_security_building_type&home_security_usage=$security_property_type&homeowner=$homeowner&project_time_frame=$project_time_frame&best_call_time=$best_call_time&property_type=$property_type_data&category=$category";
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
                            case 185:
                                //snk media group 185
                                $Trade = "22 - Flooring";
                                $ProjectType = "22";
                                $residential = 1;
                                $homeowner = ($ownership == "Yes" ? "1" : "0");

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
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
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Morning";
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $category = ($project_nature == "Repair Existing Flooring" ? "Flooring Repair - Vinyl/Linoleum" : "Flooring Install - Vinyl/Linoleum");
                                        break;
                                    case "Tile Flooring":
                                        $category = ($project_nature == "Repair Existing Flooring" ? "Flooring Repair - Tile" : "Flooring Install - Tile");
                                        break;
                                    case "Hardwood Flooring":
                                        $category = ($project_nature == "Repair Existing Flooring" ? "Flooring Repair - Hardwood" : "Flooring Install - Hardwood");
                                        break;
                                    case "Laminate Flooring":
                                        $category = ($project_nature == "Repair Existing Flooring" ? "Flooring Repair - Laminate" : "Flooring Install - Laminate");
                                        break;
                                    default:
                                        $category = ($project_nature == "Repair Existing Flooring" ? "Flooring Repair - Carpet" : "Flooring Install - Carpet");
                                }

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");
                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $type_of_work = ($project_nature == "Repair Existing Flooring" ? "Vinyl Linoleum Floor Repair" : "Vinyl Linoleum Floor Install");
                                        break;
                                    case "Laminate Flooring":
                                        $type_of_work = ($project_nature == "Repair Existing Flooring" ? "Laminate Flooring Repair" : "Laminate Flooring Install");
                                        break;
                                    default:
                                        $type_of_work = "Flooring";
                                }

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 959:
                                // 959 Pointer Leads
                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $type_of_work = "Vinyl/Linoleum Floor";
                                        break;
                                    case "Hardwood Flooring":
                                        $type_of_work = "Hardwood Floor";
                                        break;
                                    case "Laminate Flooring":
                                        $type_of_work = "Laminate Flooring";
                                        break;
                                    default:
                                        $type_of_work = "Wood Floor";
                                }

                                switch ($project_nature){
                                    case "Install New Flooring":
                                        $project_type = "Install";
                                        break;
                                    case "Refinish Existing Flooring":
                                        $project_type = "Replace";
                                        break;
                                    default:
                                        $project_type = "Repair";
                                }

                                $url_api .= "&flooring_type=$type_of_work&flooring_service=$project_type";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                switch ($Type_OfFlooring) {
                                    case "Vinyl Linoleum Flooring":
                                        switch ($project_nature) {
                                            case "Install New Flooring":
                                            case "Refinish Existing Flooring":
                                                $service = 'Flooring Vinyl Linoleum Install Home';
                                                break;
                                            default:
                                                $service = 'Flooring Vinyl Linoleum Repair Home';
                                        }
                                        break;
                                    case  "Tile Flooring":
                                        switch ($project_nature) {
                                            case "Install New Flooring":
                                            case "Refinish Existing Flooring":
                                                $service = 'Flooring Tile Install';
                                                break;
                                            default:
                                                $service = 'Flooring Tile Repair';
                                        }
                                        break;
                                    case "Hardwood Flooring":
                                        switch ($project_nature) {
                                            case "Install New Flooring":
                                                $service = 'Flooring Hardwood Install Materials Purchased Home';
                                                break;
                                            case "Refinish Existing Flooring":
                                                $service = 'Flooring Hardwood Refinishing Materials Purchased Home';
                                                break;
                                            default:
                                                $service = 'Flooring Hardwood Repair Materials Purchased Home';
                                        }
                                        break;
                                    case "Laminate Flooring":
                                        $service = 'Flooring Laminate Materials Purchased Home';
                                        break;
                                    case "Carpet":
                                    default:
                                        switch ($project_nature) {
                                            case "Install New Flooring":
                                            case "Refinish Existing Flooring":
                                                $service = 'Flooring Carpet Install Materials Purchased';
                                                break;
                                            default:
                                                $service = 'Flooring Carpet Repair Materials Purchased';
                                        }
                                }

                                $url_api .= "&service=$service";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $flooring_type = "Vinyl";
                                        break;
                                    case "Hardwood Flooring":
                                        $flooring_type = "Hardwood";
                                        break;
                                    case "Tile Flooring":
                                        $flooring_type = "Tile";
                                        break;
                                    case "Carpet":
                                    default:
                                        $flooring_type = "Carpet";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $category = ($project_nature == "Repair Existing Flooring" ? "Laminate Flooring - Repair" : "Laminate Flooring Install");
                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";

                                $url_api .= "&flooring_type=$flooring_type&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time";
                                break;
                        }
                        break;
                    case 5:
                        //WALK-IN TUBS
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        switch ($user_id){
                            case 497:
                                //Airo Marketing Inc. 497
                                $Trade = "Bathroom - Walk-in Tub";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .= "&Trade=$Trade&Homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = "Bathtub/Shower - Install/Replace";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Bathtub/Shower";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$homeowner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 22:
                                //Lead Cactus LLC WalkInTubs 22
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $bathType = "walk_in_tub";
                                $best_time = "Morning";

                                switch ($start_time) {
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-6 months";
                                        break;
                                    default:
                                        $project_timeframe = "Don't know";
                                }

                                $url_api .= "&homeowner=$homeowner&start_date=$project_timeframe&project_type=$bathType&best_time=$best_time";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");
                                $Project = "Walking Bath";

                                $url_api .= "&Project=$Project&home_owner=$homeowner";
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
                            case 394:
                                //Advertising Results 394
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
                                break;
                            case 497:
                                //Airo Marketing Inc. 497
                                if ($property_type == 'Residential') {
                                    $residential = "Yes";
                                    if($project_nature != "Repair existing roof") {
                                        switch ($roof_type) {
                                            case "Asphalt Roofing":
                                                $Trade = 'Roofing - Asphalt Install or Replace';
                                                break;
                                            case "Tile Roofing":
                                                $Trade = 'Roofing - Clay Tile Install or Replace';
                                                break;
                                            case "Metal Roofing":
                                                $Trade = 'Roofing - Metal Install or Replace';
                                                break;
                                            default:
                                                $Trade = 'Roofing - Wood Shingles Install or Replace';
                                        }
                                    } else {
                                        $Trade = 'Roofing - Repair';
                                    }
                                } else {
                                    $residential = "No";
                                    $Trade = ($project_nature != "Repair existing roof" ? 'Roofing - Commercial Install or Replace' : 'Roofing - Commercial Repair');
                                }

                                $url_api .= "&Trade=$Trade&Residential=$residential";
                                break;
                            case 185:
                                //snk media group 185
                                $ProjectType = ($project_nature != "Repair existing roof" ? 14 : 6);
                                $homeowner = ($property_type == "Residential" ? 1 : 0);
                                $residential = ($property_type == "Residential" ? 1 : 0);

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_type_data = '1';
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_type_data = '2';
                                        break;
                                    case  "Metal Roofing":
                                        $roof_type_data = '3';
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_type_data = '6';
                                        break;
                                    default:
                                        $roof_type_data = '5';
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner&RoofingType=$roof_type_data";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                if( $property_type == "Residential" ){
                                    $home_owner = "Yes";
                                    $residential_commercia = "residential";
                                    switch ($roof_type){
                                        case "Wood Shake/Composite Roofing":
                                            $type_of_work = "Roofing Wood Shingle";
                                            break;
                                        case  "Metal Roofing":
                                            $type_of_work = "Roofing Metal";
                                            break;
                                        case "Natural Slate Roofing":
                                            $type_of_work = "Roofing Slate";
                                            break;
                                        case "Tile Roofing":
                                            $type_of_work = "Roofing Tile";
                                            break;
                                        default:
                                            $type_of_work = 'Roofing';
                                    }
                                } else {
                                    $home_owner = "No";
                                    $residential_commercia = "commercial";
                                    $type_of_work = "Commercial Roofing";
                                }

                                $category = "Roofing";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 636:
                                //The Lead Giant 636
                                $homeowner = ($property_type == "Residential" ? "Yes" : "No");
                                $property_type = "Single Family Home";

                                switch ($project_nature){
                                    case "Install roof on new construction":
                                        $replace_repair = "New";
                                        break;
                                    case "Completely replace roof":
                                        $replace_repair = "Replace";
                                        break;
                                    default:
                                        $replace_repair = "Repair";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Unsure";
                                        break;
                                    default:
                                        $project_timeframe = "1-2 Weeks";
                                }

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_type_text = "Asphalt Shingles";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_type_text = "Wood Shake";
                                        break;
                                    case "Metal Roofing":
                                        $roof_type_text = "Metal";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_type_text = "Slate";
                                        break;
                                    case "Tile Roofing":
                                        $roof_type_text = "Cement Tile";
                                        break;
                                    default:
                                        $roof_type_text = "Other or Unknown";
                                }

                                $url_api .= "&property_owner=$homeowner&property_type=$property_type&project_type=$replace_repair&project_timeframe=$project_timeframe&roof_type=$roof_type_text";
                                break;
                            case 303:
                                //TradeMarc Global LLC 303
                                switch ($property_type){
                                    case "Residential":
                                        $residence_type = "Own";
                                        $property_type = "Residential";
                                        break;
                                    default:
                                        $residence_type = "Rent";
                                        $property_type = "Business";
                                }

                                switch ($project_nature){
                                    case "Completely replace roof":
                                        $task_id = "Replace";
                                        break;
                                    case "Repair existing roof":
                                        $task_id = "Repair";
                                        break;
                                    default:
                                        $task_id = "New";
                                }

                                $url_api .= "&property_type=$property_type&time_frame=$start_time&residence_type=$residence_type&task_id=$task_id";
                                break;
                            case 546:
                                //Remodel Well 546
                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $category = ($project_nature == "Repair existing roof" ? "Roof Repair - Asphalt Shingle" : "Roof Install - Asphalt Shingle");
                                        $roof_material = "Asphalt Shingle";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $category = ($project_nature == "Repair existing roof" ? "Roof Repair - Wood Shake/Comp." : "Roof Install - Wood Shake/Comp.");
                                        $roof_material = "Wood Shake/Comp.";
                                        break;
                                    case "Metal Roofing":
                                        $category = ($project_nature == "Repair existing roof" ? "Roof Repair - Metal" : "Roof Install - Metal");
                                        $roof_material = "Metal";
                                        break;
                                    case "Natural Slate Roofing":
                                        $category = ($project_nature == "Repair existing roof" ? "Roof Repair - Natural Slate" : "Roof Install - Natural Slate");
                                        $roof_material = "Natural Slate";
                                        break;
                                    case "Tile Roofing":
                                    default:
                                        $category = ($project_nature == "Repair existing roof" ? "Roof Repair - Tile" : "Roof Install - Tile");
                                        $roof_material = "Tile";
                                }

                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "1-3 months";
                                        break;
                                    case "Not Sure":
                                    default:
                                        $time_frame = "Within 1 months";
                                }

                                $best_time_to_call = "Anytime";
                                $homeowner = "Yes";
                                $roof_job = ($project_nature == "Repair existing roof" ? "Roof Repair" : "Roof Install");

                                $url_api .= "&category=$category&best_time_to_call=$best_time_to_call&timeframe=$time_frame&roof_material=$roof_material&roof_job=$roof_job&homeowner=$homeowner";
                                break;
                            case 22:
                                //Lead Cactus LLC 22
                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_material = "ROOFING_TAR_TORCHDOWN";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_material = "ROOFING_CEDAR_SHAKE";
                                        break;
                                    case "Metal Roofing":
                                        $roof_material = "ROOFING_METAL";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_material = "ROOFING_NATURAL_SLATE";
                                        break;
                                    case "Tile Roofing":
                                    default:
                                        $roof_material = "ROOFING_TILE";
                                }

                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "1-6 months";
                                        break;
                                    case "Not Sure":
                                    default:
                                        $time_frame = "Don't know";
                                }

                                $homeowner = "Yes";
                                $best_time = "Morning";

                                $url_api .= "&homeowner=$homeowner&start_date=$time_frame&best_time=$best_time&roof_type=$roof_material&project_type=$project_nature&property_type=$property_type";
                                break;
                            case 605:
                                //Grow My Firm Online 605
                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "Immediate";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "1-3 Months";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                switch ($project_nature) {
                                    case 'Install roof on new construction':
                                        $project_nature_data = "Install";
                                        break;
                                    case "Completely replace roof":
                                        $project_nature_data = "Replace";
                                        break;
                                    default:
                                        $project_nature_data = "Repair";
                                }

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_material = "Asphalt Shingle";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_material = "Cedar Shake";
                                        break;
                                    case "Metal Roofing":
                                        $roof_material = "Metal";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_material = "Natural State";
                                        break;
                                    case "Tile Roofing":
                                    default:
                                        $roof_material = "Tile";
                                }

                                $property_type_data = ($property_type == "Commercial" ? "Commercial" : "Home");
                                $homeowner = "Owner";
                                $service_need = "Roofing";

                                $url_api .= "&project_timeframe=$start_time_data&own_rent=$homeowner&property_type=$property_type_data&service_need=$service_need&project_details=$roof_material&project_nature=$project_nature_data&roof_material=$roof_material";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($project_nature) {
                                    case 'Install roof on new construction':
                                        $project_nature_data = "New roof for new home";
                                        break;
                                    case "Completely replace roof":
                                        $project_nature_data = "New roof for an existing home";
                                        break;
                                    default:
                                        $project_nature_data = "Repair";
                                }

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_material = "Asphalt shingle";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_material = "Cedar shake";
                                        break;
                                    case "Metal Roofing":
                                        $roof_material = "Metal";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_material = "Natural state";
                                        break;
                                    case "Tile Roofing":
                                        $roof_material = "Tile";
                                        break;
                                    default:
                                        $roof_material = "Tar";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($property_type) {
                                    case "Residential":
                                        switch ($roof_type){
                                            case "Asphalt Roofing":
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install - Asphalt Shingle";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Asphalt Shingle";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair - Asphalt Shingle";
                                                }
                                                break;
                                            case "Wood Shake/Composite Roofing":
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install - Wood Shake/Comp.";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Wood Shake";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair - Wood Shake/Comp.";
                                                }
                                                break;
                                            case "Metal Roofing":
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install - Metal";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Metal";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair - Metal";
                                                }
                                                break;
                                            case "Natural Slate Roofing":
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install - Natural Slate";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Natural Slate";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair - Natural Slate";
                                                }
                                                break;
                                            case "Tile Roofing":
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install - Tile";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Tile";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair - Tile";
                                                }
                                                break;
                                            default:
                                                switch ($project_nature) {
                                                    case 'Install roof on new construction':
                                                        $category = "Roof Install -Flat / SinglePly";
                                                        break;
                                                    case "Completely replace roof":
                                                        $category = "Roof Replace - Flat / SinglePly";
                                                        break;
                                                    default:
                                                        $category = "Roof Repair-Flat / SinglePly";
                                                }
                                        }
                                        break;
                                    default:
                                        $category = "Commercial Roofing";
                                }

                                $home_owner = ($property_type == 'Residential' ? "True" : "False");
                                $best_call_time = "Any Time";
                                $project_type = "Roofing Project Type";
                                $property_type = "Other";

                                $url_api .= "&roofing_project_type=$project_nature_data&roofing_type=$roof_material&category=$category&homeowner=$home_owner&project_time_frame=$project_timeframe&best_call_time=$best_call_time&project_type=$project_type&property_type=$property_type";
                                break;
                            case 959:
                                // 959 pointer leads
                                switch ($project_nature){
                                    case "Install roof on new construction":
                                    case "Completely replace roof":
                                        $replace_repair = "Replace";
                                        break;
                                    default:
                                        $replace_repair = "Repair";
                                }
                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roof_material = "Asphalt";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roof_material = "Wood";
                                        break;
                                    case "Metal Roofing":
                                        $roof_material = "Metal";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roof_material = "Slate";
                                        break;
                                    case "Tile Roofing":
                                        $roof_material = "Tile/Clay";
                                        break;
                                    default:
                                        $roof_material = "Not Sure";
                                }
                                $url_api.="&roof_service=$replace_repair&roof_material=$roof_material";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $task_id = '4014';
                                                break;
                                            default:
                                                $task_id = '4003';
                                        }
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $task_id = '4004';
                                                break;
                                            default:
                                                $task_id = '4008';
                                        }
                                        break;
                                    case  "Metal Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $task_id = '4013';
                                                break;
                                            default:
                                                $task_id = '4005';
                                        }
                                        break;
                                    case "Natural Slate Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $task_id = '4012';
                                                break;
                                            default:
                                                $task_id = '4006';
                                        }
                                        break;
                                    case "Tile Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $task_id = '4011';
                                                break;
                                            default:
                                                $task_id = '4007';
                                        }
                                        break;
                                    default:
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                                $task_id = '4017';
                                                break;
                                            case "Completely replace roof":
                                                $task_id = '4009';
                                                break;
                                            default:
                                                $task_id = '4018';
                                        }
                                }
                                switch ($start_time) {
                                    case 'Immediately':
                                        $start_time_data = "Immediate";
                                        break;
                                    case "Within 6 months":
                                        $start_time_data = "More Than 1 Month";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }
                                $property_typeRoofing = ($property_type == 'Residential' ? "Yes" : "No");

                                $url_api.="&service_id=$task_id&time_frame=$start_time_data&home_owner=$property_typeRoofing";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $homeowner = "YES";
                                if($property_type == "Commercial"){
                                    $type_of_work = "Commercial Roofing";
                                }
                                else {
                                    switch ($roof_type){
                                        case "Wood Shake/Composite Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Composition Shingle Repair" : "Roofing Composition Shingle Install");
                                            break;
                                        case "Metal Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Metal Repair" : "Roofing Metal Install");
                                            break;
                                        case "Natural Slate Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Slate Repair" : "Roofing Slate Install");
                                            break;
                                        case "Tile Roofing":
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roofing Tile Repair" : "Roofing Tile Install");
                                            break;
                                        default:
                                            $type_of_work = ($project_nature == "Repair existing roof" ? "Roof Repair" : "New Roof");
                                    }
                                }

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                switch ($roof_type) {
                                    case "Asphalt Roofing":
                                        switch ($project_nature) {
                                            case "Install roof on new construction":
                                                $service = 'Roofing Install on New Construction Asphalt';
                                                break;
                                            case "Completely replace roof":
                                                $service = 'Roofing Replacement Asphalt';
                                                break;
                                            default:
                                                $service = 'Roofing Repair Asphalt';
                                        }
                                        break;
                                    case  "Metal Roofing":
                                        switch ($project_nature) {
                                            case "Install roof on new construction":
                                                $service = 'Roofing Install on New Construction Metal';
                                                break;
                                            case "Completely replace roof":
                                                $service = 'Roofing Replacement Metal';
                                                break;
                                            default:
                                                $service = 'Roofing Repair Metal';
                                        }
                                        break;
                                    case "Natural Slate Roofing":
                                        switch ($project_nature) {
                                            case "Install roof on new construction":
                                                $service = 'Roofing Install on New Construction Natural Slate';
                                                break;
                                            case "Completely replace roof":
                                                $service = 'Roofing Replacement Natural Slate';
                                                break;
                                            default:
                                                $service = 'Roofing Repair Natural Slate';
                                        }
                                        break;
                                    case "Tile Roofing":
                                    default:
                                        switch ($project_nature) {
                                            case "Install roof on new construction":
                                                $service = 'Roofing Install on New Construction Tile';
                                                break;
                                            case "Completely replace roof":
                                                $service = 'Roofing Replacement Tile';
                                                break;
                                            default:
                                                $service = 'Roofing Repair Tile';
                                        }
                                }

                                $url_api .= "&service=$service";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                switch ($property_type){
                                    case "Commercial":
                                        $homeowner = "Rent";
                                        $residential_commercia = "Commercial";
                                        $project = "B02-Commercial Roofing";
                                        break;
                                    default:
                                        $homeowner = "Own";
                                        $residential_commercia = "Residential";
                                        $project = "B02-Roofing";
                                }

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=B02";
                                break;
                            case 1240:
                                //Rexdirect 1240
                                $own_home = "yes";
                                $Scheduling = "flexible";

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $Category = 'Roofing - Install Asphalt Shingle';
                                                $Category_id = '101';
                                                $roof_type = "asphalt";
                                                $service_type = "install_or_replace";
                                                break;
                                            default:
                                                $Category = 'Roofing - Install Asphalt Shingle';
                                                $Category_id = '108';
                                                $roof_type = "asphalt";
                                                $service_type = "repair";
                                        }
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $Category = 'Roofing - Install Wood or Composite';
                                                $Category_id = '107';
                                                $roof_type = "wood_shake_or_composite";
                                                $service_type = "install_or_replace";
                                                break;
                                            default:
                                                $Category = 'Roofing - Repair Wood or Composite';
                                                $Category_id = '108';
                                                $roof_type = "wood_shake_or_composite";
                                                $service_type = "repair";
                                        }
                                        break;
                                    case  "Metal Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $Category = 'Roofing - Install Metal';
                                                $Category_id = '104';
                                                $roof_type = "metal";
                                                $service_type = "install_or_replace";
                                                break;
                                            default:
                                                $Category = 'Roofing - Repair Metal';
                                                $Category_id = '108';
                                                $roof_type = "metal";
                                                $service_type = "repair";
                                        }
                                        break;
                                    case "Natural Slate Roofing":
                                        switch ($project_nature){
                                            case "Completely replace roof":
                                                $Category = 'Roofing - Install Natural Slate';
                                                $Category_id = '105';
                                                $roof_type = "slate";
                                                $service_type = "install_or_replace";
                                                break;
                                            default:
                                                $Category = 'Roofing - Repair Natural Slate';
                                                $Category_id = '108';
                                                $roof_type = "slate";
                                                $service_type = "repair";
                                        }
                                        break;
                                    case "Tile Roofing":
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $Category = 'Roofing - Install Traditional Tile';
                                                $Category_id = '106';
                                                $roof_type = "tile";
                                                $service_type = "install_or_replace";
                                                break;
                                            default:
                                                $Category = 'Roofing - Repair Traditional Tile';
                                                $Category_id = '108';
                                                $roof_type = "tile";
                                                $service_type = "repair";
                                        }
                                        break;
                                }

                                $url_api .= "&service_type=$service_type&scheduling=$Scheduling&own_home=$own_home&roof_type=$roof_type&category=$Category&CategoryID=$Category_id&roof_type=$roof_type&tcpa_user_agent=$UserAgent&tcpa_text=$TCPAText";
                                break;
                            case 1249:
                                // 1249	Lead2contractors
                                $credit = "Good";
                                $propertyType = "Single Family";
                                $pitch = "Sloped";
                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "3-6 Months";
                                        break;
                                    case "Not Sure":
                                    default:
                                        $time_frame = "Flexible";
                                }
                                switch ($project_nature) {
                                    case 'Install roof on new construction':
                                        $project_nature_data = "New roof for new home";
                                        $project_nature_data2 = "New roof for new home";
                                        $repairOrReplace = "Replace";
                                        break;
                                    case "Completely replace roof":
                                        $project_nature_data = "New roof for existing home";
                                        $project_nature_data2 = "New roof for existing home";
                                        $repairOrReplace = "Replace";
                                        break;
                                    default:
                                        $project_nature_data = "Roof Repair";
                                        $project_nature_data2 = "Repair";
                                        $repairOrReplace = "Repair";
                                }
                                switch ($property_type){
                                    case "Commercial":
                                        $homeowner = "No";
                                        break;
                                    default:
                                        $homeowner = "Yes";
                                }
                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roofyType = "Asphalt Shingle";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roofyType = "Slate";
                                        break;
                                    case "Tile Roofing":
                                        $roofyType = "Tile";
                                        break;
                                    case "Metal Roofing":
                                    case "Wood Shake/Composite Roofing":
                                    default:
                                        $roofyType = "Other";
                                        break;
                                }

                                $url_api .= "&roof_project_type=$project_nature_data2&own_home=$homeowner&credit_rating=$credit&repair_or_replace=$repairOrReplace&current_roof_type=$roofyType&type_of_home=$propertyType&timeframe=$time_frame&credit=$credit&property_type=$propertyType&homeowner=$homeowner&roof_pitch=$pitch&project_type=$project_nature_data&landing_page=$OriginalURL";

                                break;
                            case 1303:
                                // 1303	Solar Direct Marketing
                                switch ($project_nature){
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

                                switch ($roof_type){
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

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "Ready Now";
                                        break;
                                    default:
                                        $start_time_data = "4 To 6 Months";
                                }

                                $url_api .= "&roof_type=$roofType&replace_or_repair=$replace_or_repair&taskId=$taskId&home_type=Single Family Home&timeFrame=$start_time_data";
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
                            case 497:
                                //Airo Marketing Inc. 497
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                if ($project_nature != "Repair section(s) of siding") {
                                    switch ($type_of_siding){
                                        case "Vinyl Siding":
                                            $Trade = "Siding - Vinyl Install or Replace";
                                            break;
                                        case "Brickface Siding" || "Stoneface Siding":
                                            $Trade = "Siding - Brick or Stone Install or Replace";
                                            break;
                                        case "Composite wood Siding":
                                            $Trade = "Siding - Wood Install or Replace";
                                            break;
                                        case "Aluminium Siding":
                                            $Trade = "Siding - Stucco Install or Replace";
                                            break;
                                        default:
                                            $Trade = "Siding - Cement Install or Replace";
                                    }
                                } else {
                                    $Trade = "Siding - Repair";
                                }

                                $url_api .= "&Trade=$Trade&Homeowner=$homeowner";
                                break;
                            case 185:
                                //snk media group 185
                                $residential = 1;
                                $ProjectType = ($project_nature != "Repair section(s) of siding" ? 15 : 7);
                                $homeowner = ($ownership == "Yes" ? 1 : 0);

                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $type_of_siding_data = '1';
                                        break;
                                    case "Brickface Siding":
                                        $type_of_siding_data = '5';
                                        break;
                                    case "Composite wood Siding":
                                        $type_of_siding_data = '2';
                                        break;
                                    default:
                                        $type_of_siding_data = '6';
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner&SidingType=$type_of_siding_data";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = ($project_nature != "Repair section(s) of siding" ? "Siding Install/Replace" : "Siding Repair");
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Home Siding";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $best_time = "Morning";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $category = ($project_nature == "Repair section(s) of siding" ? "Siding Repair - Vinyl" : "Siding Install - Vinyl");
                                        break;
                                    case "Brickface Siding" || "Stoneface Siding":
                                        $category = ($project_nature == "Repair section(s) of siding" ? "Siding Repair - Brick or stone" : "Siding Install - Brick or stone");
                                        break;
                                    case "Composite wood Siding":
                                        $category = ($project_nature == "Repair section(s) of siding" ? "Siding Repair - Wood" : "Siding Install - Wood");
                                        break;
                                    case "Aluminium Siding":
                                        $category = ($project_nature == "Repair section(s) of siding" ? "Siding Repair - Metal" : "Siding Install - Metal");
                                        break;
                                    default:
                                        $category = ($project_nature == "Repair section(s) of siding" ? "Siding Repair - Other" : "Siding Install - Other");
                                }

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $location = ($ownership == "Yes" ? "Residential" : "Commercial");
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediate";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "More Than 1 Month";
                                        break;
                                    default:
                                        $project_timeframe = "Timing is Flexible";
                                }
                                switch ($project_nature){
                                    case "Repair section(s) of siding":
                                        switch ($type_of_siding){
                                            case "Vinyl Siding":
                                                $projectType = "VINYL - Repair";
                                                break;
                                            case "Brickface Siding":
                                                $projectType = "BRICKFACE - Repair";
                                                break;
                                            case "Stoneface Siding":
                                                $projectType = "STONEFACE - Repair";
                                                break;
                                            case "Composite wood Siding":
                                                $projectType = "COMPOSITE_WOOD - Repair";
                                                break;
                                            case "Aluminium Siding":
                                                $projectType = "ALUMINIUM - Repair";
                                                break;
                                            default:
                                                $projectType = "STUCCO - Repair";
                                        }
                                        break;
                                    case "Replace existing siding":
                                    default:
                                        switch ($type_of_siding){
                                            case "Vinyl Siding":
                                                $projectType = "VINYL - Install/Replace";
                                                break;
                                            case "Brickface Siding":
                                                $projectType = "BRICKFACE - Install/Replace";
                                                break;
                                            case "Stoneface Siding":
                                                $projectType = "STONEFACE - Install/Replace";
                                                break;
                                            case "Composite wood Siding":
                                                $projectType = "COMPOSITE_WOOD - Install/Replace";
                                                break;
                                            case "Aluminium Siding":
                                                $projectType = "ALUMINIUM - Install/Replace";
                                                break;
                                            default:
                                                $projectType = "STUCCO - Install/Replace";
                                        }
                                }

                                $url_api.="&home_owner=$homeowner&time_frame=$project_timeframe&type_of_project=$projectType&location=$location";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = ($project_nature != "Repair section(s) of siding" ? "Siding Install Replace" : "Siding Repair");
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 959:
                                // 959 Pointer Leads
                                switch ($project_nature){
                                    case "Repair section(s) of siding":
                                        $pointerProjectNature = "Repair";
                                        break;
                                    case "Replace existing siding":
                                        $pointerProjectNature = "Replace";
                                        break;
                                    default:
                                        $pointerProjectNature = "Install";
                                }

                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $projectType = "Vinyl";
                                        break;
                                    case "Brickface Siding":
                                    case "Stoneface Siding":
                                        $projectType = "Brick or Stone";
                                        break;
                                    case "Composite wood Siding":
                                        $projectType = "Wood";
                                        break;
                                    case "Aluminium Siding":
                                        $projectType = "Aluminum";
                                        break;
                                    case "Fiber Cement Siding":
                                        $projectType = "Cement";
                                        break;
                                    default:
                                        $projectType = "Metal";
                                }

                                $url_api.="&siding_service=$pointerProjectNature&siding_material=$projectType";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                switch ($type_of_siding) {
                                    case "Vinyl Siding":
                                        $service = ($project_nature == "Repair section(s) of siding" ? "Siding Vinyl Repair Sections of Siding" : "Siding Vinyl Replace Existing");
                                        break;
                                    case "Brickface Siding" || "Stoneface Siding":
                                        $service = ($project_nature == "Repair section(s) of siding" ? "Siding Brickface Repair Sections of Siding" : "Siding Brickface Replace Existing");
                                        break;
                                    case "Composite wood Siding":
                                        $service = ($project_nature == "Repair section(s) of siding" ? "Siding Composite Wood Repair Sections of Siding" : "Siding Composite Wood Replace Existing");
                                        break;
                                    case "Aluminium Siding":
                                    default:
                                        $service = ($project_nature == "Repair section(s) of siding" ? "Siding Aluminum Repair Sections of Siding" : "Siding Aluminum Replace Existing");
                                }

                                $url_api .= "&service=$service";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $siding_type = "Vinyl";
                                        break;
                                    case "Brickface Siding" || "Stoneface Siding":
                                        $siding_type = "Brick or stone";
                                        break;
                                    case "Composite wood Siding":
                                        $siding_type = "Wood";
                                        break;
                                    default:
                                        $siding_type = "Other";
                                }

                                if ($project_nature == "Repair section(s) of siding") {
                                    $category = "Siding Repair";
                                    $siding_project_type = "Siding repair";
                                } else {
                                    $category = "Siding Install/Replace";
                                    $siding_project_type = "Replace siding";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $property_type = "Siding Project Type";

                                $url_api .= "&siding_type=$siding_type&siding_project_type=$siding_project_type&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time&project_type=$property_type";
                                break;
                        }
                        break;
                    case 8:
                        //Kitchen
                        $service_kitchen = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
                            case 394:
                                //Advertising Results 394
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $service_kitchen_data = ($service_kitchen == 'Full Kitchen Remodeling' ? "Floor plan" : "Cabinets");
                                $property_type = "residential";
                                $time_frame = "Timing is Flexible";
                                $project_status = "Ready to Hire";
                                $best_call_time = "Any Time";
                                $cabinet_job = "Install new custom cabinets";

                                $url_api .= "&property_type=$property_type&time_frame=$time_frame&owner=$homeowner&project_status=$project_status&project_type=$service_kitchen_data&best_call_time=$best_call_time&cabinet_job=$cabinet_job";
                                break;
                            case 185:
                                //snk media group 185
                                $Trade = "10 - Kitchen Remodel";
                                $ProjectType = "10";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = "Kitchen Remodel";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $home_owner = "No";
                                $residential_commercia = "residential";
                                $category = "Kitchen";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Anytime";
                                $kitchen_project_type = "Cabinets";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($service_kitchen){
                                    case 'Full Kitchen Remodeling':
                                        $category = "Kitchen Remodel - Full Remodel";
                                        break;
                                    default:
                                        $category = "Kitchen Remodel - Partial Remodel";
                                }

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time&kitchen_project_type=$kitchen_project_type";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = "Kitchen Remodel";
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 959:
                                // 959 pointer leads
                                $service_kitchen_data = ($service_kitchen == 'Full Kitchen Remodeling' ? "Kitchen Full Remodel" : "Kitchen Cabinet Installation");
                                $url_api.="&kitchen_service=$service_kitchen_data";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $project = "J08-Kitchen Remodel";
                                $residential_commercia = "Residential";
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=J08";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($service_kitchen){
                                    case "Full Kitchen Remodeling":
                                        $kitchenType = "Floor plan";
                                        $kitchen_cabinet_job="";
                                        break;
                                    case "Cabinet Refacing":
                                        $kitchenType = "Cabinets";
                                        $kitchen_cabinet_job = "Reface existing cabinets";
                                        break;
                                    case "Cabinet Install":
                                        $kitchenType = "Cabinets";
                                        $kitchen_cabinet_job = "Install new custom cabinets";
                                        break;
                                }

                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $property_type = "Other";
                                $best_call_time = "Any Time";
                                $project_time_frame = "Immediately";
                                $property_type_data = "Other";
                                $category = "Kitchen Remodel";
                                $estimate_timeline = "Timing Flexible";

                                $url_api .= "&kitchen_project_type=$service_kitchen&kitchen_cabinet_job=$kitchen_cabinet_job&homeowner=$homeowner&project_time_frame=$project_time_frame&best_call_time=$best_call_time&property_type=$property_type_data&category=$category";
                                break;

                        }
                        break;
                    case 9:
                        //bathroom
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $bathroom_type = trim($Leaddatadetails['services']);

                        switch ($user_id){
                            case 497:
                                //Airo Marketing Inc. 497
                                $Trade = "Bathroom - Remodel";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .= "&Trade=$Trade&Homeowner=$homeowner";
                                break;
                            case 185:
                                //snk media group 185
                                $Trade = "11 - Bathroom Remodel";
                                $ProjectType = "11";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = "Bathroom Remodel";
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Bathroom";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 636:
                                //The Lead Giant 636
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $property_type = "Single Family Home";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Unsure";
                                        break;
                                    default:
                                        $project_timeframe = "1-2 Weeks";
                                }

                                switch ($bathroom_type){
                                    case "Full Remodel":
                                        $bathroom_type_data = "Full Bathroom";
                                        break;
                                    case "Shower / Bath":
                                        $bathroom_type_data = "Shower or Bath";
                                        break;
                                    case "Sinks":
                                        $bathroom_type_data = "Sink";
                                        break;
                                    case "Flooring":
                                        $bathroom_type_data = "Tile";
                                        break;
                                    default:
                                        $bathroom_type_data = "Other";
                                }

                                $url_api .= "&homeowner=$homeowner&property_type=$property_type&project_timeframe=$project_timeframe&project_type=$bathroom_type_data";
                                break;
                            case 303:
                                //TradeMarc Global LLC 303
                                $residence_type = ($ownership == "Yes" ? "Own" : "Rent");
                                $task_id = "New";
                                $property_type = "Residential";

                                $url_api .= "&property_type=$property_type&time_frame=$start_time&residence_type=$residence_type&task_id=$task_id";
                                break;
                            case 22:
                                //Lead Cactus LLC bathroom 22
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Morning";
                                switch ($start_time) {
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-6 months";
                                        break;
                                    default:
                                        $project_timeframe = "Don't know";
                                }

                                switch ($bathroom_type){
                                    case "Full Remodel":
                                        $bathType = "bathroom_remodel";
                                        break;
                                    case "Shower / Bath":
                                        $bathType = "shower_conversion";
                                        break;
                                    default:
                                        $bathType = "other";
                                }

                                $url_api .= "&homeowner=$homeowner&start_date=$project_timeframe&project_type=$bathType&best_time=$best_time";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Anytime";
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($bathroom_type){
                                    case "Full Remodel":
                                        $bathType = "Full bathroom";
                                        $category = "Bathroom Remodel - Complete Remodel";
                                        break;
                                    case "Shower / Bath":
                                        $bathType = "Bath, sinks";
                                        $category = "Bathroom Remodel - Shower Install or Upgrade";
                                        break;
                                    case "Flooring":
                                        $bathType = "Tile";
                                        $category = "Bathroom Remodel - Vanity, Fixtures, Sinks, Toilets, other";
                                        break;
                                    default:
                                        $bathType = "Bath, sinks";
                                        $category = "Bathroom Remodel - Vanity, Fixtures, Sinks, Toilets, other";
                                }

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time&remove_walls=No&bathroom_type=$bathType";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($bathroom_type){
                                    case "Shower / Bath":
                                    case "Sinks":
                                        $bathroom_type_data = "Bath, sinks";
                                        break;
                                    case "Flooring":
                                        $bathroom_type_data = "Tile";
                                        break;
                                    case "Full Remodel":
                                    default:
                                        $bathroom_type_data = "Full bathroom";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $category = "Bathroom Remodel";
                                $best_call_time = "Any Time";
                                $project_type = "Bathroom Project Type";
                                $property_type = "Other";

                                $url_api .= "&bathroom_project_type=$bathroom_type_data&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time&project_type=$project_type&property_type=$property_type";
                                break;
                            case 959:
                                // 959 pointer leads
                                switch ($bathroom_type){
                                    case "Shower / Bath":
                                        $bathroom_servicePointer = "Bath/Shower Updates";
                                        break;
                                    case "Full Remodel":
                                    default:
                                        $bathroom_servicePointer = "Full Bathroom Remodel";
                                }
                                $url_api.="&bathroom_service=$bathroom_servicePointer";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                switch ($start_time){
                                    case "Immediately":
                                        $start_time_data = "Immediate";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "More Than 1 Month";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $url_api.="&remodeling_project=Bathroom_Remodeling&time_frame=$start_time_data&home_owner=$homeowner";
                                break;
                            case 394:
                                //Advertising Results 394
                                switch ($bathroom_type){
                                    case "Flooring":
                                        $product_needs = "New Flooring";
                                        break;
                                    case "Shower / Bath":
                                        $product_needs = "Shower Install";
                                        break;
                                    case "Sinks":
                                        $product_needs = "Sink Install";
                                        break;
                                    case "Toilet":
                                        $product_needs = "Toilet Install";
                                        break;
                                    case "Cabinets / Vanity":
                                        $product_needs = "Cabinets";
                                        break;
                                    case "Countertops":
                                        $product_needs = "Countertops";
                                        break;
                                    default:
                                        $product_needs = "Full Remodel";
                                }

                                switch ($start_time) {
                                    case "Immediately":
                                        $start_time_data = "1-2 Weeks";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "5-6 Weeks";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }
                                $service_type = "Remodel";
                                $project_status = "Ready to Hire";
                                $property_type = "Other";
                                $credit_rating = "Good";
                                $best_time_to_call = "Any Time";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .= "&service_type=$service_type&product_needs=$product_needs&project_time_frame=$start_time_data&project_status=$project_status&property_type=$property_type&credit_rating=$credit_rating&best_time_to_call=$best_time_to_call&owner=$homeowner";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = "Bathroom Remodel";
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $project = "J08-Bathroom Remodel";
                                $residential_commercia = "Residential";
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=J08";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                $service = "Bath Remodel no walls added or removed";

                                $url_api .= "&service=$service";
                                break;
                            case 1240:
                                //Rexdirect 1240
                                $homeowner = ($ownership == "Yes" ? "Y" : "N");
                                $credit_rating = "Good";
                                $best_time_to_call = "Morning";

                                $remodel_scope = "N";
                                $bath = "N";
                                $toilet = "N";
                                $cabinet = "N";
                                $sinks = "N";
                                $flooring = "N";
                                $countertop = "N";
                                $business = "N";
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 month";
                                }

                                switch ($bathroom_type){
                                    case "Full Remodel":
                                        $bath = "Y";
                                        $toilet = "Y";
                                        $cabinet = "Y";
                                        $sinks = "Y";
                                        $flooring = "Y";
                                        $countertop = "Y";
                                        $business = "Y";
                                        break;
                                    case "Cabinets / Vanity":
                                        $cabinet = "Y";
                                        break;
                                    case "Countertops":
                                        $countertop = "Y";
                                        break;
                                    case "Flooring":
                                        $flooring = "Y";
                                        break;
                                    case "Shower / Bath":
                                        $bath = "Y";
                                        break;
                                    case "Sinks":
                                        $sinks = "Y";
                                        break;
                                }

                                $url_api .= "&own_home=$homeowner&when=$project_timeframe&remodel_scope=$remodel_scope&best_time_to_call=$best_time_to_call&credit_rating=$credit_rating&bath=$bath&toilet=$toilet&cabinet=$cabinet&sinks=$sinks&flooring=$flooring&countertop=$countertop&business=$business&tcpa_consent_lang=$TCPAText&tcpa_consent=$tcpa_compliant&landing_page_url=$OriginalURL2";
                                break;
                            case 1249:
                                // 1249 Lead2contractors
                                $url_api .= "&install_repair=install";
                                break;
                            case 1303:
                                // 1303	Solar Direct Marketing
                                $url_api .= "&repair_or_replace=Replace";
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
                            case 185:
                                //snk media group 185
                                $ProjectType = ($project_nature == "Repair" ? "5" : "13");
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;
                                $AirType = "3";

                                switch ($type_of_heating){
                                    case "Natural Gas":
                                        $system_type = "6";
                                        break;
                                    case "Oil":
                                        $system_type = "8";
                                        break;
                                    case "Electric":
                                        $system_type = "9";
                                        break;
                                    case "Propane Gas":
                                        $system_type = "7";
                                        break;
                                    default:
                                        $system_type = "5";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner&HVACSystemType=$system_type&AirType=$AirType";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = ($project_nature == "Repair" ? "Furnace/Heating System - Repair/Service" : "Furnace/Heating System - Install/Replace");
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Furnace";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Anytime";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $category = ($project_nature == "Repair" ? "HVAC Repair - Furnace/Heating System" : "HVAC Install - Furnace/Heating System");

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 394:
                                //Advertising Results 394
                                switch ($start_time) {
                                    case "Immediately":
                                        $start_time_data = "1-2 Weeks";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "5-6 Weeks";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                $service_type = ($project_nature == "Repair" ? "furnaces_repair" : "furnaces_new_install");
                                $project_type = ($project_nature == "Repair" ? "Repair" : "New install");
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $air_type = "Heating";

                                $url_api .= "&air_type=$air_type&project_type=$project_type&service_type=$service_type&property_type=residential&time_frame=$start_time_data&project_status=Ready to Hire&best_time_to_call=Anytime&owner=$homeowner";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = ($project_nature == "Repair" ? "Furnace Heating System Repair Service" : "Furnace Heating System Install Replace");
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 303:
                                // TradeMarc Global LLC 303
                                switch ($type_of_heating){
                                    case "Natural Gas":
                                        $Heat_Type = "Gas furnace";
                                        $HVAC_System_Type = "Gas furnace";
                                        break;
                                    case "Oil":
                                        $Heat_Type = "Oil furnace";
                                        $HVAC_System_Type = "Oil furnace";
                                        break;
                                    case "Electric":
                                        $Heat_Type = "Electric furnace";
                                        $HVAC_System_Type = "Electric furnace";
                                        break;
                                    case "Propane Gas":
                                    default:
                                        $Heat_Type = "Propane furnace";
                                        $HVAC_System_Type = "Propane furnace";
                                }

                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "1-6 months";
                                        break;
                                    case "Not Sure":
                                        $time_frame = "don't know";
                                        break;
                                }

                                $Scope = ($project_nature == "Repair" ? "repair_boiler_furnace" : "install_boiler_furnace");
                                $HVAC_Project_Type = ($project_nature == "Repair" ? "Repair" : "Install");
                                $HVAC_Air_Type = "Heating";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .="&scope=$Scope&hvac_project_type=$HVAC_Project_Type&hvac_air_type=$HVAC_Air_Type&hvac_system_type=$HVAC_System_Type&timeframe=$time_frame&own_home=$homeowner&heat_type=$Heat_Type";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $residential_commercia = "Residential";
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");

                                switch ($type_of_heating) {
                                    case "Oil":
                                        $project = ($project_nature == "Repair" ? "F05-Oil Furnace / Forced Air Heating System - Repair" : "F05-Oil Furnace / Forced Air Heating System - Install");
                                        break;
                                    case "Electric":
                                        $project = ($project_nature == "Repair" ? "F05-Electric Furnace / Forced Air Heating System - Repair" : "F05-Electric Furnace / Forced Air Heating System - Install");
                                        break;
                                    case "Propane Gas":
                                    case "Natural Gas":
                                    default:
                                        $project = ($project_nature == "Repair" ? "F05-Gas Furnace / Forced Air Heating System - Repair" : "F05-Gas Furnace / Forced Air Heating System - Install");
                                }

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=F05";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                if ($project_nature == "Repair") {
                                    $category = "Furnace/Heating System - Repair/Service";
                                    $hvac_project_type = "Repair";
                                } else {
                                    $category = "Furnace/Heating System - Install/Replace";
                                    $hvac_project_type = "New unit installed";
                                }

                                switch ($type_of_heating){
                                    case "Natural Gas":
                                        $hvac_system_type = "Gas furnace";
                                        break;
                                    case "Oil":
                                        $hvac_system_type = "Oil furnace";
                                        break;
                                    case "Electric":
                                        $hvac_system_type = "Electric furnace";
                                        break;
                                    case "Propane Gas":
                                    default:
                                        $hvac_system_type = "Propane furnace";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $hvac_air_type = "Heating";
                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $project_type = "HVAC Project Type";

                                $url_api .= "&project_type=$project_type&hvac_air_type=$hvac_air_type&hvac_system_type=$hvac_system_type&hvac_project_type=$hvac_project_type&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time";
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
                            case 185:
                                //snk media group 185
                                $ProjectType = ($project_nature == "Repair" ? "5" : "13");
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;
                                $AirType = "3";

                                switch ($type_of_heating){
                                    case "Natural Gas" || "Propane Gas":
                                        $system_type = "2";
                                        break;
                                    case "Electric":
                                        $system_type = "3";
                                        break;
                                    default:
                                        $system_type = "5";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner&HVACSystemType=$system_type&AirType=$AirType";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = ($project_nature == "Repair" ? "Boiler Repair/Service" : "Boiler Install/Replace");
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Boiler";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Anytime";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $category = ($project_nature == "Repair" ? "HVAC Repair - Boiler" : "HVAC Install - Boiler");

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 394:
                                //Advertising Results 394
                                switch ($start_time) {
                                    case "Immediately":
                                        $start_time_data = "1-2 Weeks";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "5-6 Weeks";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                $service_type = ($project_nature == "Repair" ? "boilers_and_radiators_repair" : "boilers_and_radiators_new_install");
                                $project_type = ($project_nature == "Repair" ? "Repair" : "New install");
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $air_type = "Heating";

                                $url_api .= "&air_type=$air_type&project_type=$project_type&service_type=$service_type&property_type=residential&time_frame=$start_time_data&project_status=Ready to Hire&best_time_to_call=Anytime&owner=$homeowner";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = ($project_nature == "Repair" ? "Boiler Repair Service" : "Boiler Install Replace");
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 303:
                                // TradeMarc Global LLC 303
                                switch ($type_of_heating){
                                    case "Natural Gas":
                                        $Heat_Type = "Gas boiler";
                                        $HVAC_System_Type = "Gas boiler";
                                        break;
                                    case "Oil":
                                        $Heat_Type = "Oil boiler";
                                        $HVAC_System_Type = "Oil boiler";
                                        break;
                                    case "Electric":
                                        $Heat_Type = "Electric boiler";
                                        $HVAC_System_Type = "Electric boiler";
                                        break;
                                    case "Propane Gas":
                                    default:
                                        $Heat_Type = "Propane boiler";
                                        $HVAC_System_Type = "Propane boiler";
                                }

                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "1-6 months";
                                        break;
                                    case "Not Sure":
                                        $time_frame = "don't know";
                                        break;
                                }

                                $Scope = ($project_nature == "Repair" ? "repair_boiler_furnace" : "install_boiler_furnace");
                                $HVAC_Project_Type = ($project_nature == "Repair" ? "Repair" : "Install");
                                $HVAC_Air_Type = "Heating";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .="&scope=$Scope&hvac_project_type=$HVAC_Project_Type&hvac_air_type=$HVAC_Air_Type&hvac_system_type=$HVAC_System_Type&timeframe=$time_frame&own_home=$homeowner&heat_type=$Heat_Type";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $residential_commercia = "Residential";
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");

                                switch ($type_of_heating) {
                                    case "Oil":
                                        $project = ($project_nature == "Repair" ? "F05-Oil Boiler or Radiator Heating System - Repair" : "F05-Oil Boiler or Radiator Heating System - Install");
                                        break;
                                    case "Electric":
                                        $project = ($project_nature == "Repair" ? "F05-Electric Boiler or Radiator Heating System - Repair" : "F05-Electric Boiler or Radiator Heating System - Install");
                                        break;
                                    case "Propane Gas":
                                    case "Natural Gas":
                                    default:
                                        $project = ($project_nature == "Repair" ? "F05-Gas Boiler or Radiator Heating System - Repair" : "F05-Gas Boiler or Radiator Heating System - Install");
                                }

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=F05";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                if ($project_nature == "Repair") {
                                    $category = "Boiler Repair";
                                    $hvac_project_type = "Repair";
                                } else {
                                    $category = "Boiler Install";
                                    $hvac_project_type = "New unit installed";
                                }

                                switch ($type_of_heating){
                                    case "Natural Gas":
                                        $hvac_system_type = "Gas boiler";
                                        break;
                                    case "Oil":
                                        $hvac_system_type = "Oil boiler";
                                        break;
                                    case "Electric":
                                        $hvac_system_type = "Electric boiler";
                                        break;
                                    case "Propane Gas":
                                    default:
                                        $hvac_system_type = "Propane boiler";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $hvac_air_type = "Heating";
                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $project_type = "HVAC Project Type";

                                $url_api .= "&project_type=$project_type&hvac_air_type=$hvac_air_type&hvac_system_type=$hvac_system_type&hvac_project_type=$hvac_project_type&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time";
                                break;
                        }
                        break;
                    case 13:
                        //Central A/C
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        switch ($user_id){
                            case 185:
                                //snk media group 185
                                $ProjectType = ($project_nature == "Repair" ? "5" : "13");
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;
                                $AirType = "3";
                                $system_type = "1";

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner&HVACSystemType=$system_type&AirType=$AirType";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = ($project_nature == "Repair" ? "Central A/C - Repair/Service" : "Central A/C - Install/Replace");
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Central A/C";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Anytime";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                $category = ($project_nature == "Repair" ? "HVAC Repair - Central A/C" : "HVAC Install - Central A/C");

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 394:
                                //Advertising Results 394
                                switch ($start_time) {
                                    case "Immediately":
                                        $start_time_data = "1-2 Weeks";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "5-6 Weeks";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                $service_type = ($project_nature == "Repair" ? "central_air_repair" : "central_air_new_install");
                                $project_type = ($project_nature == "Repair" ? "Repair" : "New install");
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $air_type = "Heating and Cooling";

                                $url_api .= "&air_type=$air_type&project_type=$project_type&service_type=$service_type&property_type=residential&time_frame=$start_time_data&project_status=Ready to Hire&best_time_to_call=Anytime&owner=$homeowner";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = ($project_nature == "Repair" ? "Central A/C Repair Service" : "Central A/C Install Replace");
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 959:
                                // 959 pointer leads
                                $HVACType = "Central A/C";
                                $type_of_work = ($project_nature == "Repair" ? "Repair" : "Install");
                                $url_api.="&hvac_service=$type_of_work&hvac_system_type=$HVACType";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                $service = ($project_nature == "Repair" ? "HVAC Repair Central AC" : "HVAC Install Central AC");
                                $url_api .= "&service=$service";
                                break;
                            case 303:
                                // TradeMarc Global LLC 303
                                switch ($start_time){
                                    case "Immediately":
                                        $time_frame = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $time_frame = "1-6 months";
                                        break;
                                    case "Not Sure":
                                        $time_frame = "don't know";
                                        break;
                                }

                                $Heat_Type = "Central AC";
                                $HVAC_System_Type = "Central AC";
                                $Scope = ($project_nature == "Repair" ? "repair_central_ac" : "install_central_ac");
                                $HVAC_Project_Type = ($project_nature == "Repair" ? "Repair" : "Install");
                                $HVAC_Air_Type = "Heating and Cooling";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .="&scope=$Scope&hvac_project_type=$HVAC_Project_Type&hvac_air_type=$HVAC_Air_Type&hvac_system_type=$HVAC_System_Type&timeframe=$time_frame&own_home=$homeowner&heat_type=$Heat_Type";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $residential_commercia = "Residential";
                                $homeowner = ($ownership == "Yes" ? "Own" : "Rent");
                                $project = ($project_nature == "Repair" ? "F05-Central A/C - Repair/Service" : "F05-Central A/C - Install/Replace");

                                $url_api .= "&project=$project&property_type=$residential_commercia&homeowner=$homeowner&contractor_ID=F05";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                if ($project_nature == "Repair") {
                                    $category = "Central A/C - Repair/Service";
                                    $hvac_project_type = "Repair";
                                } else {
                                    $category = "Central A/C - Install/Replace";
                                    $hvac_project_type = "New unit installed";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $hvac_system_type = "Central AC";
                                $hvac_air_type = "Heating and cooling";
                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $project_type = "HVAC Project Type";

                                $url_api .= "&project_type=$project_type&hvac_air_type=$hvac_air_type&hvac_system_type=$hvac_system_type&hvac_project_type=$hvac_project_type&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time";
                                break;
                        }
                        break;
                    case 14:
                        //Cabinet
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
                            case 185:
                                //snk media group 185
                                $Trade = "18 - Cabinets";
                                $ProjectType = "18";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
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
                            case 497:
                                //Airo Marketing Inc. 497
                                $Trade = "Bathroom - Bathtub to Shower Conversion";
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $url_api .= "&Trade=$Trade&Homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = "Bathtub/Shower - Install/Replace";
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Bathtub/Shower";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = "Bathtub Shower Install Replace";
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                        }
                        break;
                    case 18:
                        //Handyman
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        switch ($user_id){
                            case 185:
                                //snk media group 185
                                $Trade = "25 - Handyman";
                                $ProjectType = "25";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = "Handyman";
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Handyman";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = "Handyman";
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                        }
                        break;
                    case 20:
                        //Doors
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        switch ($user_id){
                            case 185:
                                //snk media group 185
                                $Trade = "20 - Doors";
                                $ProjectType = "20";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
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
                            case 185:
                                //snk media group 185
                                $Trade = "24 - Gutter";
                                $ProjectType = "24";
                                $homeowner = ($ownership == "Yes" ? "1" : "0");
                                $residential = 1;

                                switch ($start_time){
                                    case 'Immediately':
                                        $start_time_data = "1";
                                        break;
                                    case 'Not Sure':
                                        $start_time_data = "2";
                                        break;
                                    default:
                                        $start_time_data = "4";
                                }

                                $url_api .= "&projectType=$ProjectType&timeframe=$start_time_data&residential=$residential&homeowner=$homeowner";
                                break;
                            case 540:
                                //pbtp Powered by The People LLC 540
                                $type_of_work = ($project_nature == "Repair" ? "Gutter Repair" : "Gutter Install/Replace");
                                $home_owner = ($ownership == "Yes" ? "Yes" : "No");
                                $residential_commercia = "residential";
                                $category = "Gutters";

                                $url_api .= "&type_of_work=$type_of_work&home_owner=$home_owner&residential_commercia=$residential_commercia&category=$category";
                                break;
                            case 546:
                                //Remodel Well 546
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $best_time = "Morning";

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "1-3 months";
                                        break;
                                    default:
                                        $project_timeframe = "Within 1 months";
                                }

                                switch ($service){
                                    case "Galvanized Steel":
                                        $category = ($project_nature == "Repair" ? "Gutters Repair - Galvanized" : "Gutters Install - Galvanized");
                                        break;
                                    case "PVC":
                                        $category = ($project_nature == "Repair" ? "Gutters Repair - PVC" : "Gutters Install - PVC");
                                        break;
                                    case "Seamless Aluminum":
                                        $category = ($project_nature == "Repair" ? "Gutters Repair - Seamless Metal" : "Gutters Install - Seamless Metal");
                                        break;
                                    case "Wood":
                                        $category = ($project_nature == "Repair" ? "Gutters Repair - Wood" : "Gutters Install - Wood");
                                        break;
                                    default:
                                        $category = ($project_nature == "Repair" ? "Gutters Repair - Galvanized" : "Gutters Install - Galvanized");
                                }

                                $url_api .= "&homeowner=$homeowner&timeframe=$project_timeframe&category=$category&best_time_to_call=$best_time";
                                break;
                            case 974:
                                // 974 OnCore Leads
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $location = ($ownership == "Yes" ? "Residential" : "Commercial");
                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediate";
                                        break;
                                    case "Within 6 months":
                                        $project_timeframe = "More Than 1 Month";
                                        break;
                                    default:
                                        $project_timeframe = "Timing is Flexible";
                                }
                                switch ($service){
                                    case "Galvanized Steel":
                                        $category = ($project_nature == "Repair" ? "Galvanized Gutters - Repair" : "Galvanized Gutters - Install");
                                        break;
                                    case "PVC":
                                        $category = ($project_nature == "Repair" ? "PVC Gutters - Repair" : "PVC Gutters - Install");
                                        break;
                                    case "Seamless Aluminum":
                                        $category = ($project_nature == "Repair" ? "Seamless Metal Gutters - Repair" : "Seamless Metal Gutters - Install");
                                        break;
                                    case "Wood":
                                        $category = ($project_nature == "Repair" ? "Wood Gutters - Repair" : "Wood Gutters - Install");
                                        break;
                                    case "Copper":
                                        $category = ($project_nature == "Repair" ? "Copper Gutters - Repair" : "Copper Gutters - Install");
                                        break;
                                    default:
                                        $category = ($project_nature == "Repair" ? "Gutter Cleaning" : "Install or Replace Gutter Covers and Protection");
                                }

                                $url_api.="&home_owner=$homeowner&time_frame=$project_timeframe&type_of_project=$category&location=$location";
                                break;
                            case 394:
                                //Advertising Results 394
                                switch ($start_time) {
                                    case "Immediately":
                                        $start_time_data = "1-2 Weeks";
                                        break;
                                    case 'Within 6 months':
                                        $start_time_data = "5-6 Weeks";
                                        break;
                                    default:
                                        $start_time_data = "Timing is Flexible";
                                }

                                $service_type = ($project_nature == "Repair" ? "repair" : "install");
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");

                                switch ($service) {
                                    case "Galvanized Steel":
                                        $product_type = "Galvanized";
                                        break;
                                    case "PVC":
                                        $product_type = "PVC";
                                        break;
                                    case "Seamless Aluminum":
                                        $product_type = "Seamless Metal";
                                        break;
                                    default:
                                        $product_type = "other";
                                }

                                $url_api .= "&product_type=$product_type&service_type=$service_type&property_type=Residential&time_frame=$start_time_data&best_time_to_call=Anytime&owner=$homeowner";
                                break;
                            case 1025:
                                //MILI Group LLC 1025
                                $type_of_work = ($project_nature == "Repair" ? "Gutter Repair" : "Gutter Install Replace");
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $url_api .= "&Project=$type_of_work&home_owner=$homeowner";
                                break;
                            case 1045:
                                //UptownLeads LLC  1045
                                switch ($service){
                                    case "Galvanized Steel":
                                        $service = ($project_nature == "Repair" ? "Gutters Galvanized Repair Home" : "Gutters Galvanized Install Home");
                                        break;
                                    case "PVC":
                                        $service = ($project_nature == "Repair" ? "Gutters PVC Repair Home" : "Gutters PVC Install Home");
                                        break;
                                    case "Seamless Aluminum":
                                        $service = ($project_nature == "Repair" ? "Gutters Seamless Metal Repair Home" : "Gutters Seamless Metal Install Home");
                                        break;
                                    case "Wood":
                                        $service = ($project_nature == "Repair" ? "Gutters Wood Repair Home" : "Gutters Wood Install Home");
                                        break;
                                    case "Copper":
                                        $service = ($project_nature == "Repair" ? "Gutters Copper Repair Home" : "Gutters Copper Install Home");
                                        break;
                                    default:
                                        $service = ($project_nature == "Repair" ? "Gutters Galvanized Repair Home" : "Gutters Galvanized Install Home");
                                }

                                $url_api .= "&service=$service";
                                break;
                            case 939:
                                //939 Turtle Leads LLC
                                switch ($service){
                                    case "Galvanized Steel":
                                        $category = ($project_nature == "Repair" ? "Galvanized Gutters - Repair" : "Galvanized Gutters - Install");
                                        break;
                                    case "PVC":
                                        $category = ($project_nature == "Repair" ? "PVC Gutters - Repair" : "PVC Gutters - Install");
                                        break;
                                    case "Seamless Aluminum":
                                        $category = ($project_nature == "Repair" ? "Seamless Metal Gutters - Repair" : "Seamless Metal Gutters - Install");
                                        break;
                                    case "Wood":
                                        $category = ($project_nature == "Repair" ? "Wood Gutters - Repair" : "Wood Gutters - Install");
                                        break;
                                    default:
                                        $category = ($project_nature == "Repair" ? "Gutter Repair" : "Gutter Install/Replace");
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $project_timeframe = "Immediately";
                                        break;
                                    case 'Not Sure':
                                        $project_timeframe = "Within 1 months";
                                        break;
                                    default:
                                        $project_timeframe = "1-3 months";
                                }

                                $homeowner = ($ownership == "Yes" ? "True" : "False");
                                $best_call_time = "Any Time";
                                $gutter_protection = False;
                                $property_type = "Other";

                                $url_api .= "&gutter_protection=$gutter_protection&category=$category&homeowner=$homeowner&project_time_frame=$project_timeframe&best_call_time=$best_call_time&property_type=$property_type";
                                break;
                        }
                        break;
                    case 23:
                        //Painting
                        $service = trim($Leaddatadetails['service']);
                        switch ($user_id){
                            case 959:
                                // 959 pointer leads
                                switch ($service) {
                                    case "Exterior Home or Structure - Paint or Stain":
                                        $painting_service = "Exterior Painting - Whole House";
                                        break;
                                    case "Interior Home or Surfaces - Paint or Stain":
                                        $rooms_number = trim($Leaddatadetails['rooms_number']);
                                        switch ($rooms_number) {
                                            case "1-2":
                                                $painting_service = "Interior Painting - 1 to 2 Rooms";
                                                break;
                                            default:
                                                $painting_service = "Interior Painting - 3+ Rooms";
                                        }
                                        break;
                                    case "Painting or Staining - Small Projects":
                                    default:
                                        $painting_service = "Paint/Stain - Deck/Fence/Porch";
                                }

                                $url_api.="&painting_service=$painting_service";
                                break;
                        }
                        break;
                    case 24:
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

                        switch ($user_id){
                            case 185:
                                //snk media group 185
                                $married_data = ( $married == "Yes" ? "Married" : "Single" );
                                $license_data = ( $license == "Yes" ? "Active" : "Expired" );
                                $filing_required = ( $SR_22_need == "Yes" ? "SR-22" : "None" );
                                $ownership_data = ( $ownership == "Yes" ? "Own" : "Rent" );
                                $tickets_accidents_claims = ( $driver_experience == "Yes" ? "No" : "Yes" );
                                $DUI_charges_data = ( $DUI_charges == "Yes" ? "Yes" : "No" );
                                $birthday = date("m/d/Y", strtotime($birthday));

                                $url_api .= "&dob=$birthday&gender=$genders&marital_status=$married_data&credit_rating=Good&license_status=$license_data&licensed_state=$state&age_when_first_licensed=19&filing_required=$filing_required&education=Unknown&Occupation=Other/Not Listed&current_residence=$ownership_data&tickets_accidents_claims=$tickets_accidents_claims&insured_past_30_days=Yes&insurance_company=$InsuranceCarrier&bankruptcy_in_past_5_years=No&additional_drivers=$driversNum&additional_vehicles=$more_than_one_vehicle&reposessions=Unknown&dui_dwi=$DUI_charges_data&vehicle_year=$VehicleYear&vehicle_make=$VehicleMake&vehicle_model=$car_model&vehicle_ownership=Owned&vehicle_primary_use=Commute To/From Work&vehicle_one_way_mileage=15&vehicle_annual_mileage=20000&vehicle_parking=Private Garage&vehicle_days_per_week_used=6&vehicle_collision_coverage=No Coverage&vehicle_comprehensive_coverage=No Coverage";
                                break;
                            case 276:
                                //ETN AMERICA 276
                                $married_data = ( $married == "Yes" ? "Married" : "Single" );
                                $filing_required = ( $SR_22_need == "Yes" ? "SR22 Required" : "SR22 NOT required" );
                                $ownership_data = ( $ownership == "Yes" ? "Own" : "Rent" );
                                $birthday = date("m/d/Y", strtotime($birthday));
                                $have_insurance = ($InsuranceCarrier == "Not Currently Insured" ? "No" : "Yes");
                                $continuously_insured_period = ($InsuranceCarrier == "Not Currently Insured" ? "Not Currently Insured" : "1 Year");
                                $current_policy_start_date = date("m/d/Y");

                                $url_api .= "&dob=$birthday&bankruptcy=No&credit_rating=Good&have_insurance=$have_insurance&continuously_insured_period=$continuously_insured_period&coverage_type=Standard&insurance_company=$InsuranceCarrier&residence_type=$ownership_data&driver1_gender=$genders&driver1_marital_status=$married_data&driver1_occupation=Unknown&driver1_education=Other&driver1_required_sr22=$filing_required&vehicle1_year=$VehicleYear&vehicle1_make=$VehicleMake&vehicle1_model=$car_model&vehicle1_vehicle_ownership=Owned&current_policy_start_date=$current_policy_start_date&current_policy_expiration_date=$current_policy_start_date&years_at_residence=3&months_at_residence=4&vehicle1_collision_deductible=$0&vehicle1_comprehensive_deductible=$0&vehicle1_annual_mileage=18,001 to 25,000 miles&vehicle1_daily_mileage=up to 19 miles&vehicle1_primary_use=Commute Work&vehicle1_sub_model=$car_model";
                                break;
                        }
                        break;
                    case 25:
                        //home Insurance
                        $house_type = trim($Leaddatadetails['house_type']);
                        $Year_Built = trim($Leaddatadetails['Year_Built']);
                        $primary_residence = trim($Leaddatadetails['primary_residence']);
                        $new_purchase = trim($Leaddatadetails['new_purchase']);
                        $previous_insurance_within_last30 = trim($Leaddatadetails['previous_insurance_within_last30']);
                        $previous_insurance_claims_last3yrs = trim($Leaddatadetails['previous_insurance_claims_last3yrs']);
                        $married = trim($Leaddatadetails['married']);
                        $credit_rating = trim($Leaddatadetails['credit_rating']);
                        $birthday = trim($Leaddatadetails['birthday']);

                        switch ($user_id){
                            case 276:
                                //ETN AMERICA 276
                                $birthday = date("m/d/Y", strtotime($birthday));
                                $occupancy = ( $primary_residence == "Yes" ? "Primary Residence" : "Seasonal Residence" );

                                switch($house_type){
                                    case "Apartment":
                                        $property_type = "Condominium";
                                        break;
                                    case "Condo/TownHouse":
                                        $property_type = "Townhome";
                                        break;
                                    case "Duplex":
                                        $property_type = "Duplex";
                                        break;
                                    default:
                                        $property_type = "Single Family";
                                }

                                $url_api .= "&dob=$birthday&credit=$credit_rating&currently_insured=$previous_insurance_within_last30&property_type=$property_type&occupancy=$occupancy&garage=Other&foundation=Other&year_built=$Year_Built&construction_type=Other&roof_type=Other&heating_type=Unknown&indoor_sprinklers=Yes&central_air_conditioning=Yes&claims=None&coverage_type=Full Package&liability=500000&deductible=500&current_coverage_type=Full Package&current_insurance_company=Other&stories=2&bedrooms=3&bathrooms=2&home_value=150000&smoke_alarm=Yes";
                                break;
                        }
                        break;
                    case 26:
                        //Life Insurance
                        $Height = trim($Leaddatadetails['Height']);
                        $weight = trim($Leaddatadetails['weight']);
                        $birthday = trim($Leaddatadetails['birthday']);
                        $genders = trim($Leaddatadetails['genders']);
                        $amount_coverage = trim($Leaddatadetails['amount_coverage']);
                        $military_personnel_status = trim($Leaddatadetails['military_personnel_status']);
                        $military_status = trim($Leaddatadetails['military_status']);
                        $service_branch = trim($Leaddatadetails['service_branch']);

                        switch ($user_id){
                            case 276:
                                //ETN AMERICA 276
                                $birthday = date("m/d/Y", strtotime($birthday));
                                $Height_data = explode(" " , $Height);
                                $height_feet = $Height_data[0];
                                $height_inches = $Height_data[1];
                                $weight = (int) filter_var($weight, FILTER_SANITIZE_NUMBER_INT);
                                $amount_coverage = (int) filter_var($amount_coverage, FILTER_SANITIZE_NUMBER_INT);

                                $url_api .= "&dob=$birthday&gender=$genders&occupation=Unknown&education_level=Other&height_feet=$height_feet&height_inches=$height_inches&weight=$weight&insurance_amount=$amount_coverage&coverage_term=Not Sure&residence_type=Own&years_at_residence=2&months_at_residence=5&student=No&marital_status=Married&dui=No&previously_denied=No&expectant_parent=No&is_smoker=No&relative_heart=No&relative_cancer=No&requested_coverage_type=Unsure Life&have_insurance=No&treated=Other&policy_for=Other&current_coverage_type=Unsure Life&current_insurance_company=Other&hospitalized=No&ongoing_medical_treatment=No&hazard_pilot=No&hazard_felony=No";
                                break;
                        }
                        break;
                    case 29:
                        //Health Insurance
                        $gender = trim($Leaddatadetails['gender']);
                        $birthday = trim($Leaddatadetails['birthday']);
                        $pregnancy = trim($Leaddatadetails['pregnancy']);
                        $tobacco_usage = trim($Leaddatadetails['tobacco_usage']);
                        $health_conditions = trim($Leaddatadetails['health_conditions']);
                        $number_of_people_in_household = trim($Leaddatadetails['number_of_people_in_household']);
                        $addPeople = trim($Leaddatadetails['addPeople']);
                        $annual_income = trim($Leaddatadetails['annual_income']);

                        switch ($user_id){
                            case 276:
                                //ETN AMERICA 276
                                $birthday = date("m/d/Y", strtotime($birthday));
                                $requested_coverage_type = str_replace('&quot;', "", $addPeople);
                                $requested_coverage_type = str_replace('[', "", $requested_coverage_type);
                                $requested_coverage_type = str_replace(']', "", $requested_coverage_type);
                                $requested_coverage_type = str_replace(']', "", $requested_coverage_type);
                                $requested_coverage_type = str_replace('\\', "", $requested_coverage_type);
                                $requested_coverage_type_bool = "Individual Plan";
                                if(str_contains($requested_coverage_type, 'With Spouse') || str_contains($requested_coverage_type, 'With Child/Children')){
                                    $requested_coverage_type_bool = "Family Plan";
                                }

                                $url_api .= "&dob=$birthday&gender=$gender&occupation=Unknown&education_level=Other&medical_condition=Other&expectant_parent=$pregnancy&is_smoker=$tobacco_usage&requested_coverage_type=$requested_coverage_type_bool&residence_type=Own&years_at_residence=3&months_at_residence=6&marital_status=Married&student=No&height_feet=5&height_inches=70&weight=70&dui=No&previously_denied=No&relative_heart=No&relative_cancer=No&have_insurance=No";
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
