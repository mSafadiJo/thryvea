<?php

namespace App\Services\Zone;

use App\Services\CrmApi;
use App\Jangle;
use App\LeadPortal;
use App\Leads_Pedia;
use App\leads_pedia_track;
use Illuminate\Support\Facades\Log;

class PingCRMZone
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
            $lead_source_name = $data_msg['lead_source_name'];
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
                if (empty($Leads_PediaDetails)) {
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "Leads_PediaDetails"));
                    if ($is_multi_api == 0) {
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

                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&lp_s1=$lead_source_text&lp_s2=$lead_source_text&lp_s3=$lead_source_text";

                $httpheader = array(
                    "cache-control: no-cache",
                    "Accept: application/json",
                    "content-type: application/json"
                );

                switch ($lead_type_service_id) {
                    case 2:
                        //Solar
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);

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

                if ($is_multi_api == 0) {
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
            } else if (in_array(7, $compaign_crm_arr)) {
                $JangleDetails = Jangle::where('campaign_id', $campaign_id)->first();
                if (empty($JangleDetails)) {
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "JangleDetails"));
                    if ($is_multi_api == 0) {
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

                $url_api = $JangleDetails->PingUrl;

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
                        //solar
                        $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                        $utility_provider = trim($Leaddatadetails['utility_provider']);
                        $roof_shade = trim($Leaddatadetails['roof_shade']);
                        $property_type = trim($Leaddatadetails['property_type']);

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
                        //Home security
                        $Installation_Preferences = trim($Leaddatadetails['Installation_Preferences']);
                        $lead_have_item_before_it = trim($Leaddatadetails['lead_have_item_before_it']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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

                        switch ($bathroom_type_name) {
                            case "Shower / Bath" || "Sinks" || "Toilet":
                                $bathroom_type_name_data = "Bath, sinks";
                                break;
                            default:
                                $bathroom_type_name_data = "Full bathroom";
                        }

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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
                            "best_call_time" => "Any time",
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

                        $Lead_data_array_ping['data'] = array(
                            "best_call_time" => "Any time",
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

                if ($is_multi_api == 0) {
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
            } else if (in_array(12, $compaign_crm_arr)) {
                $LeadPortalDetails = LeadPortal::where('campaign_id', $campaign_id)->first();
                if (empty($LeadPortalDetails)) {
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "leadPortalCrm"));
                    if ($is_multi_api == 0) {
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

                switch ($lead_type_service_id) {
                    case 1:
                        //Windows
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $number_of_windows = trim($Leaddatadetails['number_of_window']);

                        if ($project_nature == "Repair") {
                            $Project = "Window Repair";
                        } else {
                            $Project = ($number_of_windows == 1 ? "Window Install - Single" : "Window Install - Multiple");
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
                        //Home Security
                        $Project = "Home Security";
                        break;
                    case 4:
                        //Flooring
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Project = "Flooring";
                        break;
                    case 5:
                        //WALK-IN TUBS
                        $Project = "Walk-in Tub";
                        break;
                    case 6:
                        //Roofing
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Project = "Roofing";
                        break;
                    case 7:
                        //Home Siding
                        $project_nature = trim($Leaddatadetails['project_nature']);
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Project = ($project_nature == "Repair" ? "Furnace / Heating System - Repair/Service" : "Furnace / Heating System - Install/Replace");
                        break;
                    case 12:
                        //Boiler
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Project = ($project_nature == "Repair" ? "Boiler or Radiator System - Service/Repair" : "Boiler Or Radiator System - Install/Replace");
                        break;
                    case 13:
                        //Central A/C
                        $project_nature = trim($Leaddatadetails['project_nature']);
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
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $Project = "Gutter Install/Repair";
                        break;
                    case  23:
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
                } else if ($lead_type_service_id == 24) {
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
                } else {
                    if (!empty($Leaddatadetails['homeOwn'])) {
                        $ownership = ($Leaddatadetails['homeOwn'] != "Yes" ? "No" : "Yes");
                    } else {
                        if (!empty($Leaddatadetails['property_type'])) {
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
                }

                $url_api = $LeadPortalDetails->api_url;

                if (config('app.env', 'local') == "local") {
                    //Test Mode
                    if ($lead_type_service_id != 2) {
                        $Lead_data_array_ping['Request']['Project'] = "Alarm/ security system - Install";
                    }

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

                if ($is_multi_api == 0) {
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
            } else if (in_array(13, $compaign_crm_arr)) {
                $leads_pedia_track = leads_pedia_track::where('campaign_id', $campaign_id)->first();
                if (empty($leads_pedia_track)) {
                    Log::info('Campaign Error Massage', array('campaign_id' => $campaign_id, 'CRM_type' => "leads_pedia_track"));
                    if ($is_multi_api == 0) {
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

                $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_s1=$lead_source_text&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress";
                switch ($user_id) {
                    case 217:
                        // 217	Solar Direct Marketing
                        $url_api = "$lp_url?lp_campaign_id=$lp_campaign_id&lp_campaign_key=$lp_campaign_key&lp_response=JSON&city=$city&state=$state&zip_code=$zip&ip_address=$IPAddress&credit_score=Good&source_page_url=$OriginalURL2&landing_page=$OriginalURL2&tcpa=$tcpa_compliant2&tcpaDisclosure=$tcpa_compliant2&tcpa_text=$TCPAText&jornaya_lead_id=$LeadId&trusted_form_cert_id=$trusted_form&user_agent=$UserAgent&lp_s2=$lead_source_text&traffic_source=$lead_source_name";
                        break;
                }
                switch ($lead_type_service_id) {
                    case 1:
                        //windows
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $number_of_windows = trim($Leaddatadetails['number_of_window']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        switch ($user_id) {
                            case 217:
                                // 217	Solar Direct Marketing
                                $url_api .= "&lp_s1=236W&repair_or_replace=Replace";
                                break;
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
                        $Installation_Preferences = trim($Leaddatadetails['Installation_Preferences']);
                        $lead_have_item_before_it = trim($Leaddatadetails['lead_have_item_before_it']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);

                        break;
                    case 4:
                        //Flooring
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $Type_OfFlooring = trim($Leaddatadetails['flooring_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        break;
                    case 5:
                        //WALK-IN TUBS
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        break;
                    case 6:
                        //Roofing
                        $roof_type = trim($Leaddatadetails['roof_type']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $property_type = trim($Leaddatadetails['property_type']);
                        switch ($user_id) {
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
                            default:
                                $owner = "yes";
                                $time_frame = ($start_time == 'Immediately' ? "immediate" : "over_2_weeks");
                                $property_typeRoofing = ($property_type == 'Residential' ? "residential" : "commercial");
                                $best_call_time = "Anytime";

                                switch ($roof_type) {
                                    case "Asphalt Roofing":
                                        switch ($project_nature) {
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
                                        switch ($project_nature) {
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
                                        switch ($project_nature) {
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
                                        switch ($project_nature) {
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
                                        switch ($project_nature) {
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
                                        switch ($project_nature) {
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

                        break;
                    case 8:
                        //Kitchen
                        $service_kitchen = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $demolishing_walls = trim($Leaddatadetails['demolishing_walls']);

                        break;
                    case 9:
                        //BathRoom
                        $bathroom_type_name = trim($Leaddatadetails['services']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        switch ($user_id) {
                            case 217:
                                // 217	Solar Direct Marketing
                                $url_api .= "&lp_s1=236B&repair_or_replace=Replace";
                                break;
                        }
                        $Lead_data['bathroom_type'] = $bathroom_type_name;
                        $Lead_data['start_time'] = $start_time;
                        $Lead_data['ownership'] = $ownership;
                        break;
                    case 11:
                        //Furnace
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        break;
                    case 12:
                        //Boiler
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $type_of_heating = trim($Leaddatadetails['type_of_heating']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);

                        break;
                    case 13:
                        //Central A/C
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        break;
                    case 14:
                        //Cabinet
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);
                        break;
                    case 16:
                        //Bathtubs
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        break;
                    case 18:
                        //Handyman
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);

                        break;
                    case 20:
                        //Doors
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

                        break;
                    case 21:
                        //Gutter
                        $ownership = trim($Leaddatadetails['homeOwn']);
                        $start_time = trim($Leaddatadetails['start_time']);
                        $project_nature = trim($Leaddatadetails['project_nature']);

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
                    "inputs" => "",
                    "method" => "POST",
                    "campaign_id" => $campaign_id,
                    "service_id" => $lead_type_service_id,
                    "user_id" => $user_id,
                    "returns_data" => $returns_data,
                    "crm_type" => 13
                );

                if ($is_multi_api == 0) {
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
            } else {
                //Custom CRM
                switch ($user_id) {
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
                        if (isset($Leaddatadetails['property_type'])) {
                            $property_type = trim($Leaddatadetails['property_type']);
                            $home_owner = ($property_type != "Owned" ? 0 : 1);
                        } else if (isset($Leaddatadetails['homeOwn'])) {
                            $ownership = trim($Leaddatadetails['homeOwn']);
                            $home_owner = ($ownership == "No" ? 0 : 1);
                        }

                        $url_api_ping = "https://api.astoriacompany.com/v2/ping/";
                        $httpheader = array(
                            "Content-Type: application/x-www-form-urlencoded"
                        );

                        $Lead_data_ping = "lead_type=$lead_type&lead_mode=$lead_mode&vendor_id=$vendor_id&sub_id=$sub_id&tcpa_optin=$tcpa_optin&tcpa_text=$TCPAText&universal_leadid=$LeadId&origination_datetime=$origination_datetime&origination_timezone=$origination_timezone&ipaddress=$IPAddress&user_agent=$UserAgent&vendor_lead_id=$leadCustomer_id&url=$OriginalURL2&zip=$zip&timeframe=$timeframe&home_owner=$home_owner&project_status=$project_status&xxtrustedformcerturl=$trusted_form";

                        //Services
                        switch ($lead_type_service_id) {
                            case 1:
                                //windows
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                if ($project_nature != "Repair") {
                                    $project = 96;
                                    $task_ID = 128;
                                    $Lead_data_ping .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_ping .= "&project=$project";
                                }
                                break;
                            case 2:
                                //Solar
                                $monthly_electric_bill = trim($Leaddatadetails['monthly_electric_bill']);
                                $utility_provider = trim($Leaddatadetails['utility_provider']);
                                $roof_shade = trim($Leaddatadetails['roof_shade']);
                                $property_type = trim($Leaddatadetails['property_type']);

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

                                $Lead_data_ping .= "&project=$project&current_provider=$current_provider&monthly_bill=$average_bill&property_type=$property_type_data&roof_type=$roof_type&roof_shade=$roof_shade_data&credit_rating=$credit_rating";
                                break;
                            case 3:
                                //Home Security
                                $project = 97;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 4:
                                //Flooring
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $project = ($project_nature != "Repair Existing Flooring" ? 77 : 90);
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 6:
                                //Roofing
                                $Type_OfRoofing = trim($Leaddatadetails['roof_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $property_type = trim($Leaddatadetails['property_type']);

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
                                $Lead_data_ping .= "&project=$project&task=$task_ID";
                                break;
                            case 7:
                                //Siding
                                $type_of_siding = trim($Leaddatadetails['type_of_siding']);
                                $project_nature = trim($Leaddatadetails['project_nature']);

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

                                    $Lead_data_ping .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_ping .= "&project=$project";
                                }
                                break;
                            case 8:
                                //Kitchen
                                $project = 82;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 9:
                                //Bathroom
                                $project = 63;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 11 || 12 || 13:
                                //HVAC
                                $project = 81;

                                $project_nature = trim($Leaddatadetails['project_nature']);
                                if ($project_nature == "Repair") {
                                    $task_ID = 78;
                                } else {
                                    switch ($lead_type_service_id) {
                                        case 11:
                                            $type_of_heating = trim($Leaddatadetails['type_of_heating']);

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
                                            $type_of_heating = trim($Leaddatadetails['type_of_heating']);

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

                                $Lead_data_ping .= "&project=$project&task=$task_ID";
                                break;
                            case 14:
                                //Cabinet
                                $project = 64;
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $task_ID = ($project_nature == "Cabinet Install" ? 14 : 15);
                                $Lead_data_ping .= "&project=$project&task=$task_ID";
                                break;
                            case 15:
                                //Plumbing
                                $project = 87;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 17:
                                //Sunrooms
                                $project = 92;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 18:
                                //Handyman
                                $project = 80;
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 19:
                                //CounterTops
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $project = ($project_nature != "Repair" ? 64 : 90);
                                $Lead_data_ping .= "&project=$project";
                                break;
                            case 20:
                                //Doors
                                $door_type = trim($Leaddatadetails['door_type']);
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                if ($project_nature != "Repair") {
                                    $project = 72;
                                    $task_ID = ($door_type == "Exterior" ? 49 : 50);
                                    $Lead_data_ping .= "&project=$project&task=$task_ID";
                                } else {
                                    $project = 90;
                                    $Lead_data_ping .= "&project=$project";
                                }
                                break;
                            case 21:
                                //Gutters
                                $project = 88;
                                $project_nature = trim($Leaddatadetails['project_nature']);
                                $task_ID = ($project_nature == "Repair" ? 108 : 103);
                                $Lead_data_ping .= "&project=$project&task=$task_ID";
                                break;
                            case 23:
                                //Painting
                                $project = 85;
                                $service_type = trim($Leaddatadetails['service']);

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

                                $Lead_data_ping .= "&project=$project&task=$task_ID";
                                break;
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

                        if ($is_multi_api == 0) {
                            $result = $crm_api_file->api_send_data($url_api_ping, $httpheader, $leadCustomer_id, $Lead_data_ping, "POST", $returns_data, $campaign_id);
                            try {
                                libxml_use_internal_errors(true);
                                $result2 = simplexml_load_string($result);
                                $result3 = json_encode($result2);
                                $result4 = json_decode($result3, TRUE);

                                if (!empty($result4)) {
                                    if (!empty($result4['Response'])) {
                                        if ($result4['Response'] == "Accepted") {
                                            $TransactionId = $result4['Confirmation'];
                                            $Payout = $result4['Price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                            }
                        }
                        break;
                }
            }

            if ($is_multi_api == 0) {
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
