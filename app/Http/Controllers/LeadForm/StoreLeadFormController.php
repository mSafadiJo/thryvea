<?php

namespace App\Http\Controllers\LeadForm;

use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use App\Models\LeadForm;
use App\Services\APIValidations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreLeadFormController extends Controller
{

    public function saveLeadForm(Request $request){

        $index0_key = $request['user_column_data'][0]['column_id'];
        $index0_val = $request['user_column_data'][0]['string_value'];

        $index1_key = $request['user_column_data'][1]['column_id'];
        $index1_val = $request['user_column_data'][1]['string_value'];

        $index2_key = $request['user_column_data'][2]['column_id'];
        $index2_val = $request['user_column_data'][2]['string_value'];

        $index3_key = $request['user_column_data'][3]['column_id'];
        $index3_val = $request['user_column_data'][3]['string_value'];

        $index4_key = $request['user_column_data'][4]['column_id'];
        $index4_val = $request['user_column_data'][4]['string_value'];

        $index5_key = $request['user_column_data'][5]['column_id'];
        $index5_val = $request['user_column_data'][5]['string_value'];

        $index6_key = $request['user_column_data'][6]['column_id'];
        $index6_val = $request['user_column_data'][6]['string_value'];

        $arr_data[$index0_key] = $index0_val;
        $arr_data[$index1_key] = $index1_val;
        $arr_data[$index2_key] = $index2_val;
        $arr_data[$index3_key] = $index3_val;
        $arr_data[$index4_key] = $index4_val;
        $arr_data[$index5_key] = $index5_val;
        $arr_data[$index6_key] = $index6_val;

        $FirstName = (!empty($arr_data['FIRST_NAME']) ? $arr_data['FIRST_NAME'] : "");
        $LastName = (!empty($arr_data['LAST_NAME']) ? $arr_data['LAST_NAME'] : "");
        $PHONE_NUMBER = (!empty($arr_data['PHONE_NUMBER']) ? $arr_data['PHONE_NUMBER'] : "");
        $EMAIL = (!empty($arr_data['EMAIL']) ? $arr_data['EMAIL'] : "");
        $POSTAL_CODE = (!empty($arr_data['POSTAL_CODE']) ? $arr_data['POSTAL_CODE'] : "");
        $OFFER = (!empty($arr_data['OFFER']) ? $arr_data['OFFER'] : "");
        $PREFERRED_CONTACT_METHOD = (!empty($arr_data['PREFERRED_CONTACT_METHOD']) ? $arr_data['PREFERRED_CONTACT_METHOD'] : "");
        $api_version = $request['api_version'];
        $form_id = $request['form_id'];
        $campaign_id = $request['campaign_id'];
        $is_test = $request['is_test'];
        $gcl_id = $request['gcl_id'];
        $adgroup_id = $request['adgroup_id'];
        $creative_id = $request['creative_id'];

        //** to save lead info inside database */
        if($request['google_key'] == "1a9a9a6")
        {
            $LeadForm = new LeadForm();
            $LeadForm->lead_fname = $FirstName;
            $LeadForm->lead_lname = $LastName;
            $LeadForm->lead_email = $EMAIL;
            $LeadForm->lead_phone_number = $PHONE_NUMBER;
            $LeadForm->lead_zipcode = $POSTAL_CODE;
            $LeadForm->offer = $OFFER;
            $LeadForm->preferred_contact_method = $PREFERRED_CONTACT_METHOD;
            $LeadForm->api_version = $api_version;
            $LeadForm->form_id = $form_id;
            $LeadForm->campaign_id = $campaign_id;
            $LeadForm->is_test = $is_test;
            $LeadForm->gcl_id = $gcl_id;
            $LeadForm->adgroup_id = $adgroup_id;
            $LeadForm->creative_id = $creative_id;
            $LeadForm->save();

            return "true";
        } else {
            return "google key invalid";
        }
    }

    public function saveLeadCallTools(Request $request){
        $request->headers->set('Accept', 'application/json');
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_key' => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'zipcode' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'traffic_source' => ['required', 'string', 'max:255'],
            'leadId' => ['required', 'string', 'max:255'],
            'trusted_form' => ['required', 'string', 'max:255']
        ]);

        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => ''
        );

        //Check OF Campaign ID + Key
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code['error'] = 'Invalid campaign_id or campaign_key value';
            return response()->json($response_code);
        }

        $api_validations = new APIValidations();

        // Lead Address ==========================================================================
        $address = array();
        $address_state_id = "";
        $address_zip_state_id = "";

        $is_set_error = 0;
        $error_log = array();
        //Check From Array
        $state_arr =  $api_validations->check_state_array(strtoupper($request['state']));
        if(empty($state_arr)){
            $error_log[] = 'Invalid state value';
            $is_set_error = 1;
        } else {
            $address['state_id'] = $state_arr['state_id'];
            $address['state_arr_id'] = array($state_arr['state_id']);
            $address['state_name'] = $state_arr['state_name'];
            $address['state_code'] = $state_arr['state_code'];
            $address_state_id = $state_arr['state_id'];
        }

        //Check Zipcode From Cache
        $zipcode_arr = $api_validations->check_zipcode_cache($request['zipcode']);
        if( empty($zipcode_arr) ){
            //Check Zipcode From DataBase
            $zipcode_arr = $api_validations->check_zipcode($request['zipcode']);
            if(empty($zipcode_arr)){
                $error_log[] = 'Invalid zipcode value';
                $is_set_error = 1;
            } else {
                $address['zipcode_id'] = $zipcode_arr->zip_code_list_id;
                $address['zipcode_arr_id'] = array($zipcode_arr->zip_code_list_id);
                $address['zipcode_arr_name'] = array($zipcode_arr->zip_code_list);
                $address['zip_code_list'] = $zipcode_arr->zip_code_list;
                $address['county_id'] = $zipcode_arr->county_id;
                $address['city_id'] = $zipcode_arr->city_id;
                $address['city_arr_id'] = array($zipcode_arr->city_id);
                $address['zip_state_id'] = $zipcode_arr->state_id;
                $address_zip_state_id = $zipcode_arr->state_id;

                $counties_arr = DB::table('counties')
                    ->where('county_id', $address['county_id'])
                    ->first();
                $address['county_name'] = $counties_arr->county_name;

                $city_arr = DB::table('cities')
                    ->where('city_id', $address['city_id'])
                    ->first();
                $address['city_name'] = $city_arr->city_name;
            }
        } else {
            $zipcode_arr = $zipcode_arr->getData();
            $address['zipcode_id'] = $zipcode_arr->zip_code_list_id;
            $address['zipcode_arr_id'] = array($zipcode_arr->zip_code_list_id);
            $address['zipcode_arr_name'] = array($zipcode_arr->zip_code_list);
            $address['zip_code_list'] = $zipcode_arr->zip_code_list;
            $address['county_id'] = $zipcode_arr->county_id;
            $address['city_id'] = $zipcode_arr->city_id;
            $address['city_arr_id'] = array($zipcode_arr->city_id);
            $address['zip_state_id'] = $zipcode_arr->state_id;
            $address['county_name'] = $zipcode_arr->county_name;
            $address['city_name'] = $zipcode_arr->city_name;
            $address_zip_state_id = $zipcode_arr->state_id;
        }

        if( $address_state_id != $address_zip_state_id ){
            $error_log[] = 'Invalid Location';
            $is_set_error = 1;
        }

        if( $is_set_error == 1 ){
            $response_code['error'] = $error_log;
            return response()->json($response_code);
        }
        // Lead Address ==========================================================================

        //Check if duplicate lead
        $is_exist1 = LeadsCustomer::where('lead_phone_number', $request->phone)->first();
        $is_exist2 = LeadForm::where('lead_phone_number', $request->phone)->first();
        if (!empty($is_exist1) || !empty($is_exist2)) {
            $response_code['error'] = 'Duplicated Lead';
            return response()->json($response_code);
        }

        //Jornaya ID Validations
        $check_lead_id = $api_validations->check_lead_id($request->leadId);
        if ($check_lead_id == "false") {
            $response_code['error'] = 'Invalid Universal LeadID';
            return response()->json($response_code);
        }

        //Phone Validations
        $phone_validations_msg = $api_validations->phone_validations($request->phone);
        if ($phone_validations_msg != "true") {
            $response_code['error'] = $phone_validations_msg;
            return response()->json($response_code);
        }

        $LeadForm = new LeadForm();

        $LeadForm->lead_fname = $request->first_name;
        $LeadForm->lead_lname = $request->last_name;
        $LeadForm->lead_email = $request->email;
        $LeadForm->lead_phone_number = $request->phone;
        $LeadForm->lead_zipcode = $request->zipcode;
        $LeadForm->offer = $request->service;
        $LeadForm->traffic_source = $request->traffic_source;
        $LeadForm->county = $address['county_name'];
        $LeadForm->city = $address['city_name'];
        $LeadForm->state = $request->state;
        $LeadForm->address = $request->address;
        $LeadForm->trusted_form = $request->trusted_form;
        $LeadForm->leadId = $request->leadId;

        $LeadForm->save();

        //Send an SMS to the lead
        try {
            $content = "Thank you for your submission!\nA representative will contact you shortly!\nYou can also give us a call at: (888) 901-3930 to get your free estimate.";
            $bandwidth_row_arr = array(
                "to" => $request->phone,
                "from" => "+18587271999",
                "text" => $content,
                "applicationId" => "f71d1844-7d8f-4357-8a14-df98c6aa2508"
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://messaging.bandwidth.com/api/v2/users/5007501/messages",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($bandwidth_row_arr),
                CURLOPT_USERPWD => "330ffd5bb952d274f3f3d787db0c3addbf24f0b8d8bd9691" . ':' . "53fae356ab1ff6cbb23d000445776bf6b37a7f08ede6dc32",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } catch (Exception $e) {

        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => ''
        );
        return response()->json($response_code);
    }
}
