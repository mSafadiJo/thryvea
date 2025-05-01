<?php

namespace App\Http\Controllers\Api\Jobs;

use App\AccessLog;
use App\Http\Controllers\BandWidth\BandWidthController;
use App\Http\Controllers\Controller;
use App\Models\ScheduleSms;
use App\User;

class ScheduleSmsController extends Controller
{
    public function __construct(){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index(){
        $current_date = date("Y-m-d H:i:s");
        //Get All Schedule SMS
        $schedule_sms = ScheduleSms::where('is_sent', 0)->where('schedule_date', '<=', $current_date)->get();

        if(!empty($schedule_sms)){
            foreach ($schedule_sms as $val){
                $phone_list = json_decode($val->phone_list, true);
                $content = $val->content;
                $user_id = $val->user_id;

                //Get User info
                $user_info = User::where('id', $user_id)->first(["role_id", "username"]);

                $number_of_numbers = 0;
                if( !empty($phone_list) ){
                    $number_of_numbers = count($phone_list);
                }

                if( config('app.name', '') == "Zone1Remodeling" ) {
                    // Twilio ============================================================================================
                    $account_sid = config('services.TWILIO.TWILIO_SID', '');
                    $auth_token = config('services.TWILIO.TWILIO_AUTH_TOKEN', '');
                    //$twilio_number = config('services.TWILIO.TWILIO_NUMBER2', '');
                    $twilio_number = "18582992205";
                    $sms_sent_by = "Twilio";

                    foreach ($phone_list as $number) {
                        if (!empty($number)) {
                            try {
                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => "To=%2B1$number&Body=$content&From=%2B$twilio_number",
                                    CURLOPT_USERPWD => $account_sid . ':' . $auth_token,
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/x-www-form-urlencoded'
                                    ),
                                ));

                                $response = curl_exec($curl);

                                curl_close($curl);
                            } catch (Exception $e) {
                                continue;
                            }
                        }
                    }
                    // Twilio ==============================================================================================
                } else {
                    //Start Bandwidth Send SMS ============================================================================================
                    $sms_sent_by = "Bandwidth";
                    //$BandWidth = new BandWidthController();
                    //$NewNumber = $BandWidth->makeOrder("208");
                    $NewNumber = "2084080187";
                    //(208) 408-0187
                    $prefixed_array = preg_filter('/^/', '+1', $phone_list);

                    foreach ($prefixed_array as $number) {
                        if (!empty($number)) {
                            //$BandWidth->SendMessage(array($number), $content, "+1$NewNumber");
                            try {
                                $PhoneNumber = trim(str_replace([' ', '(', ')', '-'], '', $number));
                                $bandwidth_row_arr = array(
                                    "to" => $PhoneNumber,
                                    "from" => "+1$NewNumber",
                                    "text" => $content,
                                    "applicationId" => "354cc71c-e484-4410-810f-0d0f43ce52f9"
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
                                continue;
                            }
                        }
                    }
                    //END Bandwidth Send SMS ============================================================================================
                }

                //Edit ScheduleSms
                ScheduleSms::where('id', $val->id)
                    ->update([
                        "is_sent" => 1,
                        "send_date" => $current_date
                    ]);

                $array_data = array(
                    "massage" => $content,
                    "number Of numbers" => $number_of_numbers,
                    "Sent By" => $sms_sent_by,
                    "Schedule Date" => date("Y-m-d H:i:s", strtotime($val->schedule_date))
                );

                AccessLog::create([
                    'user_id' => $user_id,
                    'user_name' => $user_info->username,
                    'section_id' => 1,
                    'section_name' => "Send Pro SMS",
                    'user_role' => $user_info->role_id,
                    'section'   => 'SendSMS',
                    'action'    => 'Created',
                    'ip_address' => "",
                    'location' => "",
                    'request_method' => json_encode($array_data)
                ]);
            }
        }
    }
}
