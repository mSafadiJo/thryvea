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
                    case 44:
                        //UptownLeads LLC  1045
                        $url_api .= "&trusted_form_cert_id=$trusted_form&jornaya_lead_id=$LeadId&tcpa_consent_language=$TCPAText";
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
                            case 44:
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

                                $url_api .= "&service=$service&landing_page_url=https://thewindowsinstall.com/Quote?ts=thv$google_ts&lp_s2=thv$google_ts";
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
                            case 44:
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

                                $url_api .= "&service=$service&landing_page_url=https://theflooringinstall.net/Quote?ts=thv$google_ts&lp_s2=thv$google_ts";
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
                            case 44:
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

                                $url_api .= "&service=$service&landing_page_url=https://homeremodelingpro.net/Quote?ts=thv$google_ts&lp_s2=thv$google_ts";
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
                            case 44:
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

                                $url_api .= "&service=$service&landing_page_url=https://homeremodelingpro.net/Quote?ts=thv$google_ts&lp_s2=thv$google_ts";
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
                            case 44:
                                //UptownLeads LLC  1045
                                $service = "Bath Remodel no walls added or removed";

                                $url_api .= "&service=$service&landing_page_url=https://thebathroomremodel.net/Quote?ts=thv$google_ts&lp_s2=thv$google_ts";
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

                        $Lead_data_ping = "ip=$IPAddress&useragent=$UserAgent&country_iso_2=US&region=$statename_code&referrer=$OriginalURL2&zipcode=$zip&trusted_form_cert_url=$trusted_form&trusted_form_cert_id=$trusted_form&s2=$google_ts";

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
                        //Clean Energy Authority 29
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
                                    $url_api_ping = "https://uat.sbbnetinc.com/rest/api/windows/submit-inquiry";
                                    $source_name = "Test";
                                } else {
                                    //Live Mode
                                    $url_api_ping = "https://live.sbbnetinc.com/rest/api/windows/submit-inquiry";
                                    $source_name = "thrwi";
                                }

                                $Lead_data_array_ping = array(
                                    "API_Action" => "pingPostLead",
                                    "Mode" => "ping",
                                    "IP_Address" => $IPAddress,
                                    "SRC" => $source_name,
                                    "Sub_ID" => $leadCustomer_id,
                                    "Pub_ID" => $google_ts,
                                    "Return_Best_Price" => "1",
                                    "TYPE" => "18",
                                    "Property_Zip" => $zip,
                                    "Property_State" => $statename_code,
                                    "Property_Owner" => "Yes",
                                    "Landing_Page" => $OriginalURL2,
                                    "Property_City" => $city,
                                    "Universal_Lead_ID" => $LeadId,
                                    "Best_Time_To_Call" => "Anytime",
                                    "Window_Task" => $NumWindows,
                                    "Timing" => "Immediately",
                                    "Tcpa_Language" => $TCPAText,
                                    "vendor_lead_id" => $leadCustomer_id
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
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'Match') == true) {
                                                $TransactionId = $result4['id'];
                                                $Payout = $result4['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                }
                                break;
                            case 2:
                                //Solar
                                $property_type_data = 1;
                                $times_remaining_to_sell = 4;
                                $times_previously_sold = 0;
                                $inquiry_datetime = date('m/d/Y H:i:s');

                                $httpheader = array(
                                    'Authorization: Basic cmVzdC11c2VyOjVTOGNCRHEmRWYha3BMKk5XNXVM',
                                    'Content-Type: application/xml',
                                );

                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);

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

                                switch ($roof_shade) {
                                    case "Mostly Shaded":
                                        $roof_shade_data = 4;
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = 2;
                                        break;
                                    default:
                                        $roof_shade_data = 1;
                                }

                                if (config('app.env', 'local') == "local") {
                                    //Test Mode
                                    $url_api_ping = "https://uat.sbbnetinc.com/rest/api/solar/submit-inquiry";
                                    $source_name = "test";
                                    $vendor_id = "8";
                                } else {
                                    //Live Mode
                                    $url_api_ping = "https://live.sbbnetinc.com/rest/api/solar/submit-inquiry";
                                    $source_name = "THRYVEASOT1";
                                    $vendor_id = "65046";
                                }

                                $utility_company_id = "00000000";
                                $hot_water_inquiry = 0;
                                $electric_inquiry = 1;


                                $Lead_data_ping = '<cea_ping>
                                  <consumer_inquiry>
                                    <property_sun_exposure>' . $roof_shade_data . '</property_sun_exposure>
                                    <property_type>' . $property_type_data . '</property_type>
                                    <property_zip_code>' . $zip . '</property_zip_code>
                                    <monthly_electricity_cost>' . $monthly_bill . '</monthly_electricity_cost>
                                    <utility_company_id>' . $utility_company_id . '</utility_company_id>
                                    <universal_lead_id>' . $LeadId . '</universal_lead_id>
                                    <consumer_ip_address>' . $IPAddress . '</consumer_ip_address>
                                    <times_remaining_to_sell>' . $times_remaining_to_sell . '</times_remaining_to_sell>
                                    <times_previously_sold>' . $times_previously_sold . '</times_previously_sold>
                                    <hot_water_inquiry>' . $hot_water_inquiry . '</hot_water_inquiry>
                                    <electric_inquiry>' . $electric_inquiry . '</electric_inquiry>
                                    <inquiry_datetime>' . $inquiry_datetime . '</inquiry_datetime>
                                    <vendor_lead_id>' . $leadCustomer_id . '</vendor_lead_id>
                                    <active_prospect_url>' . $trusted_form . '</active_prospect_url>
                                    <source_name>' . $source_name . '</source_name>
                                    <vendor_id>' . $vendor_id . '</vendor_id>
                                    <vendor_pub_id>' . $google_ts . '</vendor_pub_id>
                                  </consumer_inquiry>
                                </cea_ping>';

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
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'Match') == true) {
                                                $TransactionId = $result4['id'];
                                                $Payout = $result4['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                }
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);

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
                                    $url_api_ping = "https://uat.sbbnetinc.com/rest/api/roofing/submit-inquiry";
                                    $source_name = "Test";
                                } else {
                                    //Live Mode
                                    $url_api_ping = "https://live.sbbnetinc.com/rest/api/roofing/submit-inquiry";
                                    $source_name = "thrro";
                                }

                                $Lead_data_array_ping = array(
                                    "Request" => array(
                                        "API_Action" => "pingPostLead",
                                        "Mode" => "ping",
                                        "IP_Address" => $IPAddress,
                                        "SRC" => $source_name,
                                        "Sub_ID" => $leadCustomer_id,
                                        "Pub_ID" => $google_ts,
                                        "Return_Best_Price" => "1",
                                        "TYPE" => "16",
                                        "Property_Zip" => $zip,
                                        "Property_State" => $statename_code,
                                        "Property_Owner" => "Yes",
                                        "Landing_Page" => $OriginalURL2,
                                        "Property_City" => $city,
                                        "Universal_Lead_ID" => $LeadId,
                                        "Best_Time_To_Call" => "Anytime",
                                        "Timing" => "Immediately",
                                        "Tcpa_Language" => $TCPAText,
                                        "vendor_lead_id" => $leadCustomer_id,
                                        "Roof_Material" => $Type_OfRoofing_data,
                                        "Roof_Task" => $roof_task,
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
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'Match') == true) {
                                                $TransactionId = $result4['id'];
                                                $Payout = $result4['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

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
                    case 34:

                        // 1) Determine Ping URL once
                        $url_api_ping = (config('app.env') == "local")
                            ? "https://exchange.standardinformation.io/ping_test?legacy=J"
                            : "https://exchange.standardinformation.io/ping?legacy=J";

                        // 2) Base Meta (common for all)
                        $base_meta = [
                            "landing_page_url"      => ($OriginalURL2 ?: "will provide on post"),
                            "source_id"             => $google_ts,
                            "lead_id_code"          => $LeadId,
                            "trusted_form_cert_url" => $trusted_form,
                            "tcpa_compliant"        => true,
                            "tcpa_consent_text"     => $TCPAText,
                            "user_agent"            => $UserAgent,
                            "originally_created"    => gmdate("Y-m-d\TH:i:s\Z")
                        ];

                        // 3) Base Partial Contact (ZIP + IP only)
                        $base_contact = [
                            "zip_code"   => $zip,
                            "ip_address" => $IPAddress
                        ];

                        // 4) Service-mapping
                        switch ($lead_type_service_id) {

                            case 1: // WINDOWS
                                $num = trim($Leaddatadetails['number_of_window']);
                                $num = ($num === "3-5") ? "5" : (($num === "6-9") ? "9" : "10");

                                $service_data = [
                                    "windows" => [
                                        "num_windows"  => $num,
                                        "project_type" => (trim($Leaddatadetails['project_nature']) == "Repair"
                                            ? "Need repair services at this time"
                                            : "Interested in replacement windows"),
                                    ]
                                ];

                                $own_property = (trim($Leaddatadetails['homeOwn']) != "Yes");
                                $authToken = "e373e16a-0bfb-4a2d-ad36-48794909cfe0";
                                break;
                            case 6: // ROOFING
                                $roof_map = [
                                    "Asphalt Roofing"               => "Asphalt shingle",
                                    "Wood Shake/Composite Roofing"  => "Composite",
                                    "Metal Roofing"                 => "Metal",
                                    "Natural Slate Roofing"         => "Natural slate",
                                    "Tile Roofing"                  => "Tile",
                                ];

                                $service_data = [
                                    "roof" => [
                                        "project_type" => (trim($Leaddatadetails['project_nature']) == "Repair existing roof"
                                            ? "Repair"
                                            : "New roof for new home"),
                                        "roofing_type" => $roof_map[$Leaddatadetails['roof_type']] ?? "Not Sure",
                                    ]
                                ];

                                $own_property = (trim($Leaddatadetails['property_type']) != "Rented");
                                $authToken = "249c60d5-8890-460f-bdef-7b2cbcf33f45";
                                break;
                            case 7: // SIDING
                                $siding_map = [
                                    "Vinyl Siding"           => "Vinyl",
                                    "Brickface Siding"       => "Brick or stone",
                                    "Stoneface Siding"       => "Brick or stone",
                                    "Composite wood Siding"  => "Wood",
                                ];

                                $service_data = [
                                    "siding" => [
                                        "siding_type"  => $siding_map[$Leaddatadetails['type_of_siding']] ?? "Other",
                                        "project_type" => (trim($Leaddatadetails['project_nature']) == "Repair section(s) of siding"
                                            ? "Siding repair"
                                            : "Replace siding"),
                                    ]
                                ];

                                $own_property = (trim($Leaddatadetails['homeOwn']) == "Yes");
                                $authToken = "556bce96-7811-4739-b2ce-8a0bd72e9a08";
                                break;
                            case 9: // BATHROOM
                                $bath_map = [
                                    "Full Remodel"   => "Full bathroom",
                                    "Shower / Bath"  => "Bathtub/Shower Updates",
                                    "Sinks"          => "Bath sinks",
                                ];

                                $service_data = [
                                    "bathroom" => [
                                        "project_type" => $bath_map[$Leaddatadetails['services']] ?? "Not Sure",
                                    ]
                                ];

                                $own_property = (trim($Leaddatadetails['homeOwn']) == "Yes");
                                $authToken = "e8ad25b7-3a32-4b9d-9b13-60733f60b46d";
                                break;
                        }

                        // 5) Final Ping Data
                        $Lead_data_array_ping = [
                            "data" => array_merge($service_data, [
                                "own_property"        => $own_property,
                                "best_call_time"      => "Anytime",
                                "purchase_time_frame" => "Immediately",
                                "credit_rating"       => "Good",
                            ]),
                            "meta"    => $base_meta,
                            "contact" => $base_contact
                        ];

                        // 6) Headers
                        $httpHeader = [
                            "Authorization: Token $authToken",
                            "Content-Type: application/json",
                            "Accept: application/json",
                        ];

                        $ping_crm_apis = array(
                            "url" => $url_api_ping,
                            "header" => $httpHeader,
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
                            $result = $crm_api_file->api_send_data($url_api_ping, $httpHeader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['status'])) {
                                if ($result2['status'] == "success") {
                                    $TransactionId = $result2['auth_code'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }

                        break;
                    case 43:
                        //Adopt A Contractor ========================
                        $numcategory = 0;
                        switch ($lead_type_service_id) {
                            case 1:
                                //Windows
                                $number_of_window = trim($Leaddatadetails['number_of_window']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                if ($project_nature != "Repair") {
                                    $numcategory = ($number_of_window == 1 ? "318" : "232");
                                } else {
                                    $numcategory = "299";
                                }
                                break;
                            case 2:
                                //Solar
                                $numcategory = "80";
                                break;
                            case 6:
                                //Roofing
                                $property_type = trim($Leaddatadetails['property_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $roof_type = trim($Leaddatadetails['roof_type']);

                                if ($property_type == "Commercial") {
                                    $numcategory = "205";
                                } else {
                                    switch ($roof_type) {
                                        case "Asphalt Roofing":
                                            switch ($project_nature) {
                                                case "Repair existing roof":
                                                    $numcategory = "265";
                                                    break;
                                                case "Completely replace roof":
                                                    $numcategory = "271";
                                                    break;
                                                default:
                                                    $numcategory = "259";
                                            }
                                            break;
                                        case "Wood Shake/Composite Roofing":
                                            switch ($project_nature) {
                                                case "Repair existing roof":
                                                    $numcategory = "269";
                                                    break;
                                                case "Completely replace roof":
                                                    $numcategory = "276";
                                                    break;
                                                default:
                                                    $numcategory = "264";
                                            }
                                            break;
                                        case "Metal Roofing":
                                            switch ($project_nature) {
                                                case "Repair existing roof":
                                                    $numcategory = "266";
                                                    break;
                                                case "Completely replace roof":
                                                    $numcategory = "273";
                                                    break;
                                                default:
                                                    $numcategory = "261";
                                            }
                                            break;
                                        case "Natural Slate Roofing":
                                            switch ($project_nature) {
                                                case "Repair existing roof":
                                                    $numcategory = "267";
                                                    break;
                                                case "Completely replace roof":
                                                    $numcategory = "274";
                                                    break;
                                                default:
                                                    $numcategory = "262";
                                            }
                                            break;
                                        case "Tile Roofing":
                                            switch ($project_nature) {
                                                case "Repair existing roof":
                                                    $numcategory = "268";
                                                    break;
                                                case "Completely replace roof":
                                                    $numcategory = "275";
                                                    break;
                                                default:
                                                    $numcategory = "263";
                                            }
                                            break;
                                    }
                                }
                                break;
                            case 7:
                                //Home Siding
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $numcategory = ($project_nature == "Repair" ? "297" : "23");
                                break;
                            case 9:
                                //Bathroom
                                $numcategory = "193";
                                break;
                        }

                        if (config('app.env', 'local') == "local") {
                            $key = "test_mode";
                            $google_ts = "match";
                            $partnerid = "THR";
                        } else {
                            $key = "a260f21d7bd315a350b387a7e72a9c7d";
                            $partnerid = "THR";
                        }


                        $trusted_form = substr($trusted_form, strrpos($trusted_form, '/') + 1);

                        $trusted_form_available = $universal_leadid_available = 0;
                        if (!empty($trusted_form)) {
                            $trusted_form_available = 1;
                        }
                        if (!empty($LeadId)) {
                            $universal_leadid_available = 1;
                        }

                        $url_api_ping = "http://api.letsmakealead.com/Ping_Partner.php?";
                        $Lead_data_ping = "";
                        $httpheader = array(
                            "cache-control: no-cache",
                            "Accept: application/json",
                            "content-type: application/json"
                        );

                        $TCPAText = urlencode($TCPAText);

                        $url_api_ping .= "partnerid=$partnerid&subid=$google_ts&key=$key&numcategory=$numcategory&zipcode=$zip&trusted_form_available=$trusted_form_available&universal_leadid_available=$universal_leadid_available&trusted_form_token=$trusted_form&universal_leadid=$LeadId&city=$city&ip=$IPAddress&tcpa_optin=$tcpa_compliant&tcpa_text=$TCPAText";

                        if ($lead_type_service_id == 2) {
                            $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                            $utility_provider = trim($Leaddatadetails['utility_provider']);

                            switch ($monthly_electric_bill) {
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

                            $url_api_ping .= "&average_monthly_electricity_bill=$average_bill&electricity_service_provider=$utility_provider";
                        }

                        if ($type == 2) {
                            $hash1 = md5($leadCustomer_id);
                            if ($numberOfCamp >= 3) {
                                $numberOfCamp = 3;
                                $hash1 .= "," . md5($leadCustomer_id + 2);
                            } else if ($numberOfCamp == 2) {
                                $hash1 .= "," . md5($leadCustomer_id + 1);
                            }

                            $url_api_ping .= "&shared=1&nb_legs_available=3&nb_legs_sold=$numberOfCamp&hash_legs_sold=$hash1";
                        }

                        $url_api_ping = str_replace(" ", "%20", $url_api_ping);

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
                            if (!empty($result)) {
                                if (strpos("-" . $result, 'ACCEPTED') == false) {
                                    $TransactionId = "";
                                    $Payout = 0;
                                    $Result = 0;
                                } else {
                                    $data = $result;
                                    $arr_explode = explode("<br>", $data);
                                    $Price = 0;
                                    $Token = "";
                                    if (!empty($arr_explode[1])) {
                                        $Token = trim(str_replace("Token:", "", $arr_explode[1]));
                                    }
                                    if (!empty($arr_explode[2])) {
                                        $Price = trim(str_replace("Price:", "", $arr_explode[2]));
                                    }

                                    $TransactionId = $Token;
                                    $Payout = $Price;
                                    $multi_type = 1;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 42:
                        //point to web 39
                        $url_api = "https://homeimprove.io/api/host-post/leads/ping";
                        $httpheader = array(
                            "Accept: application/json",
                            "Content-Type: application/json"
                        );

                        $Lead_data_array = array(
                            "external_id" => $leadCustomer_id,
                            "zip_code" => $zip,
                            "ip_address" => $IPAddress,
                            "user_agent" => $UserAgent,
                            "leadid_tcpa_disclosure" => $TCPAText,
                            "trustedform_cert_url" => $trusted_form,
                            "seconds_on_landing" => $SessionLength ?? "40",
                            "source" => "thv".$google_ts,
                        );

                        switch ($lead_type_service_id){
                            case 1:
                                //Window
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                $homeowner = ($ownership == "Yes" ? "yes" : "no");
                                $ProjectType = ($project_nature == "Repair" ? "Windows Repair" : "Windows Install");

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
                                        $number_of_windows_data = "more";
                                }

                                $Lead_data_array['project_type'] = $ProjectType;
                                $Lead_data_array['vertical'] = "window";
                                $Lead_data_array['home_owner'] = $homeowner;
                                $Lead_data_array['number_of_windows'] = $number_of_windows_data;
                                $Lead_data_array['landing_page_url'] = "https://thewindowsinstall.com/Quote?ts=thv$google_ts";

                                $httpheader[] = "X-API-Key: MTZfd2luZG93XzE3NjQ3NTk3MjlfNjkz";
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $property_type = trim($Leaddatadetails['property_type']);

                                switch ($Type_OfRoofing) {
                                    case "Asphalt Roofing":
                                        $roofmaterial = "Asphalt Shingle";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roofmaterial = "wood";
                                        break;
                                    case "Natural Slate Roofing":
                                        $roofmaterial = "Natural Slate";
                                        break;
                                    case "Tile Roofing":
                                        $roofmaterial = "Tile";
                                        break;
                                    default:
                                        $roofmaterial = "Flat/Single Ply";
                                }

                                $ProjectType = ($project_nature == "Repair existing roof" ? "Roof Repair" : "Roof Install");

                                $Lead_data_array['project_type'] = $ProjectType;
                                $Lead_data_array['vertical'] = "roof";
                                $Lead_data_array['home_owner'] = "yes";
                                $Lead_data_array['roofing_type'] = $roofmaterial;
                                $Lead_data_array['landing_page_url'] = "https://homeremodelingpro.net/Quote?ts=thv$google_ts";

                                $httpheader[] = "X-API-Key: MTZfcm9vZl8xNzY0NzU5Nzg1XzY5MzAx";
                                break;
                            case 9:
                                //Bathroom
                                $bathroom_type_name = trim($Leaddatadetails['services']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $homeowner = ($ownership == "Yes" ? "yes" : "no");

                                $Lead_data_array['home_owner'] = $homeowner;
                                $Lead_data_array['vertical'] = "bath-remodel";
                                $Lead_data_array['remodel_walls'] = "yes";
                                $Lead_data_array['landing_page_url'] = "https://thebathroomremodel.net/Quote?ts=thv$google_ts";
                                $httpheader[] = "X-API-Key: MTZfYmF0aC1yZW1vZGVsXzE3NjQ3NTk2";
                                break;
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
                            if (!empty($result2['success'])) {
                                if ($result2['success'] == "true") {
                                    $TransactionId = $result2['data']['ping_id'];
                                    $Payout = $result2['data']['max_bid'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 47:
                        // Modernize

                        if( config('app.env', 'local') == "local" || !empty($data_msg['is_test']) ) {
                            //Test Mode
                            $url_api = "https://hsapiservice.quinstage.com/ping-post/pings";
                        }else{
                            $url_api = "https://form-service-hs.qnst.com/ping-post/pings";
                        }

                        $httpheader = array(
                            "content-type: application/json",
                        );

                        $tagId = "204692795";

                        $Lead_data_array = array(
                            "tagId" => $tagId,
                            "postalCode" => $zip,
                            "partnerSourceId" => "THV1$google_ts" ,
                            "publisherSubId" => "THV1" ,
                        );

                        switch ($lead_type_service_id){
                            case 9:
                                // Bathroom
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");
                                $bathroom_type_name = trim($Leaddatadetails['services']);

                                switch ($bathroom_type_name){
                                    case "Shower / Bath":
                                        $bathroom_type_name_data = "BATHROOM_REFACING";
                                        break;
                                    default:
                                        $bathroom_type_name_data = "BATH_REMODEL";
                                }
                                $Lead_data_array['ownHome'] = $homeowner;
                                $Lead_data_array['service'] = $bathroom_type_name_data;
                                $Lead_data_array['OptIn1'] = "No";
                                $Lead_data_array['buyTimeframe'] = "Don't know";
                                break;
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
                            "crm_type" => 0,
                            "trustedFormToken" => $trusted_form,
                        );

                        if($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2)) {
                                if ($result2['status'] == "success"){
                                    $TransactionId = $result2['pingToken'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 49:
                        // 1302	HomeYou
                        $url_api = "https://api.wiserleads.com/services/ping";

                        $httpheader = array(
                            "content-type: application/json",
                            "Accept: application/json"
                        );

                        $campaignToken = "6bfb05308c3a88f7a71c3a3d029b3088f60fe0bc";
                        $campaignCode = "thryvea-llc-pingpost";

                        $Lead_data_array = array(
                            "campaign" => $campaignCode,
                            "campaign_token" => $campaignToken,
                            "zipcode" => $zip,
                            "source" => "THV1",//$lead_source_text,
                            "certification_type" => "TrustedForm",
                            "tcpa_consent" => "Yes",
                            "redirect_url" => "No",
                            "test" => "false",

                        );

                        switch ($lead_type_service_id){
                            case 1:
                                // Windows
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);

                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                switch ($number_of_windows){
                                    case '1':
                                        switch ($project_nature) {
                                            case "Repair":
                                                $project_type = "Repair";
                                                break;
                                            default:
                                                $project_type = "Install";
                                        }
                                        $number_windows = "1";
                                        break;
                                    case '2':
                                        switch ($project_nature) {
                                            case "Repair":
                                                $project_type = "Repair";
                                                break;
                                            default:
                                                $project_type = "Install";
                                        }
                                        $number_windows = "2";
                                        break;
                                    case "3-5":
                                        switch ($project_nature) {
                                            case "Repair":
                                                $project_type = "Repair";
                                                break;
                                            default:
                                                $project_type = "Install";
                                        }
                                        $number_windows = "3-5";
                                        break;
                                    case "6-9":
                                    default:
                                        switch ($project_nature) {
                                            case "Repair":
                                                $project_type = "Repair";
                                                break;
                                            default:
                                                $project_type = "Install";
                                        }
                                        $number_windows = "6-9";
                                        break;
                                }

                                $Lead_data_array['ownhome'] = $homeowner;
                                $Lead_data_array['service_code'] = "WINDOWS";
                                $Lead_data_array['NumberOfWindows'] = $number_windows;
                                $Lead_data_array['WindowsProjectScope'] = $project_type;
                                $Lead_data_array['WindowMaterial'] = "Vinyl";
                                break;
                            case 2:
                                // Solar
                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);
                                $power_solution = trim($Leaddatadetails['power_solution']);

                                $homeowner = ($property_type == "Rented" ? "No" : "Yes");

                                switch ($monthly_electric_bill){
                                    case '$0 - $50':
                                    case '$51 - $100':
                                        $average_bill = "less_than_100";
                                        break;
                                    case '$101 - $150':
                                    case '$151 - $200':
                                        $average_bill = "from_100_to_200";
                                        break;
                                    case '$201 - $300':
                                        $average_bill = "from_200_to_300";
                                        break;
                                    default:
                                        $average_bill = "more_than_300";
                                }

                                $Lead_data_array['ownhome'] = $homeowner;
                                $Lead_data_array['service_code'] = "SOLAR";
                                $Lead_data_array['monthly_bill'] = $average_bill;
                                $Lead_data_array['description'] = "Requested Solar Solution: $power_solution, Utility Provider: $utility_provider, Roof Shade: $roof_shade";
                                $Lead_data_array['house_size'] = "";
                                break;
                            case 4:
                                // Flooring
                                $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                switch ($Type_OfFlooring) {
                                    case "Vinyl Linoleum Flooring":
                                        $serviceCode = "FLOORING_VINYL_LINOLEUM";
                                        switch ($project_nature){
                                            case "Install New Flooring":
                                                $project_type = " Install";
                                                break;
                                            default:
                                                $project_type = "Repair";
                                        }
                                        break;
                                    case "Tile Flooring":
                                        $serviceCode = "FLOORING_TILE";
                                        switch ($project_nature){
                                            case "Install New Flooring":
                                                $project_type = "Install";
                                                break;
                                            default:
                                                $project_type = "Repair";
                                        }
                                        break;
                                    case "Hardwood Flooring":
                                        $serviceCode = "FLOORING_HARDWOOD";
                                        switch ($project_nature){
                                            case "Install New Flooring":
                                                $project_type = "Install";
                                                break;
                                            case "Refinish Existing Flooring":
                                                $project_type = "Refinishing";
                                                break;
                                            default:
                                                $project_type = "Repair";
                                        }
                                        $Lead_data_array['MaterialPurchase'] = "No";
                                        break;
                                    case "Carpet":
                                        switch ($project_nature){
                                            case "Install New Flooring":
                                                $project_type = "Install";
                                                $serviceCode = "CARPET";
                                                break;
                                            default:
                                                $serviceCode = "CARPET_REPAIR";
                                                $project_type = "Repair";
                                        }
                                        break;
                                    default:
                                        $serviceCode = "FLOORING_LAMINATE";
                                        switch ($project_nature){
                                            case "Install New Flooring":
                                                $project_type = "Install";
                                                break;
                                            default:
                                                $project_type = "Repair";
                                        }
                                        $Lead_data_array['MaterialPurchase'] = "No";
                                }

                                $homeowner = ($ownership == 'Yes' ? "Yes" : "No");


                                $Lead_data_array['ownhome'] = $homeowner;
                                $Lead_data_array['service_code'] = $serviceCode;
                                $Lead_data_array['CommercialLocation'] = "Home";
                                $Lead_data_array['FlooringProjectScope'] = $project_type;
                                break;
                            case 6:
                                // Roofing
                                $roof_type = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $property_type = trim($Leaddatadetails['property_type']);

                                $residential = ($property_type == "Residential" ? "Yes" : "No");

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $serviceCode = "ROOFING_ASPHALT";
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                            case "Completely replace roof":
                                                $project_nature_data = "Completely replace roof";
                                                break;
                                            default:
                                                $project_nature_data = "Repair existing roof";
                                        }
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $serviceCode = "ROOFING_COMPOSITE";
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                                $project_nature_data = "Install roof on new construction";
                                                break;
                                            case "Completely replace roof":
                                                $project_nature_data = "Completely replace roof";
                                                break;
                                            default:
                                                $project_nature_data = "Repair existing roof";
                                        }
                                        break;
                                    case "Metal Roofing":
                                        $serviceCode = "ROOFING_METAL";
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                                $project_nature_data = "Install new roof";
                                                break;
                                            case "Completely replace roof":
                                                $project_nature_data = "Replace existing roof";
                                                break;
                                            default:
                                                $project_nature_data = "Repair existing roof";
                                        }
                                        break;
                                    case "Natural Slate Roofing":
                                        $serviceCode = "ROOFING_NATURAL_SLATE";
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                                $project_nature_data = "Install roof on new construction";
                                                break;
                                            case "Completely replace roof":
                                                $project_nature_data = "Completely replace roof";
                                                break;
                                            default:
                                                $project_nature_data = "Repair existing roof";
                                        }
                                        break;
                                    default:
                                        $serviceCode = "ROOFING_TILE";
                                        switch ($project_nature){
                                            case "Install roof on new construction":
                                                $project_nature_data = "Install roof on new construction";
                                                break;
                                            case "Completely replace roof":
                                                $project_nature_data = "Completely replace roof";
                                                break;
                                            default:
                                                $project_nature_data = "Repair existing roof";
                                        }
                                }

                                $Lead_data_array['ownhome'] = $residential;
                                $Lead_data_array['service_code'] = $serviceCode;
                                $Lead_data_array['RoofingPlan'] = $project_nature_data;
                                break;
                            case 7:
                                //Home Siding
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                $SecurityUsage = ($ownership == "Yes" ? "Yes" : "No");
                                $project_nature_data = ($project_nature == "Repair section(s) of siding" ? "Repair section(s) of siding" : "Siding for a new home");

                                switch($type_of_siding){
                                    case "Vinyl Siding":
                                        $service_code = "SIDING_VINYL";
                                        break;
                                    case "Brickface Siding":
                                        $service_code = "SIDING_BRICKFACE";
                                        break;
                                    case "Stoneface Siding":
                                        $service_code = "SIDING_STONEFACE";
                                        break;
                                    case "Composite wood Siding":
                                        $service_code = "SIDING_COMPOSITE_WOOD";
                                        break;
                                    default:
                                        $service_code = "SIDING_ALUMINIUM";
                                }

                                $Lead_data_array['ownhome'] = $SecurityUsage;
                                $Lead_data_array['service_code'] = $service_code;
                                $Lead_data_array['ProjectPlan'] = $project_nature_data;
                                break;
                            case 9:
                                // Bathroom
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $homeowner = ($ownership == "Yes" ? "Yes" : "No");

                                $Lead_data_array['ownhome'] = $homeowner;
                                $Lead_data_array['service_code'] = "BATH_REMODEL";
                                $Lead_data_array['OptIn1'] = "No";
                                break;
                        }

                        $Lead_data_array['status'] = "Planning & Budgeting";
                        $Lead_data_array['timeframe'] = "Timing is Flexible";

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
                            if (!empty($result2)) {
                                if ($result2['success'] == "true"){
                                    $TransactionId = $result2['lead_token'];
                                    $Payout = $result2['payout'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 52:
                        //Billy.com	536
                        if ($trusted_form == "NA" || $trusted_form == "N/A"
                            || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                            || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                            || $trusted_form == "https://cert.trustedform.com"
                            || empty($LeadId) || empty($trusted_form) ) {
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

                        $property_type_data = "UNKNOWN";
                        switch ($lead_type_service_id){
                            case 1:
                                //Windows
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                $apiId = "CB6B8D7AA4604F23BADF9FCC7E530E39";
                                $apiPassword = "2290628";

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                if ($project_nature == "Repair") {
                                    $taskId = "WINDOW_REPAIR";
                                }
                                else {
                                    $taskId = ($number_of_windows == 1 ? "WINDOW_INSTALL_SINGLE" : "WINDOWS_INSTALL_MULTIPLE");
                                }

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 268,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 2:
                                //Solar
                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);
                                $power_solution = trim($Leaddatadetails['power_solution']);

                                $apiId = "C32EA063999E4F389D88C4F473D3DF7B";
                                $apiPassword = "650878a08";


                                switch ($monthly_electric_bill){
                                    case '$0 - $50' || '$51 - $100':
                                        $average_bill = 'Below100';
                                        break;
                                    case '$101 - $150':
                                        $average_bill = 'Above100Below150';
                                        break;
                                    case '$151 - $200':
                                        $average_bill = 'Above150Below200';
                                        break;
                                    case '$201 - $300':
                                        $average_bill = 'Above250Below300';
                                        break;
                                    default:
                                        $average_bill = 'Above350';
                                }

                                switch ($property_type){
                                    case "Business":
                                        $homeowner = "Unknown";
                                        $property_type_data = "Business";
                                        break;
                                    case "Rented":
                                        $homeowner = "Rent";
                                        break;
                                    default:
                                        $homeowner = "Own";
                                }

                                switch ($roof_shade){
                                    case "Full Sun":
                                        $roof_shade_data = "Full";
                                        break;
                                    case "Mostly Shaded":
                                        $roof_shade_data = "Shaded";
                                        break;
                                    case "Partial Sun":
                                        $roof_shade_data = "Partial";
                                        break;
                                    default:
                                        $roof_shade_data = "Unknown";
                                }

                                $taskId = "SL01";

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 265,
                                    "residenceOwnership" => $homeowner,
                                    "sunExposure" => $roof_shade_data,
                                    "averageBill" => $average_bill,
                                    "taskId" => $taskId
                                );
                                break;
                            case 4:
                                //Flooring
                                $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "11714E54BA2E4DED8EDD6F9ED49645A7";
                                    $apiPassword = "53194403";
                                } else {
                                    $apiId = "B66256A0C87A4AED9E46C3D0E680E06C";
                                    $apiPassword = "b7fa088";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $taskId = ($project_nature == "Repair Existing Flooring" ? "VINYL_OR_LINOLEUM_FLOORING_REPAIR" : "VINYL_OR_LINOLEUM_FLOORING_INSTALL");
                                        break;
                                    case "Tile Flooring":
                                        $taskId = ($project_nature == "Repair Existing Flooring" ? "TILE_FLOORING_REPAIR" : "TILE_FLOORING_INSTALL");
                                        break;
                                    case "Hardwood Flooring":
                                        $taskId = ($project_nature == "Repair Existing Flooring" ? "WOOD_FLOOR_REPAIR" : "HARDWOOD_FLOOR_INSTALL");
                                        break;
                                    case "Laminate Flooring":
                                        $taskId = ($project_nature == "Repair Existing Flooring" ? "LAMINATE_FLOORING_REPAIR" : "LAMINATE_FLOORING_INSTALL");
                                        break;
                                    default:
                                        $taskId = ($project_nature == "Repair Existing Flooring" ? "CARPET_REPAIR" : "CARPET_INSTALL");
                                }

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 296,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 5:
                                //Walk-In Tubs
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "E613988B6DAD46999BC87DDCD96B598B";
                                    $apiPassword = "6272f2461";
                                } else {
                                    $apiId = "6A06932627054925BE702BF5F054B3FA";
                                    $apiPassword = "3da6e4a6";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $taskId = "BATHROOM_REMODEL_WALK_IN_TUB_INSTALLATION_OR_CONVERSION";
                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 272,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 6:
                                //Roofing
                                $roof_type = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $property_type = trim($Leaddatadetails['property_type']);

                                $apiId = "6746244027444005B30D7593F9F708F7";
                                $apiPassword = "8676b50263";

                                $property_type_data = ($property_type == "Residential" ? "RESIDENTIAL" : "BUSINESS");
                                $roofJob = ($project_nature == "Repair existing roof" ? "ROOFREPAIR" : "ROOFINSTALL");

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $taskId = ($project_nature == "Repair existing roof" ? "ROOF_REPAIR_ASPHALT_SHINGLE" : "ROOF_INSTALL_ASPHALT_SHINGLE");
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $taskId = ($project_nature == "Repair existing roof" ? "ROOF_REPAIR_WOOD_SHAKE_COMP" : "ROOF_INSTALL_WOOD_SHAKE_COMP");
                                        break;
                                    case "Metal Roofing":
                                        $taskId = ($project_nature == "Repair existing roof" ? "ROOF_REPAIR_METAL" : "ROOF_INSTALL_METAL");
                                        break;
                                    case "Natural Slate Roofing":
                                        $taskId = ($project_nature == "Repair existing roof" ? "ROOF_REPAIR_NATURAL_SLATE" : "ROOF_INSTALL_NATURAL_SLATE");
                                        break;
                                    default:
                                        $taskId = ($project_nature == "Repair existing roof" ? "ROOF_REPAIR_TILE" : "ROOF_INSTALL_TILE");
                                }

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 267,
                                    "residenceOwnership" => "UNKNOWN",
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "roofJob" => $roofJob,
                                    "taskId" => $taskId,
                                );
                                break;
                            case 7:
                                //Home Siding
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                $apiId = "896E4AFE01EA464D850CEA8938CF5C94";
                                $apiPassword = "d905dd37a";

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $taskId = ($project_nature == "Repair section(s) of siding" ? "VINYL_REPAIR" : "VINYL_INSTALL");
                                        break;
                                    case "Brickface Siding":
                                    case "Stoneface Siding":
                                        $taskId = ($project_nature == "Repair section(s) of siding" ? "BRICK_OR_STONE_REPAIR" : "BRICK_OR_STONE_INSTALL");
                                        break;
                                    case "Composite wood Siding":
                                        $taskId = ($project_nature == "Repair section(s) of siding" ? "WOOD_REPAIR" : "WOOD_INSTALL");
                                        break;
                                    default:
                                        $taskId = ($project_nature == "Repair section(s) of siding" ? "OTHER_REPAIR" : "OTHER_INSTALL");
                                }

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 270,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 8:
                                //Kitchen
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "E613988B6DAD46999BC87DDCD96B598B";
                                    $apiPassword = "6272f2461";
                                } else {
                                    $apiId = "6A06932627054925BE702BF5F054B3FA";
                                    $apiPassword = "3da6e4a6";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $taskId = "KITCHEN_REMODEL_FULL_REMODEL";
                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 272,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 9:
                                //Bathroom
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $bathroom_type = trim($Leaddatadetails['services']);

                                $apiId = "1341F28CA037414E8BEC279ED3D7E565";
                                $apiPassword = "17d6deb596";

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                switch ($bathroom_type) {
                                    case "Full Remodel":
                                        $taskId = "BATHROOM_REMODEL_COMPLETE_REMODEL";
                                        break;
                                    case "Shower / Bath":
                                        $taskId = "BATHROOM_REMODEL_SHOWER_INSTALL_OR_UPGRADE";
                                        break;
                                    default:
                                        $taskId = "BATHROOM_REMODEL_VANITY_FIXTURES_SINKS_TOILETS_OTHER";
                                }

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 272,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 11:
                                //Furnace
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "DDCADDE8EA3243A0AE74773FEDF22E38";
                                    $apiPassword = "780653716";
                                } else {
                                    $apiId = "3328BA95151844CD8C5C7392AA7C1631";
                                    $apiPassword = "b403f7e7";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $taskId = ($project_nature == "Repair" ? "FURNACE_HEATING_SYSTEM_REPAIR" : "FURNACE_HEATING_SYSTEM_INSTALL");
                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 295,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 12:
                                //Boiler
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "DDCADDE8EA3243A0AE74773FEDF22E38";
                                    $apiPassword = "780653716";
                                } else {
                                    $apiId = "3328BA95151844CD8C5C7392AA7C1631";
                                    $apiPassword = "b403f7e7";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $taskId = ($project_nature == "Repair" ? "BOILER_REPAIR" : "BOILER_INSTALL");
                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 295,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 13:
                                //Central A/C
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "DDCADDE8EA3243A0AE74773FEDF22E38";
                                    $apiPassword = "780653716";
                                } else {
                                    $apiId = "3328BA95151844CD8C5C7392AA7C1631";
                                    $apiPassword = "b403f7e7";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                $taskId = ($project_nature == "Repair" ? "CENTRAL_AC_REPAIR" : "CENTRAL_AC_INSTALL");
                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 295,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                            case 21:
                                //Gutter
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $service = trim($Leaddatadetails['service']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                if (str_contains($campaign_name, 'Affiliate')){
                                    $apiId = "30C0192829814AF5A457400DE0BBC199";
                                    $apiPassword = "ad3afb64";
                                } else {
                                    $apiId = "BE4E358AC8F540E7AEFA9CCC7F99F5A1";
                                    $apiPassword = "3aa5b7a9";
                                }

                                switch ($start_time){
                                    case 'Immediately':
                                        $timeframe = "IMMEDIATELY";
                                        break;
                                    case 'Within 6 months':
                                    default:
                                        $timeframe = "1-3_MONTHS";
                                }

                                switch ($service){
                                    case "Galvanized Steel":
                                        $taskId = ($project_nature == "Repair" ? "GALVANIZED_GUTTERS_REPAIR" : "GALVANIZED_GUTTERS_INSTALL");
                                        break;
                                    case "PVC":
                                        $taskId = ($project_nature == "Repair" ? "PVC_GUTTERS_REPAIR" : "PVC_GUTTERS_INSTALL");
                                        break;
                                    case "Wood":
                                        $taskId = ($project_nature == "Repair" ? "WOOD_GUTTERS_REPAIR" : "WOOD_GUTTERS_INSTALL");
                                        break;
                                    case "Seamless Aluminum":
                                    default:
                                        $taskId = ($project_nature == "Repair" ? "SEAMLESS_METAL_GUTTERS_REPAIR" : "SEAMLESS_METAL_GUTTERS_INSTALL");
                                }

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                $Lead_data_array_service = array(
                                    "apiId" => $apiId,
                                    "apiPassword" => $apiPassword,
                                    "productId" => 271,
                                    "residenceOwnership" => $homeowner,
                                    "bestTimeToCall" => "ANYTIME",
                                    "timeframe" => $timeframe,
                                    "taskId" => $taskId
                                );
                                break;
                        }
                        if ($tcpa_compliant2 = "No"){
                            $TCPAText = "UNKNOWN";
                        }
                        $Lead_data_array_general = array(
                            "state" => $statename_code,
                            "zip" => $zip,
                            "tcpa" => $tcpa_compliant2,
                            "landingPage" => $OriginalURL2,
                            "city" => $city,
                            "ipAddress" => $IPAddress,
                            "tcpaText" => $TCPAText,
                            "jornayaLeadId" => $LeadId,
                            "trustedFormURL" => $trusted_form,
                            "userAgent" => $UserAgent,
                            "source" => $google_ts,
                            "propertyType" => $property_type_data
                        );

                        $Lead_data_array_ping = array_merge($Lead_data_array_general, $Lead_data_array_service);

//                        if (config('app.env', 'local') == "local") {
//                            //Test Mode
//                            $Lead_data_array_ping['testMode'] = "1";
//                        }

                        $url_api = "https://leads.billy-partners.com/ping/";
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
                            if (!empty($result2['status'])) {
                                if ($result2['status'] == "continue") {
                                    $TransactionId = $result2['promise'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                        }
                        break;
                    case 53:
                        // ecrux 660
                        $url_api = "https://leadvantage.co/api/287ebb39c711d47b00ccd35f5b232ed6a459/";
                        $httpheader = array(
                            "Accept: application/json",
                            "Content-Type: application/x-www-form-urlencoded"
                        );
                        $Lead_data_array_ping = array(
                            'SRC' => 'THV1',
                            'zip' => $zip,
                            'TCPAConsent' => $tcpa_compliant,
                            'TCPAConsentLanguage' => $TCPAText,
                        );
                        if (config('app.env', 'local') == "local") {
                            //Test Mode
                            $Lead_data_array_ping['Test_Lead'] = "1";
                        }

                        switch ($lead_type_service_id){
                            case 1:
                                //windows
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $project_natureWindows = trim($Leaddatadetails['project_nature']);

                                $Lead_data_array_ping['Home_Improvement_Product'] = "Windows";
                                $Lead_data_array_ping['subcat'] = "Windows - Window Installation";
                                $Lead_data_array_ping['No_Of_Windows'] = $number_of_windows;
                                $Lead_data_array_ping['WindowMaterial'] = "NA";
                                break;
                            case 4:
                                // flooring
                                $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);

                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $flooring_typesubcat = "Flooring - Vinyl";
                                        break;
                                    case "Hardwood Flooring":
                                        $flooring_typesubcat = "Flooring - Hardwood";
                                        break;
                                    case "Carpet":
                                        $flooring_typesubcat = "Flooring - Carpet";
                                        break;
                                    case "Laminate Flooring":
                                        $flooring_typesubcat = "Flooring - Laminate";
                                        break;
                                    case "Tile Flooring":
                                    default:
                                        $flooring_typesubcat = "Flooring - Tile";

                                }
                                $Lead_data_array_ping['Home_Improvement_Product'] = "Flooring";
                                $Lead_data_array_ping['subcat'] = $flooring_typesubcat;
                                break;
                            case 6:
                                //roofing
                                $project_natureRoofing = trim($Leaddatadetails['project_nature']);
                                $roof_type = trim($Leaddatadetails['roof_type']);
                                switch ($project_natureRoofing){
                                    case "Repair existing roof":
                                        $roof_type_SubCat = "Roof Repair";
                                        break;
                                    default:
                                        switch ($roof_type){
                                            case "Asphalt Roofing":
                                                $roof_type_SubCat = "Asphalt Shingle Roof Installation";
                                                break;
                                            case "Metal Roofing":
                                                $roof_type_SubCat = "Metal Roof Installation";
                                                break;
                                            case "Natural Slate Roofing":
                                                $roof_type_SubCat = "Slate Roof Installation";
                                                break;
                                            case "Tile Roofing":
                                                $roof_type_SubCat = "Tile Roof Installation";
                                                break;
                                            case "Wood Shake/Composite Roofing":
                                            default:
                                                $roof_type_SubCat = "Composite Shingle Roof Installation";
                                        }
                                }
                                $Lead_data_array_ping['Home_Improvement_Product'] = "Roofing";
                                $Lead_data_array_ping['subcat'] = "Roofing - ".$roof_type_SubCat;
                                $Lead_data_array_ping['JobType'] = $project_natureRoofing;
                                break;
                            case 7:
                                //Home Siding
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                $homeowner = ($ownership == "Yes" ? "OWN" : "RENT");

                                switch ($type_of_siding){
                                    case "Vinyl Siding":
                                        $subcat = ($project_nature == "Repair section(s) of siding" ? "Siding - Siding Repair" : "Siding - Vinyl Siding Installation");
                                        break;
                                    case "Brickface Siding":
                                    case "Stoneface Siding":
                                        $subcat = ($project_nature == "Repair section(s) of siding" ? "Siding - Siding Repair" : "Siding - Brick/Stone Siding Installation");
                                        break;
                                    case "Composite wood Siding":
                                        $subcat = ($project_nature == "Repair section(s) of siding" ? "Siding - Siding Repair" : "Siding - Wood Siding Installation");
                                        break;
                                    case "Aluminium Siding":
                                        $subcat = ($project_nature == "Repair section(s) of siding" ? "Siding - Siding Repair" : "Siding - Aluminum Siding Installation");
                                        break;
                                    default:
                                        $subcat = ($project_nature == "Repair section(s) of siding" ? "Siding - Siding Repair" : "Siding - Metal Siding Installation");
                                }

                                $Lead_data_array_ping['Home_Improvement_Product'] = "Siding";
                                $Lead_data_array_ping['subcat'] = $subcat;
                                break;
                            case 8:
                                //kitchen
                                $service_kitchen = trim($Leaddatadetails['services']);
                                $servicefloorPanKitchen = "No";
                                $serviceCabinetsKitchen = "No";
                                $serviceSinksKitchen = "No";
                                $serviceCounertopsKitchen = "No";
                                $servicefloorKitchen = "No";
                                $serviceLightingKitchen = "No";
                                switch ($service_kitchen){
                                    case "Full Kitchen Remodeling":
                                        $servicefloorPanKitchen = "Yes";
                                        $serviceCabinetsKitchen = "Yes";
                                        $serviceSinksKitchen = "Yes";
                                        $serviceCounertopsKitchen = "Yes";
                                        $servicefloorKitchen = "Yes";
                                        $serviceLightingKitchen = "Yes";
                                        break;
                                    case "Cabinet Refacing":
                                    case "Cabinet Install":
                                        $serviceCabinetsKitchen = "Yes";
                                        break;
                                }

                                $Lead_data_array_ping['Home_Improvement_Product'] = "Remodels";
                                $Lead_data_array_ping['subcat'] = "Remodels - Kitchen Remodel";
                                $Lead_data_array_ping['Change_Kitchen_Floorpan'] = $servicefloorPanKitchen;
                                $Lead_data_array_ping['Change_Kitchen_Cabinets'] = $serviceCabinetsKitchen;
                                $Lead_data_array_ping['Move_Kitchen_Appliances'] = "NA";
                                $Lead_data_array_ping['Change_Kitchen_Sinks'] = $serviceSinksKitchen;
                                $Lead_data_array_ping['Change_Kitchen_Counertops'] = $serviceCounertopsKitchen;
                                $Lead_data_array_ping['Change_Kitchen_Flooring'] = $servicefloorKitchen;
                                $Lead_data_array_ping['Change_Kitchen_Lighting'] = $serviceLightingKitchen;
                                break;
                            case 9:
                                //Bathroom
                                $bathroom_type_name = trim($Leaddatadetails['services']);
                                $serviceBathroom_Floorplan = "No";
                                $serviceBathroom_Shower_Bath = "No";
                                $serviceBathroom_Toilet = "No";
                                $serviceBathroom_Cabinets = "No";
                                $serviceBathroom_Countertops = "No";
                                $serviceBathroom_Sinks = "No";
                                $serviceBathroom_Flooring = "No";
                                switch ($bathroom_type_name){
                                    case "Flooring":
                                        $serviceBathroom_Floorplan = "Yes";
                                        break;
                                    case "Shower / Bath":
                                        $serviceBathroom_Shower_Bath = "Yes";
                                        break;
                                    case "Sinks":
                                        $serviceBathroom_Sinks = "Yes";
                                        break;
                                    case "Toilet":
                                        $serviceBathroom_Toilet = "Yes";
                                        break;
                                }
                                $Lead_data_array_ping['Home_Improvement_Product'] = "Remodels";
                                $Lead_data_array_ping['subcat'] = "Remodels - Bathroom Remodel";
                                $Lead_data_array_ping['Change_Bathroom_Floorplan'] = $serviceBathroom_Floorplan;
                                $Lead_data_array_ping['Change_Bathroom_Shower_Bath'] = $serviceBathroom_Shower_Bath;
                                $Lead_data_array_ping['Change_Bathroom_Toilet'] = $serviceBathroom_Toilet;
                                $Lead_data_array_ping['Change_Bathroom_Cabinets'] = $serviceBathroom_Cabinets;
                                $Lead_data_array_ping['Change_Bathroom_Countertops'] = $serviceBathroom_Countertops;
                                $Lead_data_array_ping['Change_Bathroom_Sinks'] = $serviceBathroom_Sinks;
                                $Lead_data_array_ping['Change_Bathroom_Flooring'] = $serviceBathroom_Flooring;
                                break;
                        }

                        $ping_crm_apis = array(
                            "url" => $url_api,
                            "header" => $httpheader,
                            "lead_id" => $leadCustomer_id,
                            "inputs" => http_build_query($Lead_data_array_ping),
                            "method" => "POST",
                            "campaign_id" => $campaign_id,
                            "service_id" => $lead_type_service_id,
                            "user_id" => $user_id,
                            "returns_data" => $returns_data,
                            "crm_type" => 0
                        );

                        if($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, http_build_query($Lead_data_array_ping), "POST", $returns_data, $campaign_id);
                            if (str_contains(strtolower($result), "success")) {
                                $bidLeadIDExctract = explode("|", $result);
                                $leadIDecrux = $bidLeadIDExctract[1];
                                $bid = $bidLeadIDExctract[2];
                                $TransactionId = $leadIDecrux;
                                $Payout = $bid;
                                $multi_type = 0;
                                $Result = 1;
                            }
                        }
                        break;
                    case 54:
                        //Brand Genius
                        $httpheader = array(
                            "cache-control: no-cache",
                            "Accept: application/json",
                            "content-type: application/json"
                        );

                        $Lead_data_array_ping = array(
                            "userIp" => $IPAddress,
                            "webSiteUrl" => $OriginalURL2,
                            "bg_city" => $city,
                            "bg_state" => $statename_code,
                            "bg_user_agent" => $UserAgent,
                            "bg_postal_code" => $zip
                        );

                        switch ($lead_type_service_id){
                            case 1:
                                //Windows
                                $apiId = "E3F2562F62514667BD9AA3FC871EB6E6";
                                $apiPassword = "1f51cc22";
                                $productId = "295";

                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $number_of_windows = trim($Leaddatadetails['number_of_window']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                switch ($number_of_windows) {
                                    case "1":
                                    case "2":
                                    case "3-5":
                                        $wndw_number = "3-5";
                                        break;
                                    case "6-9":
                                        $wndw_number = "6-9";
                                        break;
                                    default:
                                        $wndw_number = "10+";
                                }

                                $Lead_data_array_ping["apiId"] = $apiId;
                                $Lead_data_array_ping["apiPassword"] = $apiPassword;
                                $Lead_data_array_ping["productId"] = $productId;
                                $Lead_data_array_ping["bg_ownhome"] = $homeowner;
                                $Lead_data_array_ping["bg_service"] = "Other";
                                $Lead_data_array_ping["bg_buytimeframe"] = "Immediately";
                                $Lead_data_array_ping["bg_how_many_windows_are_involved"] = $wndw_number;

                                break;
                            case 4:
                                //Flooring
                                $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                $apiId = "9D770B4F1B344D9BBC9C10CE2AAA7FFB";
                                $apiPassword = "702500790";
                                $productId = "300";

                                switch ($Type_OfFlooring){
                                    case "Vinyl Linoleum Flooring":
                                        $typeOfFlooring = "Linoleum";
                                        break;
                                    case "Tile Flooring":
                                        $typeOfFlooring = "Tile";
                                        break;
                                    case "Hardwood Flooring":
                                        $typeOfFlooring = "Hardwood";
                                        break;
                                    case "Laminate Flooring":
                                        $typeOfFlooring = "Laminate";
                                        break;
                                    default:
                                        $typeOfFlooring = "Carpet";
                                }

                                switch ($start_time) {
                                    case 'Immediately':
                                        $Timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $Timeframe = "6 Months";
                                        break;
                                    default:
                                        $Timeframe = "Exploring";
                                }

                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $Lead_data_array_ping["apiId"] = $apiId;
                                $Lead_data_array_ping["apiPassword"] = $apiPassword;
                                $Lead_data_array_ping["productId"] = $productId;
                                $Lead_data_array_ping["bg_ownhome"] = $homeowner;
                                $Lead_data_array_ping["bg_service"] = $typeOfFlooring;
                                $Lead_data_array_ping["bg_buytimeframe"] = $Timeframe;

                                break;
                            case 6:
                                //Roofing
                                $roof_type = trim($Leaddatadetails['roof_type']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $property_type = trim($Leaddatadetails['property_type']);

                                $residential = ($property_type == "Residential" ? "YES" : "NO");

                                $apiId = "29F2250460A6467B8982F29D44EA23E5";
                                $apiPassword = "e17d229";
                                $productId = "299";

                                switch ($start_time) {
                                    case 'Immediately':
                                        $Timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $Timeframe = "6 Months";
                                        break;
                                    default:
                                        $Timeframe = "Exploring";
                                }

                                switch ($roof_type){
                                    case "Asphalt Roofing":
                                        $roofType="Asphalt";
                                        break;
                                    case "Wood Shake/Composite Roofing":
                                        $roofType="Wood Shake";
                                        break;
                                    case "Metal Roofing":
                                        $roofType="Metal";
                                        break;
                                    case "Tile Roofing":
                                        $roofType="Tile";
                                        break;
                                    default:
                                        $roofType="Asphalt";
                                }

                                $Lead_data_array_ping["apiId"] = $apiId;
                                $Lead_data_array_ping["apiPassword"] = $apiPassword;
                                $Lead_data_array_ping["productId"] = $productId;
                                $Lead_data_array_ping["bg_ownhome"] = $residential;
                                $Lead_data_array_ping["bg_service"] = $roofType;
                                $Lead_data_array_ping["bg_buytimeframe"] = $Timeframe;
                                break;
                            case 7:
                                //Home Siding
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $ownership = trim($Leaddatadetails['homeOwn']);
                                $start_time = trim($Leaddatadetails['start_time']);

                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");

                                $apiId = "6C55BC3B584246AA997EDAD7A2D9FDCF";
                                $apiPassword = "6be80bbcb";
                                $productId = "294";

                                switch ($start_time) {
                                    case 'Immediately':
                                        $Timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $Timeframe = "6 Months";
                                        break;
                                    default:
                                        $Timeframe = "Exploring";
                                }

                                switch($type_of_siding){
                                    case "Vinyl Siding":
                                        $type_of_siding_data = "Vinyl";
                                        break;
                                    case "Composite wood Siding":
                                        $type_of_siding_data = "Wood";
                                        break;
                                    case "Fiber Cement Siding":
                                        $type_of_siding_data = "Fiber Cement";
                                        break;
                                    default:
                                        $type_of_siding_data = "Masonry Siding";
                                }


                                $Lead_data_array_ping["apiId"] = $apiId;
                                $Lead_data_array_ping["apiPassword"] = $apiPassword;
                                $Lead_data_array_ping["productId"] = $productId;
                                $Lead_data_array_ping["bg_ownhome"] = $homeowner;
                                $Lead_data_array_ping["bg_service"] = $type_of_siding_data;
                                $Lead_data_array_ping["bg_buytimeframe"] = $Timeframe;
                                break;
                            case 9:
                                //Bathroom
                                $bathroom_type_name = trim($Leaddatadetails['services']);
                                $start_time = trim($Leaddatadetails['start_time']);
                                $ownership = trim($Leaddatadetails['homeOwn']);

                                $apiId = "7C52CC310A704D60A1B7AB92373F8A37";
                                $apiPassword = "3a58b15e5";
                                $productId = "265";

                                $homeowner = ($ownership == "Yes" ? "YES" : "NO");
                                switch ($bathroom_type_name){
                                    case "Flooring":
                                        $bathroomType = "FLOORING";
                                        break;
                                    case "Shower / Bath":
                                        $bathroomType = "SHOWER_BATH";
                                        break;
                                    case "Sinks":
                                        $bathroomType = "SINKS";
                                        break;
                                    case "Toilet":
                                        $bathroomType = "TOILET";
                                        break;
                                    default:
                                        $bathroomType = "FULL_REMODEL";
                                }
                                switch ($start_time) {
                                    case 'Immediately':
                                        $Timeframe = "Immediately";
                                        break;
                                    case "Within 6 months":
                                        $Timeframe = "6 Months";
                                        break;
                                    default:
                                        $Timeframe = "Exploring";
                                }


                                $Lead_data_array_ping["apiId"] = $apiId;
                                $Lead_data_array_ping["apiPassword"] = $apiPassword;
                                $Lead_data_array_ping["productId"] = $productId;
                                $Lead_data_array_ping["bg_ownhome"] = $homeowner;
                                $Lead_data_array_ping["bg_service"] = "Bathroom Remodeling";
                                $Lead_data_array_ping["bg_buytimeframe"] = $Timeframe;
                                break;
                        }

                        $url_api = "https://leads-inst566-client.phonexa.com/ping/";

                        if (config('app.env', 'local') == "local") {
                            // Test Mode
                            $Lead_data_array_ping['testMode'] = "1";
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
                                if ($result2['status'] == "continue") {
                                    $TransactionId = $result2['promise'];
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
