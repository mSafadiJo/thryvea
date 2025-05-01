<?php

namespace App\Http\Controllers\Api\CallLeads;

use App\Http\Controllers\Controller;
use App\Models\CallLeads;
use App\Services\ApiMain;
use Illuminate\Http\Request;

class CallLeadsController extends Controller
{
    public function storeCallLeads(Request $request){
        $request->headers->set('Accept', 'application/json');
        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => ''
        );

        //Check OF Campaign ID + Key ========================================================================
        if( !($request->campaign_id == config('services.ApiLead.API_Campaign_ID', '') &&
            $request->campaign_key == config('services.ApiLead.API_Campaign_Key', '')) ){
            $response_code['error'] = 'Invalid campaign_id or campaign_key value';
            return response()->json($response_code);
        }
        //===================================================================================================

        if(!empty($request->is_verified_phone)){
            $CallLeads_exist = CallLeads::where('phone_number', $request->phone_number)
                ->where('is_verified_phone', 1)
                ->where('status', 0)
                ->where('universal_leadid', $request->universal_leadid)
                ->where('created_at', '>=', date('Y-m-d', strtotime("-7 day")) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
                ->first();

            if(empty($CallLeads_exist)) {
                CallLeads::where('phone_number', $request->phone_number)
                    ->where('status', 0)
                    ->where('universal_leadid', $request->universal_leadid)
                    ->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
                    ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
                    ->update(['is_verified_phone' => 1]);

                //Server to server Conversion =================================================================
                //For Roy (Popunder)
                if( strtolower(substr($request['tc'], 0, 2)) == 'vr' ){
                    if( !empty($request['k']) ){
                        $token_data_conv = $request['k'];
                        $url_conv = "http://ad.propellerads.com/conversion.php?aid=874155&pid=&tid=93273&visitor_id=$token_data_conv";

                        $main_api_file = new ApiMain();
                        $main_api_file->server_to_server_conv($url_conv);
                    }
                }
                //=============================================================================================
            }
            $response_code = array(
                'response_code' => 'true',
                'message' => 'Lead Verified',
                'error' => ''
            );

            return json_encode($response_code);
        }

        //TCPA ==============================================================================================
        $tcpa_compliant = 1;
        if( !empty($request->tcpa_consent_text) ){
            $tcpa_consent_text = $request->tcpa_consent_text;
        } else {
            $tcpa_consent_text = "By clicking the finish button and submitting this form, you are providing your electronic signature in which you consent, acknowledge, and agree to this website's Privacy Policy and Terms And Conditions. You also hereby consent to receive marketing communications via automated telephone dialing systems and/or pre-recorded calls, text messages, and/or emails from our Premiere Partners and marketing partners at the phone number, physical address and email address provided above, with offers regarding the requested Home service. This is also a consent to receive communications even if you are on any State and/or Federal Do Not Call list. Consent is not a condition of purchase and may be revoked at any time. Message and data rates may apply. California Residents Privacy Notice.";
        }
        //TCPA ==============================================================================================

        //Check If Duplicated Lead =========================================================================
        $is_sold_duplicate = CallLeads::where('status', 0)
            ->where('phone_number', $request['phone_number'])
            ->where('created_at', '>=', date('Y-m-d', strtotime("-7 day")) . ' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
            ->first();
        //===================================================================================================

        //Store Call Leads ==================================================================================
        $lead = new CallLeads();

        $lead->first_name = $request['fname'];
        $lead->last_name = $request['lname'];
        $lead->address = $request['street_name'];
        $lead->email = $request['email'];
        $lead->phone_number = $request['phone_number'];
        $lead->service_id = $request['service_id'];

        $lead->state_id = $request['state_id'];
        $lead->city_id = $request['city_id'];
        $lead->zipcode_id = $request['zipcode_id'];
        $lead->county_id = $request['county_id'];

        $lead->lead_serverDomain = $request['serverDomain'];
        $lead->lead_timeInBrowseData = $request['timeInBrowseData'];
        $lead->lead_ipaddress = $request['ipaddress'];
        $lead->lead_FullUrl = $request['FullUrl'];
        $lead->lead_browser_name = $request['browser_name'];
        $lead->lead_aboutUserBrowser = $request['aboutUserBrowser'];
        $lead->lead_website = $request['lead_website'];

        $lead->google_ts = $request['tc'];
        $lead->google_c = $request['c'];
        $lead->google_g = $request['g'];
        $lead->google_k = $request['k'];
        $lead->token = $request['token'];
        $lead->visitor_id = $request['visitor_id'];
        $lead->google_gclid = $request['gclid'];

        $lead->tcpa_compliant = $tcpa_compliant;
        $lead->tcpa_consent_text = $tcpa_consent_text;
        $lead->trusted_form = $request['trusted_form'];
        $lead->universal_leadid = $request['universal_leadid'];

        if (!empty($is_sold_duplicate)) {
            $lead->is_duplicate_lead = 1;
        }

        $lead->created_at = date('Y-m-d H:i:s');

        $lead->save();
        //==================================================================================================

        //Check if test lead ===============================================================================
        $is_test = 0;
        if( strtolower($request['fname']) == 'test' || strtolower($request['lname']) == 'test'
            || strtolower($request['fname']) == 'testing' || strtolower($request['lname']) == 'testing'){
            $is_test = 1;
        }
        //Check if test lead ===============================================================================

        //Send Lead To New Call Tools: =====================================================================
        if (empty($is_sold_duplicate) && $is_test == 0) {
            $calltoolsURL = "https://app.calltools.io/api/contacts/";
            $callToolsHeaders = array(
                'Authorization: Token 1a57afa10902c4edd98851d79a84021c16393b33',
                'Content-Type: application/json',
                'Accept: application/json'
            );

            switch ($request['service_id']){
                case 1:
                    $service_name = "Windows";
                    break;
                case 2:
                    $service_name = "Solar";
                    break;
                case 3:
                    $service_name = "Home Security";
                    break;
                case 4:
                    $service_name = "Flooring";
                    break;
                case 5:
                    $service_name = "WALK-IN TUBS";
                    break;
                case 6:
                    $service_name = "Roofing";
                    break;
                case 7:
                    $service_name = "Home Siding";
                    break;
                case 8:
                    $service_name = "Kitchen";
                    break;
                case 9:
                    $service_name = "Bathroom";
                    break;
                default:
                    $service_name = "";
            }

            $date = date('Y-m-d');
            $callToolsBody = array(
                "Name" => $request['fname'] . " " . $request['lname'],
                "first_name" => $request['fname'],
                "last_name" => $request['lname'],
                "home_phone_number" => $request['phone_number'],
                "mobile_phone_number" => $request['phone_number'],
                "jornaya_id" => $request['universal_leadid'],
                "service" => $service_name,
                "website" => $request['serverDomain'],
                "lead_source" => "Website Call Leads",
                "data" => array(
                    "ready" => true,
                    "status" => "string",
                    "do_not_contact" => false,
                    "first_name" => $request['fname'],
                    "last_name" => $request['lname'],
                    "address" => "string",
                    "city" => "string",
                    "state" => "string",
                    "zip_code" => "string",
                    "county" => "string",
                    "country" => "string",
                    "time_zone" => "string",
                    "suppress_until" => $date . "T13=>10=>03.329Z",
                    "last_call_outbound" => $date . "T13=>10=>03.329Z",
                    "last_call_outbound_human" => $date . "T13=>10=>03.329Z",
                    "last_call_outbound_machine" => $date . "T13=>10=>03.329Z",
                    "last_call_inbound" => $date . "T13=>10=>03.329Z",
                    "last_sms_sent" => $date . "T13=>10=>03.329Z",
                    "last_sms_received" => $date . "T13=>10=>03.329Z",
                    "last_email_sent" => $date . "T13=>10=>03.329Z",
                    "calls_outbound" => 0,
                    "calls_outbound_human" => 0,
                    "calls_outbound_machine" => 0,
                    "calls_inbound" => 0,
                    "sms_sent" => 0,
                    "emails_sent" => 0,
                    "consecutive_no_contact" => 0,
                    "owned_by" => "3fa85f64-5717-4562-b3fc-2c963f66afa6",
                    "user_queue" => 0,
                    "tags" => [
                        0
                    ],
                    "add_tags" => [
                        0
                    ],
                    "remove_tags" => [
                        0
                    ],
                    "buckets" => [
                        0
                    ],
                    "add_buckets" => [
                        0
                    ],
                    "remove_from_other_buckets_on_add" => false,
                    "remove_buckets" => [
                        0
                    ],
                    "call_uuid" => "3fa85f64-5717-4562-b3fc-2c963f66afa6",
                    "_creation_reporting_attrs" => array(),
                    "_update_reporting_attrs" => array(),
                    "appointment_set" => $date . "T13=>10=>03.329Z",
                    "bill_discount" => true,
                    "birthday" => "string",
                    "call_disposition_id" => 0,
                    "callerphonenumber" => "string",
                    "campaigns" => "string",
                    "car_model" => "string",
                    "credit_score" => "string",
                    "crm_id" => "string",
                    "disposition_id" => 0,
                    "driver_experience" => "string",
                    "dui_charges" => "string",
                    "electric_provider" => "string",
                    "genders" => "string",
                    "home_phone_number" => $request['phone_number'],
                    "home_value" => "string",
                    "jornaya_id" => $request['universal_leadid'],
                    "lead_source" => "Website Call Leads",
                    "license" => "string",
                    "married" => "string",
                    "mobile_phone_number" => $request['phone_number'],
                    "monthly_bill" => "string",
                    "multivehicle" => "string",
                    "ownership" => "string",
                    "personal_email_address" => "string",
                    "power_solution" => "string",
                    "project_nature" => "string",
                    "property_type" => "string",
                    "roof_shading" => "string",
                    "roof_type" => "string",
                    "service" => $service_name,
                    "sr_22_need" => "string",
                    "start_time" => "string",
                    "taxablincome" => "string",
                    "transactionid" => "string",
                    "trusted_form" => "string",
                    "vehiclemake" => "string",
                    "vehicleyear" => "string",
                    "website" => $request['serverDomain'],
                    "window_number" => "string"
                )
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $calltoolsURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($callToolsBody),
                CURLOPT_HTTPHEADER => $callToolsHeaders,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        }
        //==================================================================================================

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Lead Accepted',
            'error' => ''
        );

        return json_encode($response_code);
    }
}
