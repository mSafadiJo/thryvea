<?php

namespace App\Services;

class ApiPingCrm
{
    public function callToolsPings($campaign, $data_msg)
    {
        try {
            $returns_data = 2;
            $crm_api_file = new CrmApi();
            $TransactionId = '';
            $Payout = 0;
            $Result = "Error";

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

            switch ($user_id) {
                case 585:
                    //Avenge Digital lead 585
                    $httpheader = array(
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    $api_key = "29a5d3e14104463786405a26a6d04f8e";
                    $url_api = "https://www.avengehub.com/Api/PingV1?apiKey=$api_key";

                    $Lead_data_array_ping = array();
                    if ($lead_type_service_id == 24) {
                        //Auto Insurance 24
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

                        $ownership_data = ($ownership == "Yes" ? "1" : "2");
                        $DUI_charges_data = ($DUI_charges == "Yes" ? "1" : "2");
                        $SR_22_need_data = ($SR_22_need == "Yes" ? "1" : "2");
                        $more_than_one_vehicle_data = ($more_than_one_vehicle == "Yes" ? "1" : "2");
                        $is_insured = ($InsuranceCarrier == "Not Currently Insured" ? "2" : "1");

                        $InsuranceCarrier_data = 6;
                        if ($InsuranceCarrier == "State Farm") {
                            $InsuranceCarrier_data = 1;
                        } else if ($InsuranceCarrier == "Allstate" || $InsuranceCarrier == "Esurance") {
                            $InsuranceCarrier_data = 2;
                        } else if ($InsuranceCarrier == "Liberty Mutual") {
                            $InsuranceCarrier_data = 3;
                        } else if ($InsuranceCarrier == "Nationwide") {
                            $InsuranceCarrier_data = 4;
                        } else if ($InsuranceCarrier == "Farmers") {
                            $InsuranceCarrier_data = 5;
                        }

                        $Lead_data_array_ping = '{
                    "CampaignId" : 2560,
                    "ZipCode" : "02301",
                    "ExternalId": "23fadvarawrgs",
                    "FilterAnswer": [
                        {
                            "FilterId": 1,
                            "Answer": ' . $is_insured . '
                        },
                        {
                            "FilterId": 6,
                            "Answer": ' . $InsuranceCarrier_data . '
                        },
                        {
                            "FilterId": 3,
                            "Answer": ' . $ownership_data . '
                        },
                        {
                            "FilterId": 7,
                            "Answer": ' . $DUI_charges_data . '
                        },
                        {
                            "FilterId": 25,
                            "Answer": ' . $SR_22_need_data . '
                        },
                        {
                            "FilterId": 15,
                            "Answer": ' . $more_than_one_vehicle_data . '
                        }
                    ]
                }';
                    }

                    $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, $Lead_data_array_ping, "POST", $returns_data, $campaign_id);
                    $result2 = json_decode($result, true);
                    return $result2;
                    break;
                case 51:
                    //PX API 51
                    $httpheader = array(
                        "cache-control: no-cache",
                        "Accept: application/json",
                        "content-type: application/json"
                    );

                    if ($trusted_form == "NA" || $trusted_form == "N/A"
                        || $trusted_form == "https://cert.trustedform.com/Will_Provide_on_Post"
                        || $trusted_form == "https://cert.trustedform.com/will_send_on_post"
                        || $trusted_form == "https://cert.trustedform.com") {
                        $trusted_form = "";
                    }

                    switch ($lead_type_service_id) {
                        case 1:
                            //Windows
                            $ownership = trim($Leaddatadetails['homeOwn']);
                            $start_time = trim($Leaddatadetails['start_time']);
                            $number_of_windows = trim($Leaddatadetails['number_of_window']);
                            $project_nature = trim($Leaddatadetails['project_nature']);

                            $OwnRented = (strtolower($ownership) == 'yes' ? 'Own' : 'Rented');

                            switch ($start_time) {
                                case 'Immediately':
                                    $start_time = "Immediately";
                                    break;
                                case 'Not Sure':
                                    $start_time = "Not Sure";
                                    break;
                                default:
                                    $start_time = "Within a Year";
                            }

                            switch ($number_of_windows) {
                                case '1':
                                    $number_windows = "1 window";
                                    break;
                                case '2':
                                    $number_windows = "2 windows";
                                    break;
                                case '3-5':
                                    $number_windows = "3 to 5 windows";
                                    break;
                                case '6-9':
                                    $number_windows = "6 to 9 windows";
                                    break;
                                default:
                                    $number_windows = "10+ windows";
                            }

                            switch ($project_nature) {
                                case "Install":
                                case "Replace":
                                    $project_nature_data = "New Unit Installed";
                                    break;
                                default:
                                    $project_nature_data = "Repair";
                            }

                            $url_api = "https://leadapi.px.com/api/lead/ping";
                            $PartnerToken = "4C738DD2-01D5-4AA7-979F-184E8CEBBE7F";
                            $AffiliateData_OfferId = "2015";

                            if (config('app.env', 'local') == "local") {
                                //Test Mode
                                $google_ts = "test";
                                $zip = "90100";
                            }

                            $Lead_data_array_ping = array(
                                "ApiToken" => "$PartnerToken",
                                "Vertical" => "Windows",
                                "SubId" => "$google_ts",
                                "UserAgent" => "$UserAgent",
                                "OriginalUrl" => "$OriginalURL2",
                                "Source" => "All",
                                "JornayaLeadId" => "$LeadId",
                                "TrustedForm" => "$trusted_form",
                                "SessionLength" => "$SessionLength",
                                "TcpaText" => "$TCPAText",
                                "VerifyAddress" => "false",
                                "OfferId" => "$AffiliateData_OfferId",
                                "ContactData" => array(
                                    "State" => "$statename_code",
                                    "ZipCode" => "$zip",
                                    "IpAddress" => "$IPAddress",
                                    "PhoneNumber" => "$number1"
                                ),
                                "Person" => array(
                                    "BirthDate" => "1980-07-27",
                                    "Gender" => "Male",
                                    "BestTimeToCall" => "Any time"
                                ),
                                "Custom" => array(
                                    "Field1" => "Free text",
                                    "Field2" => "Free text",
                                    "Field3" => "Free text",
                                    "Field4" => "Free text",
                                    "Field5" => "Free text",
                                    "Field6" => "Free text",
                                    "Field7" => "Free text",
                                    "Field8" => "Free text",
                                    "Field9" => "Free text",
                                    "Field10" => "Free text"
                                ),
                                "Home" => array(
                                    "ProjectType" => "$project_nature_data",
                                    "NumberOfWindows" => "$number_windows",
                                    "WindowStyle" => "Unsure",
                                    "PropertyType" => "Residential",
                                    "OwnRented" => "$OwnRented",
                                    "AuthorizedToMakeChanges" => "Yes",
                                    "PurchaseTimeframe" => "$start_time",
                                )
                            );

                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['Success'])) {
                                if ($result2['Success'] == "true") {
                                    $TransactionId = $result2['TransactionId'];
                                    $Payout = $result2['Payout'];
                                    $Result = "Success";
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

                            $SecurityUsage = ($property_type == "Business" ? "Commercial" : "Residential");
                            $property_type = ($property_type == "Owned" ? "Own" : "Rented");

                            switch ($power_solution){
                                case "Solar Water Heating for my Home":
                                    $SolarSystemType = "Solar hot water";
                                    break;
                                case "Solar Electricity & Water Heating for my Home":
                                    $SolarSystemType = "Solar electricity and hot water";
                                    break;
                                default:
                                    $SolarSystemType = "Solar electricity";
                            }

                            switch ($roof_shade){
                                case "Full Sun":
                                    $roof_shade_data = "Full sun";
                                    break;
                                case "Mostly Shaded":
                                    $roof_shade_data = "Mostly shaded";
                                    break;
                                case "Partial Sun":
                                    $roof_shade_data = "Partial sun";
                                    break;
                                default:
                                    $roof_shade_data = "Not sure";
                            }

                            switch ($monthly_electric_bill) {
                                case '$0 - $50' || '$51 - $100':
                                    $monthly_electric = "$51-75";
                                    break;
                                case '$101 - $150' || '$151 - $200':
                                    $monthly_electric = "$151-175";
                                    break;
                                case '$201 - $300':
                                    $monthly_electric = "$201-300";
                                    break;
                                case '$301 - $400':
                                    $monthly_electric = "$301-400";
                                    break;
                                case '$401 - $500':
                                    $monthly_electric = "$401-500";
                                    break;
                                default:
                                    $monthly_electric = "$500+";
                            }

                            $url_api = "https://leadapi.px.com/api/lead/ping";
                            $PartnerToken = "C1FD93F3-50CB-42FA-9B67-9F0DAE79DEFA";
                            $AffiliateData_OfferId = "122";

                            if (config('app.env', 'local') == "local") {
                                //Test Mode
                                $google_ts = "test";
                                $zip = "90100";
                            }

                            $Lead_data_array_ping = array(
                                "ApiToken" => "$PartnerToken",
                                "Vertical" => "Solar",
                                "SubId" => "$google_ts",
                                "UserAgent" => "$UserAgent",
                                "OriginalUrl" => "$OriginalURL2",
                                "Source" => "All",
                                "JornayaLeadId" => "$LeadId",
                                "TrustedForm" => "$trusted_form",
                                "SessionLength" => "$SessionLength",
                                "TcpaText" => "$TCPAText",
                                "VerifyAddress" => "false",
                                "OfferId" => "$AffiliateData_OfferId",
                                "ContactData" => array(
                                    "ZipCode" => "$zip",
                                    "State" => "$statename_code",
                                    "IpAddress" => "$IPAddress",
                                    "Address" => "$street",
                                    "PhoneNumber" => "$number1",
                                ),
                                "Custom" => array(
                                    "Field1" => "Free text",
                                    "Field2" => "Free text",
                                    "Field3" => "Free text",
                                    "Field4" => "Free text",
                                    "Field5" => "Free text",
                                    "Field6" => "Free text",
                                    "Field7" => "Free text",
                                    "Field8" => "Free text",
                                    "Field9" => "Free text",
                                    "Field10" => "Free text"
                                ),
                                "Home" => array(
                                    "Ownership" => "$property_type",
                                    "RoofShade" => "$roof_shade_data",
                                    "AuthorizedForPropertyChanges" => "Yes",
                                    "CurrentUtilityProvider" => "$utility_provider",
                                    "ElectricityBill" => "$monthly_electric",
                                    "ProjectStatus" => "Existing home",
                                    "PropertyUsage" => "$SecurityUsage",
                                    "SolarSystemType" => "$SolarSystemType",
                                    "PropertyStories" => "Two stories",
                                    "SolarInstallationLocation" => "Roof"
                                )
                            );

                            $result = $crm_api_file->api_send_data($url_api, $httpheader, $leadCustomer_id, stripslashes(json_encode($Lead_data_array_ping)), "POST", $returns_data, $campaign_id);
                            $result2 = json_decode($result, true);
                            if (!empty($result2['Success'])) {
                                if ($result2['Success'] == "true") {
                                    $TransactionId = $result2['TransactionId'];
                                    $Payout = $result2['Payout'];
                                    $Result = "Success";
                                }
                            }
                            break;
                    }
                    break;
            }

            $ping_crm_apis = array(
                "Status" => $Result,
                "TransactionId" => $TransactionId,
                "Payout" => $Payout,
                "lead_id" => $leadCustomer_id
            );

            return json_encode($ping_crm_apis);
        } catch (Exception $e) {

        }
    }
}
