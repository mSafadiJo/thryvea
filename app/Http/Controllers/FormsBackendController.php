<?php

namespace App\Http\Controllers;

use App\LeadsCustomer;
use App\LeadTrafficSources;
use App\MarketingPlatform;
use App\Services\APIValidations;
use App\TestLeadsCustomer;
use App\TotalAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ApiMain;
use App\Services\CrmApi;
use App\Services\ServiceQueries;
use App\State;

class FormsBackendController extends Controller
{
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
            'service_id' => ['required', 'string', 'max:255']
        ]);

        //Lead Address ==================================================================
        $county_id_list = DB::table('zip_codes_lists')
            ->where('zip_code_list_id', $request['zipcode_id'])
            ->first();
        $county_id = $county_id_list->county_id;
        $zipcode_name_data = $county_id_list->zip_code_list;

        $counties_arr = DB::table('counties')
            ->where('county_id', $county_id)
            ->first(['county_name']);
        $county_name_data = $counties_arr->county_name;

        $city_idFromNameDB = DB::table('cities')
            ->where('city_id', $request['city_id'])
            ->first();
        $city_name_data =  $city_idFromNameDB->city_name;

        $statename_db = DB::table('states')->where('state_id', $request['state_id'])->first(['state_name', 'state_code']);
        $statename_email = $statename_db->state_name;
        $state_code = $statename_db->state_code;

        $city_idFromNameAndReq = array($request['city_id']);
        $state_idFromNameAndReq = array($request['state_id']);
        $ZipCode_idFromNameAndReq = array($request['zipcode_id']);
        $ZipCode_idFromNameAndReq_dictanse = array($zipcode_name_data);
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

        if( !empty($request['desired_featuers']) ){
            $request['desired_featuers'] = json_encode($request['desired_featuers']);
        }

        if( !empty($request['painting3_each_feature']) ){
            $request['painting3_each_feature'] = json_encode($request['painting3_each_feature']);
        }

        if( !empty($request['painting4_existing_roof']) ){
            $request['painting4_existing_roof'] = json_encode($request['painting4_existing_roof']);
        }

        if( !empty($request['painting5_kindof_texturing']) ){
            $request['painting5_kindof_texturing'] = json_encode($request['painting5_kindof_texturing']);
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

        $lead_source = "Verified";
        $lead_source_id = 8;
        $lead_website = 'Verified Leads';

        //traffic_source
        if( empty($request['traffic_source']) ){
            $request['traffic_source'] = 'Verified Leads';
        }

        //To Get Lead Source ===========================================================================
        $lead_source = "Verified";
        $lead_source2 = "Verified";
        $lead_source_api = "ADMV20";
        $lead_source_id = 14;
        //To Get Lead Source ===========================================================================

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
            $browser_name = 'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
            $browser_name =  'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
            $browser_name =  'Mozilla Firefox';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
            $browser_name =  'Google Chrome';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
            $browser_name =  "Opera Mini";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
            $browser_name =  "Opera";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
            $browser_name =  "Safari";
        else
            $browser_name =  'Something else';

        //TCPA ==============================================================================================
        $tcpa_compliant = 1;
        $tcpa_consent_text = "By clicking the finish button and submitting this form, you are providing your electronic signature in which you consent, acknowledge, and agree to this website's Privacy Policy and Terms And Conditions. You also hereby consent to receive marketing communications via automated telephone dialing systems and/or pre-recorded calls, text messages, and/or emails from our Premiere Partners and marketing partners at the phone number, physical address and email address provided above, with offers regarding the requested Home service. This is also a consent to receive communications even if you are on any State and/or Federal Do Not Call list. Consent is not a condition of purchase and may be revoked at any time. Message and data rates may apply. California Residents Privacy Notice.";
        //TCPA ==============================================================================================

        $lead_id = LeadsCustomer::where('lead_email', $request->email)
            ->where('lead_phone_number', $request['phone_number'])
            ->where('lead_type_service_id', $request->service_id)
            ->where('status', 0)
            ->where('is_duplicate_lead', "<>", 1)
            ->first();

        if( empty($lead_id) ) {
            //Add LeadsCustomer
            $leadsCustomer = new LeadsCustomer();

            $leadsCustomer->lead_fname = $request['fname'];
            $leadsCustomer->lead_lname = $request['lname'];
            $leadsCustomer->lead_address = $request['street_name'];
            $leadsCustomer->lead_email = $request['email'];
            $leadsCustomer->lead_phone_number = $request['phone_number'];
            $leadsCustomer->lead_numberOfItem = $request['numberofwindows'];
            $leadsCustomer->lead_ownership = $request['ownership'];
            $leadsCustomer->lead_type_service_id = $request['service_id'];
            $leadsCustomer->lead_installing_id = $request['projectnature'];
            $leadsCustomer->lead_priority_id = $request['priority'];
            $leadsCustomer->lead_state_id = $request['state_id'];
            $leadsCustomer->lead_city_id = $request['city_id'];
            $leadsCustomer->lead_zipcode_id = $request['zipcode_id'];
            $leadsCustomer->lead_county_id = $county_id;
            $leadsCustomer->lead_solor_solution_list_id = $request['solor_solution'];
            $leadsCustomer->lead_solor_sun_expouser_list_id = $request['solor_sun'];
            $leadsCustomer->lead_current_utility_provider_id = $request['utility_provider'];
            $leadsCustomer->lead_avg_money_electicity_list_id = $request['avg_money'];
            $leadsCustomer->property_type_campaign_id = $request['property_type_c'];
            $leadsCustomer->lead_installation_preferences_id = $request['installation_preferences'];
            $leadsCustomer->lead_have_item_before_it = $request['lead_have_item_before_it'];
            $leadsCustomer->lead_type_of_flooring_id = $request['type_of_flooring'];
            $leadsCustomer->lead_nature_flooring_project_id = $request['nature_flooring_project'];
            $leadsCustomer->lead_walk_in_tub_id = $request['walk_in_tub'];
            $leadsCustomer->lead_desired_featuers_id = $request['desired_featuers'];
            $leadsCustomer->lead_type_of_roofing_id = $request['type_of_roofing'];
            $leadsCustomer->lead_nature_of_roofing_id = $request['nature_of_roofing'];
            $leadsCustomer->lead_property_type_roofing_id = $request['property_type_roofing'];
            $leadsCustomer->type_of_siding_lead_id = $request['type_of_siding'];
            $leadsCustomer->nature_of_siding_lead_id = $request['nature_of_siding'];
            $leadsCustomer->service_kitchen_lead_id = $request['service_kitchen'];
            $leadsCustomer->campaign_kitchen_r_a_walls_status = $request['removing_adding_walls'];
            $leadsCustomer->campaign_bathroomtype_id = $request['bathroom_type'];
            $leadsCustomer->stairs_type_lead_id = $request['stairs_type'];
            $leadsCustomer->stairs_reason_lead_id = $request['stairs_reason'];
            $leadsCustomer->furnance_type_lead_id = $request['furnance_type'];
            $leadsCustomer->plumbing_service_list_id = $request['plumbing_service'];
            $leadsCustomer->sunroom_service_lead_id = $request['sunroom_service'];
            $leadsCustomer->handyman_ammount_work_id = $request['handyman_ammount'];
            $leadsCustomer->countertops_service_lead_id = $request['countertops_service'];
            $leadsCustomer->door_typeproject_lead_id = $request['door_typeproject'];
            $leadsCustomer->number_of_door_lead_id = $request['number_of_door'];
//        $leadsCustomer->gutters_install_type_leade_id = $request['gutters_install_type'];
            $leadsCustomer->gutters_meterial_lead_id = $request['gutters_meterial'];
            $leadsCustomer->paving_service_lead_id = $request['paving_service'];
            $leadsCustomer->paving_asphalt_type_id = $request['paving_asphalt_type'];
            $leadsCustomer->paving_loose_fill_type_id = $request['paving_loose_fill_type'];
            $leadsCustomer->paving_best_describes_priject_id = $request['paving_best_describes_priject'];

            $leadsCustomer->painting_service_lead_id = $request['painting_service'];
            $leadsCustomer->painting1_typeof_project_id = $request['painting1_typeof_project'];
            $leadsCustomer->painting1_stories_number_id = $request['painting1_stories'];
            $leadsCustomer->painting1_kindsof_surfaces_id = $request['painting1_kindsof_surfaces'];
            $leadsCustomer->painting2_rooms_number_id = $request['painting2_rooms_number'];
            $leadsCustomer->painting2_typeof_paint_id = $request['painting2_typeof_paint'];
            $leadsCustomer->painting3_each_feature_id = $request['painting3_each_feature'];
            $leadsCustomer->painting4_existing_roof_id = $request['painting4_existing_roof'];
            $leadsCustomer->painting5_kindof_texturing_id = $request['painting5_kindof_texturing'];
            $leadsCustomer->painting5_surfaces_textured_id = $request['painting5_surfaces_textured'];
            $leadsCustomer->historical_structure = $request['interior_historical'];

            $leadsCustomer->created_at = date('Y-m-d H:i:s');

            $leadsCustomer->trusted_form = $request['xxTrustedFormCertUrl'];
            $leadsCustomer->universal_leadid = $request['universal_leadid'];

            $leadsCustomer->lead_source_text = $lead_source;
            $leadsCustomer->lead_details_text = $dataMassageForDB;

            $leadsCustomer->lead_source = $lead_source_id;
            $leadsCustomer->traffic_source = $request['traffic_source'];
            $leadsCustomer->lead_website = $lead_website;

            $leadsCustomer->lead_serverDomain = $_SERVER['SERVER_NAME'];
            $leadsCustomer->lead_timeInBrowseData = 7;
            $leadsCustomer->lead_ipaddress = request()->ip();
            $leadsCustomer->lead_FullUrl = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
            $leadsCustomer->lead_browser_name = $browser_name;
            $leadsCustomer->lead_aboutUserBrowser = $_SERVER['HTTP_USER_AGENT'];
            $leadsCustomer->tcpa_compliant = $tcpa_compliant;
            $leadsCustomer->tcpa_consent_text = $tcpa_consent_text;

            //Auto Insurance
            $leadsCustomer->VehicleYear = $request['VehicleYear'];
            $leadsCustomer->VehicleMake = $request['VehicleMake'];
            $leadsCustomer->car_model = $request['car_model'];
            $leadsCustomer->more_than_one_vehicle = $request['more_than_one_vehicle'];
            $leadsCustomer->driversNum = $request['driversNum'];
            $leadsCustomer->birthday = ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null );
            $leadsCustomer->genders = $request['genders'];
            $leadsCustomer->married = $request['married'];
            $leadsCustomer->license = $request['license'];
            $leadsCustomer->InsuranceCarrier = $request['InsuranceCarrier'];
            $leadsCustomer->driver_experience = $request['driver_experience'];
            $leadsCustomer->number_of_tickets = $request['number_of_tickets'];
            $leadsCustomer->DUI_charges = $request['DUI_charges'];
            $leadsCustomer->SR_22_need = $request['SR_22_need'];

            $leadsCustomer->save();
            $leadCustomer_id = DB::getPdo()->lastInsertId();
        } else {
            $leadCustomer_id = $lead_id->lead_id;

            LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
                "lead_fname" => $request['fname'],
                "lead_lname" => $request['lname'],
                "lead_address" => $request['street_name'],
                "lead_email" => $request['email'],
                "lead_phone_number" => $request['phone_number'],
                "lead_numberOfItem" => $request['numberofwindows'],
                "lead_ownership" => $request['ownership'],
                "lead_type_service_id" => $request['service_id'],
                "lead_installing_id" => $request['projectnature'],
                "lead_priority_id" => $request['priority'],
                "lead_state_id" => $request['state_id'],
                "lead_city_id" => $request['city_id'],
                "lead_zipcode_id" => $request['zipcode_id'],
                "lead_county_id" => $county_id,
                "lead_solor_solution_list_id" => $request['solor_solution'],
                "lead_solor_sun_expouser_list_id" => $request['solor_sun'],
                "lead_current_utility_provider_id" => $request['utility_provider'],
                "lead_avg_money_electicity_list_id" => $request['avg_money'],
                "property_type_campaign_id" => $request['property_type_c'],
                "lead_installation_preferences_id" => $request['installation_preferences'],
                "lead_have_item_before_it" => $request['lead_have_item_before_it'],
                "lead_type_of_flooring_id" => $request['type_of_flooring'],
                "lead_nature_flooring_project_id" => $request['nature_flooring_project'],
                "lead_walk_in_tub_id" => $request['walk_in_tub'],
                "lead_desired_featuers_id" => $request['desired_featuers'],
                "lead_type_of_roofing_id" => $request['type_of_roofing'],
                "lead_nature_of_roofing_id" => $request['nature_of_roofing'],
                "lead_property_type_roofing_id" => $request['property_type_roofing'],
                "type_of_siding_lead_id" => $request['type_of_siding'],
                "nature_of_siding_lead_id" => $request['nature_of_siding'],
                "service_kitchen_lead_id" => $request['service_kitchen'],
                "campaign_kitchen_r_a_walls_status" => $request['removing_adding_walls'],
                "campaign_bathroomtype_id" => $request['bathroom_type'],
                "stairs_type_lead_id" => $request['stairs_type'],
                "stairs_reason_lead_id" => $request['stairs_reason'],
                "furnance_type_lead_id" => $request['furnance_type'],
                "plumbing_service_list_id" => $request['plumbing_service'],
                "sunroom_service_lead_id" => $request['sunroom_service'],
                "handyman_ammount_work_id" => $request['handyman_ammount'],
                "countertops_service_lead_id" => $request['countertops_service'],
                "door_typeproject_lead_id" => $request['door_typeproject'],
                "number_of_door_lead_id" => $request['number_of_door'],

                "gutters_meterial_lead_id" => $request['gutters_meterial'],
                "paving_service_lead_id" => $request['paving_service'],
                "paving_asphalt_type_id" => $request['paving_asphalt_type'],
                "paving_loose_fill_type_id" => $request['paving_loose_fill_type'],
                "paving_best_describes_priject_id" => $request['paving_best_describes_priject'],

                "painting_service_lead_id" => $request['painting_service'],
                "painting1_typeof_project_id" => $request['painting1_typeof_project'],
                "painting1_stories_number_id" => $request['painting1_stories'],
                "painting1_kindsof_surfaces_id" => $request['painting1_kindsof_surfaces'],
                "painting2_rooms_number_id" => $request['painting2_rooms_number'],
                "painting2_typeof_paint_id" => $request['painting2_typeof_paint'],
                "painting3_each_feature_id" => $request['painting3_each_feature'],
                "painting4_existing_roof_id" => $request['painting4_existing_roof'],
                "painting5_kindof_texturing_id" => $request['painting5_kindof_texturing'],
                "painting5_surfaces_textured_id" => $request['painting5_surfaces_textured'],
                "historical_structure" => $request['interior_historical'],

                "created_at" => date('Y-m-d H:i:s'),

                "trusted_form" => $request['xxTrustedFormCertUrl'],
                "universal_leadid" => $request['universal_leadid'],

                "lead_details_text" => $dataMassageForDB,
                "traffic_source" => $request['traffic_source'],
                "lead_website" => $lead_website,

                "lead_serverDomain" => $_SERVER['SERVER_NAME'],
                "lead_timeInBrowseData" => 7,
                "lead_ipaddress" => request()->ip(),
                "lead_FullUrl" => "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
                "lead_browser_name" => $browser_name,
                "lead_aboutUserBrowser" => $_SERVER['HTTP_USER_AGENT'],
                "tcpa_compliant" => $tcpa_compliant,
                "tcpa_consent_text" => $tcpa_consent_text
            ]);
        }

        if( $leadCustomer_id >= 1 ){
            if( strtolower($request['fname']) == 'test' || strtolower($request['lname']) == 'test'
                || strtolower($request['fname']) == 'testing' || strtolower($request['lname']) == 'testing'){
                \Session::put('success', 'Lead Added successful!');
                return redirect()->back();
            }
        } else {
            \Session::put('error', 'fail to Added Lead!');
            return redirect()->back();
        }

        $main_api_file = new ApiMain();

        //Lead Info =====================================================================================================================
        $city_arr = explode('=>', $city_name_data);
        $county_arr = explode('=>', $county_name_data);
        $service_info = DB::table('service__campaigns')
            ->where('service_campaign_id', $request['service_id'])
            ->first();

        $data_msg = array(
            'leadCustomer_id' => $leadCustomer_id,
            'leadName' => $request['fname'] . ' ' . $request['lname'],
            'LeadEmail' => $request['email'],
            'LeadPhone' => $request['phone_number'],
            'Address' => 'Address: ' . $request['street_name'] . ', City: ' . $city_arr[0] . ', State: ' . $statename_email . ', Zipcode: ' . $zipcode_name_data,
            'LeadService' => $service_info->service_campaign_name,
            'service_id' => $request->service_id,
            'data' => $dataMassageForBuyers,
            'trusted_form' => $request['trusted_form'],
            'street' => $request['street_name'],
            'City' => $city_arr[0],
            'State' => $statename_email,
            'state_code' => $state_code,
            'Zipcode' => $zipcode_name_data,
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
            'traffic_source' => "",
            'google_ts' => "",
            'is_multi_service' => $request['is_multi_service'],
            'is_sec_service' => $request['is_sec_service'],
            'dataMassageForBuyers' => $dataMassageForBuyers,
            'Leaddatadetails' => $Leaddatadetails,
            'LeaddataIDs' => $LeaddataIDs,
            'dataMassageForDB' => $dataMassageForDB,
            'appointment_date' => '',
            'appointment_type' => '',
            'oldNumber' => $request['phone_number'],
            'newNumber' => "",
        );
        //Lead Info =====================================================================================================================

        //TrustedForm
        //$main_api_file->claim_trusted_form($request['xxTrustedFormCertUrl']);
        //Claim Jornaya LeadId
        $main_api_file->claim_jornaya_id($request['universal_leadid']);

        //Select Area Of Campaign =============================================================
        $lastCampainInArea = $main_api_file->campaign_ids_from_area($ZipCode_idFromNameAndReq, $ZipCode_idFromNameAndReq_dictanse, $city_idFromNameAndReq, $county_id, $state_idFromNameAndReq);
        //Select Area Of Campaign =============================================================

        //Select List Of Campaign
        $service_queries = new ServiceQueries();
        $listOFCampain_exclusiveDB = $service_queries->service_queries($request->service_id, $LeaddataIDs, $lastCampainInArea, 1, 0, $lead_source, 1);
        $listOFCampain_sharedDB = $service_queries->service_queries($request->service_id, $LeaddataIDs, $lastCampainInArea, 2, 0, $lead_source, 1);
        $listOFCampain_pingDB_ex = $service_queries->service_queries($request->service_id, $LeaddataIDs, $lastCampainInArea, 1, 1, $lead_source, 1);
        $listOFCampain_pingDB_sh = $service_queries->service_queries($request->service_id, $LeaddataIDs, $lastCampainInArea, 2, 1, $lead_source, 1);

        //Filtaration
        $listOFCampainDB_array_exclusive = $main_api_file->filterCampaign_exclusive_sheared($listOFCampain_exclusiveDB, $data_msg, 5, 1,  0);
        $listOFCampainDB_array_shared = $main_api_file->filterCampaign_exclusive_sheared($listOFCampain_sharedDB, $data_msg, 10, 2,  0);
        $listOFCampainDB_array_ping_ex = $main_api_file->filterCampaign_ping_post($listOFCampain_pingDB_ex, $data_msg, 1, 0, 0);
        $listOFCampainDB_array_ping_sh = $main_api_file->filterCampaign_ping_post($listOFCampain_pingDB_sh, $data_msg, 2, 0,  0);

        $campaigns_sh = array_merge($listOFCampainDB_array_shared['campaigns'],$listOFCampainDB_array_ping_sh['campaigns']);
        $campaigns_ex = array_merge($listOFCampainDB_array_exclusive['campaigns'],$listOFCampainDB_array_ping_ex['campaigns']);
        $ping_post_arr = array_merge($listOFCampainDB_array_ping_ex['response'],$listOFCampainDB_array_ping_sh['response']);

        //Sort Campaign By Bid
        $campaigns_sh = collect($campaigns_sh);
        $campaigns_sh_sorted = $campaigns_sh->sortByDesc('campaign_budget_bid_shared');
        $campaigns_ex = collect($campaigns_ex);
        $campaigns_ex_sorted = $campaigns_ex->sortByDesc('campaign_budget_bid_exclusive');

        //Add Response To Test
        $TestLeadsCustomer = new TestLeadsCustomer();

        $TestLeadsCustomer->lead_id = $leadCustomer_id;

        $TestLeadsCustomer->lastCampainInArea = json_encode($lastCampainInArea);

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

        return redirect()->back();
    }
}
