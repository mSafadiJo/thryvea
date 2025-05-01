<?php

namespace App\Http\Controllers\Api\LeadReview;

use App\LeadReview;
use App\LeadTrafficSources;
use App\MarketingPlatform;
use App\Services\ApiMain;
use App\Services\APIValidations;
use App\State;
use App\TestLeadsCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadReviewController extends Controller
{
    public function SaveZipCode(Request $request)
    {

        $this->validate($request, [
            'zipcode_id' => ['required'],
            'city_id' => ['required'],
            'state_id' => ['required'],
            'lead_website',
            'serverDomain',
            'timeInBrowseData',
            'ipaddress',
            'FullUrl',
            'traffic_source',
            'browser_name',
            "universal_leadid" => ['required'],
            'aboutUserBrowser',
            'tc',
            'c',
            'g',
            'k',
            'token',
            'visitor_id',
            's1',
            's2',
            's3',
            'gclid'
        ]);

        if (!($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', ''))) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Invalid campaign_id or campaign_key value',
                'response_code' => 'false'
            );

            return json_encode($response_code);
        }

        try {
            if(empty($request->county_id)){
                $county_id_list = DB::table('zip_codes_lists')->where('zip_code_list_id', $request['zipcode_id'])->first(['county_id']);
                $county_id = $county_id_list->county_id;
            } else {
                $county_id = $request->county_id;
            }
            //==============================================================================================================
            $lead_source = "SEO";
            $lead_source_id = 1;
            $marketing_ts = LeadTrafficSources::where('name', strtolower($request['tc']))->first();
            if( !empty($marketing_ts) ){
                $marketing_platform = MarketingPlatform::where('id', $marketing_ts->marketing_platform_id)->first();
                if( !empty($marketing_platform) ){
                    $lead_source = $marketing_platform->name;
                    $lead_source_id = $marketing_platform->id;
                }
            }

            $is_exist_lead = LeadReview::where('universal_leadid', $request['universal_leadid'])
                ->first();

            if( empty($is_exist_lead) ){
                $LeadReview = new LeadReview();

                $LeadReview->lead_zipcode_id = $request['zipcode_id'];
                $LeadReview->lead_state_id =  $request['state_id'];
                $LeadReview->lead_city_id = $request['city_id'];
                $LeadReview->lead_county_id = $county_id;
                $LeadReview->lead_serverDomain = $request['serverDomain'];
                $LeadReview->lead_timeInBrowseData = $request['timeInBrowseData'];
                $LeadReview->lead_ipaddress = $request['ipaddress'];
                $LeadReview->lead_FullUrl = $request['FullUrl'];
                $LeadReview->lead_browser_name = $request['browser_name'];
                $LeadReview->lead_aboutUserBrowser = $request['aboutUserBrowser'];
                $LeadReview->lead_website = $request['lead_website'];
                $LeadReview->created_at = date('Y-m-d H:i:s');
                $LeadReview->lead_source_text = $lead_source;
                $LeadReview->lead_source = $lead_source_id;
                $LeadReview->universal_leadid = $request['universal_leadid'];
                $LeadReview->traffic_source = $request['traffic_source'];
                $LeadReview->google_ts = $request['tc'];
                $LeadReview->google_c = $request['c'];
                $LeadReview->google_g = $request['g'];
                $LeadReview->google_k = $request['k'];
                $LeadReview->token = $request['token'];
                $LeadReview->visitor_id = $request['visitor_id'];
                $LeadReview->pushnami_s1 = $request['s1'];
                $LeadReview->pushnami_s2 = $request['s2'];
                $LeadReview->pushnami_s3 = $request['s3'];
                $LeadReview->google_gclid = $request['gclid'];

                $LeadReview->save();
            } else {
                LeadReview::where('universal_leadid', $request['universal_leadid'])->update([
                    "lead_zipcode_id" => $request['zipcode_id'],
                    "lead_state_id" => $request['state_id'],
                    "lead_city_id" => $request['city_id'],
                    "lead_county_id" => $county_id
                ]);
            }

        } catch (Exception $e) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Something went wrong',
                'response_code' => 'false'
            );

            return json_encode($response_code);
        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => '',
            'response_code' => 'true'
        );

        return json_encode($response_code);

    }

    public function SaveName(Request $request)
    {
        $this->validate($request, [
            'fname' => ['required'],
            'lname' => ['required'],
            "universal_leadid" => ['required'],
            "xxTrustedFormCertUrl",
            'timeInBrowseData',
        ]);

        if (!($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', ''))) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Invalid campaign_id or campaign_key value',
                'response_code' => 'false'
            );

            return json_encode($response_code);
            die();
        }

        try {
            if ($request['ownership'] == 2) {
                $request['ownership'] = 0;
            }

            //Kitchen
            if( $request['removing_adding_walls'] == 2 ){
                $request['removing_adding_walls'] = 0;
            }

            if( $request['is_multi_service'] == 1 ){
                $is_multi_service = 1;
                $request['property_type_roofing'] = 1;
                if( $request['ownership'] == 0 ){
                    $request['property_type_c'] = 2;
                } else {
                    $request['property_type_c'] = 1;
                }
            } else {
                $is_multi_service = 0;
            }

            //start window questions ==========================================================================
            $api_validations = new APIValidations();
            $questions = $api_validations->check_questions_ids_array($request);
            $dataMassageForBuyers = $questions['dataMassageForBuyers'];
            $Leaddatadetails = $questions['Leaddatadetails'];
            $LeaddataIDs = $questions['LeaddataIDs'];
            $dataMassageForDB = $questions['dataMassageForDB'];
            //end window questions ==========================================================================

            //==============================================================================================================
            $old_data = LeadReview::where('universal_leadid', $request['universal_leadid'])->first();

            LeadReview::where('universal_leadid', $request['universal_leadid'])->update([
                "lead_fname" => (!empty($old_data->lead_fname) ?  $old_data->lead_fname . ", " . $request['fname'] : $request['fname']),
                "lead_lname" => (!empty($old_data->lead_fname) ?  $old_data->lead_lname . ", " . $request['lname'] : $request['lname']),
                "lead_numberOfItem" => $request['numberofwindows'],
                "lead_ownership" => $request['ownership'],
                "lead_type_service_id" => $request['service_id'],
                "lead_installing_id" => $request['projectnature'],
                "lead_priority_id" => $request['priority'],
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
                "furnance_type_b" => $request['furnance_type_b'],
                "furnance_type_f" => $request['furnance_type_f'],
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

                //Shared fields Insurance
                "birthday" => ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null ),
                "genders" => $request['genders'],
                "married" => $request['married'],

                //Auto Insurance
                "VehicleYear" => $request['VehicleYear'],
                "VehicleMake" => $request['VehicleMake'],
                "car_model" => $request['car_model'],
                "more_than_one_vehicle" => $request['more_than_one_vehicle'],
                "driversNum" => $request['driversNum'],
                "license" => $request['license'],
                "InsuranceCarrier" => $request['InsuranceCarrier'],
                "driver_experience" => $request['driver_experience'],
                "number_of_tickets" => $request['number_of_tickets'],
                "DUI_charges" => $request['DUI_charges'],
                "SR_22_need" => $request['SR_22_need'],
                "submodel" => $request['submodel'],
                "coverage_type" => $request['coverage_type'],
                "license_status" => $request['license_status'],
                "license_state" => $request['license_state'],
                "ticket_date" => $request['ticket_date'],
                "violation_date" => $request['violation_date'],
                "accident_date" => $request['accident_date'],
                "claim_date" => $request['claim_date'],
                "expiration_date" => $request['expiration_date'],

                //home insurance
                'house_type' => $request['house_type'],
                'Year_Built' => $request['Year_Built'],
                'primary_residence' => $request['primary_residence'],
                'new_purchase' => $request['new_purchase'],
                'previous_insurance_within_last30' => $request['previous_insurance_within_last30'],
                'previous_insurance_claims_last3yrs' => $request['previous_insurance_claims_last3yrs'],
                'credit_rating' => $request['credit_rating'],

                //Life Insurance & Disability insurance
                'Height' => $request['Height'],
                'weight' => $request['weight'],
                'amount_coverage' => $request['amount_coverage'],
                'military_personnel_status' => $request['military_personnel_status'],
                'military_status' => $request['military_status'],
                'service_branch' => $request['service_branch'],

                //Business insurance
                'CommercialCoverage' => $request['CommercialCoverage'],
                'company_benefits_quote' => $request['company_benefits_quote'],
                'business_start_date' => $request['business_start_date'],
                'estimated_annual_payroll' => $request['estimated_annual_payroll'],
                'number_of_employees' => $request['number_of_employees'],
                'coverage_start_month' => $request['coverage_start_month'],
                'business_name' => $request['business_name'],

                //Health Insurance & long term insurance
                'pregnancy' => $request['pregnancy'],
                'tobacco_usage' => $request['tobacco_usage'],
                'health_conditions' => $request['health_conditions'],
                'number_of_people_in_household' => $request['number_of_people_in_household'],
                'addPeople' => $request['addPeople'],
                'annual_income' => $request['annual_income'],

                "trusted_form" => $request['xxTrustedFormCertUrl'],
                "created_at" => date('Y-m-d H:i:s'),
                "lead_details_text" => $dataMassageForDB,
                "lead_timeInBrowseData" => $request['timeInBrowseData'],
                "is_multi_service" => $is_multi_service
            ]);
        } catch (Exception $e) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Something went wrong',
                'response_code' => 'false'
            );

            return json_encode($response_code);die();
        }

//        $main_api_file = new ApiMain();
//        //TrustedForm
//        if( !empty($request['xxTrustedFormCertUrl']) ) {
//            $main_api_file->claim_trusted_form($request['xxTrustedFormCertUrl']);
//        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => '',
            'response_code' => 'true'
        );

        return json_encode($response_code);
    }

    public function SavePhoneEmail(Request $request)
    {
        $this->validate($request, [
            'phone_number' => ['required'],
            'email' => ['required'],
            "universal_leadid" => ['required'],
            'timeInBrowseData',
            "xxTrustedFormCertUrl"
        ]);

        if (!($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', ''))) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Invalid campaign_id or campaign_key value',
                'response_code' => 'false'
            );

            return json_encode($response_code);
            die();
        }

        try {
            $old_data = LeadReview::where('universal_leadid', $request['universal_leadid'])->first();

            LeadReview::where('universal_leadid', $request['universal_leadid'])->update([
                "lead_email" => (!empty($old_data->lead_email) ?  $old_data->lead_email . ", " . $request['email'] : $request['email']),
                "lead_phone_number" => (!empty($old_data->lead_phone_number) ?  $old_data->lead_phone_number . ", " . $request['phone_number'] : $request['phone_number']),
                "created_at" => date('Y-m-d H:i:s'),
                "lead_timeInBrowseData" => $request['timeInBrowseData'],
                "trusted_form" => $request['xxTrustedFormCertUrl'],
            ]);
        } catch (Exception $e) {
            $response_code = array(
                'response_code' => 'false',
                'message' => 'Reject',
                'error' => 'Something went wrong',
                'response_code' => 'false'
            );

            return json_encode($response_code);
            die();
        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => '',
            'response_code' => 'true'
        );

        return json_encode($response_code);
    }
}
