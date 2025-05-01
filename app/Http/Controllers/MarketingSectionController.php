<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Http\Controllers\BandWidth\BandWidthController;
use App\Imports\SalesOrder;
use App\Models\ScheduleSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Rest\Client;
use Bitly;

class MarketingSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function sms_index(){
        return view('marketing_sections.send_sms');
    }

    public function pro_sms_index(){
        return view('marketing_sections.send_pro_sms');
    }

    public function sms_submit(Request $request){
        $this->validate($request, [
            'phone_list' => 'required',
            'content' => 'required'
        ]);

        if( $request->hasFile('phone_list') ){
            $dataexcel = Excel::toArray(new SalesOrder(), $request->file('phone_list'));
            $dataexcel1 = $dataexcel[0];
            $dataexcel2 = collect($dataexcel1);
            $dataexcel3 = $dataexcel2->pluck('0');
            $dataexcel4 = json_encode($dataexcel3);
            $dataexcel5 = json_decode($dataexcel4, true);
            $phone_list = $dataexcel5;
        } else {
            \Session::put('error', "Please Insert Phone Numbers List");
            return back();
        }

        $number_of_numbers = 0;
        if( !empty($phone_list) ){
            $number_of_numbers = count($phone_list);
        }

        // Start Bandwidth Send SMS ============================================================================================
        $sms_sent_by = "Bandwidth";
        $content = $request['content'];
        $prefixed_array = preg_filter('/^/', '+1', $phone_list);
        foreach ( $prefixed_array as $number ){
            if(!empty($number)){
                try{
                    $PhoneNumber = trim(str_replace([' ', '(', ')', '-'], '', $number));
                    $bandwidth_row_arr = array(
                        "to" => $PhoneNumber,
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
                    continue;
                }
            }
        }
        // END Bandwidth Send SMS ============================================================================================

        // Twilio ============================================================================================
//        $account_sid = config('services.TWILIO.TWILIO_SID', '');
//        $auth_token = config('services.TWILIO.TWILIO_AUTH_TOKEN', '');
//        $twilio_number = config('services.TWILIO.TWILIO_NUMBER2', '');
//        $sms_sent_by = "Twilio";
//        $body = $request['content'];
//
//        foreach ( $phone_list as $number ){
//            try{
//                $curl = curl_init();
//
//                curl_setopt_array($curl, array(
//                    CURLOPT_URL => "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json",
//                    CURLOPT_RETURNTRANSFER => true,
//                    CURLOPT_ENCODING => '',
//                    CURLOPT_MAXREDIRS => 10,
//                    CURLOPT_TIMEOUT => 0,
//                    CURLOPT_FOLLOWLOCATION => true,
//                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                    CURLOPT_CUSTOMREQUEST => 'POST',
//                    CURLOPT_POSTFIELDS => "To=%2B1$number&Body=$body&From=%2B$twilio_number",
//                    CURLOPT_USERPWD => $account_sid . ':' . $auth_token,
//                    CURLOPT_HTTPHEADER => array(
//                        'Content-Type: application/x-www-form-urlencoded'
//                    ),
//                ));
//
//                $response = curl_exec($curl);
//
//                curl_close($curl);
//
//            } catch (Exception $e) {
//                continue;
//            }
//        }
        // Twilio ==============================================================================================
        $array_data = array(
            "massage" => $request['content'],
            "number Of numbers" => $number_of_numbers,
            "Sent By" => $sms_sent_by
        );

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Send SMS",
            'user_role' => Auth::user()->role_id,
            'section'   => 'SendSMS',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($array_data)
        ]);

        \Session::put('success', "Your Massage has been send successfully");
        return back();
    }

    public function pro_sms_submit(Request $request){
        $this->validate($request, [
            'phone_list' => 'required',
            'content' => 'required',
            'url' => 'required',
            'google_w',
            'google_x',
            'google_y',
            'google_z',
            'google_token',
            'visitor_id'
        ]);

        $msg_url = $request->url."?";
        if( !empty($request->google_w) ){
            $msg_url .= "w=".$request->google_w."&";
        }
        if( !empty($request->google_x) ){
            $msg_url .= "x=".$request->google_x."&";
        }
        if( !empty($request->google_y) ){
            $msg_url .= "y=".$request->google_y."&";
        }
        if( !empty($request->google_z) ){
            $msg_url .= "z=".$request->google_z."&";
        }
        if( !empty($request->google_token) ){
            $msg_url .= "token=".$request->google_token."&";
        }
        if( !empty($request->visitor_id) ){
            $msg_url .= "visitor_id=".$request->visitor_id."&";
        }
        $msg_url = rtrim($msg_url, "&");

        $bitlyUrl = Bitly::getUrl($msg_url);

        if( $request->hasFile('phone_list') ){
            $dataexcel = Excel::toArray(new SalesOrder(), $request->file('phone_list'));
            $dataexcel1 = $dataexcel[0];
            $dataexcel2 = collect($dataexcel1);
            $dataexcel3 = $dataexcel2->pluck('0');
            $dataexcel4 = json_encode($dataexcel3);
            $dataexcel5 = json_decode($dataexcel4, true);
            $phone_list = $dataexcel5;
        } else {
            \Session::put('error', "Please Insert Phone Numbers List");
            return back();
        }

        $number_of_numbers = 0;
        if( !empty($phone_list) ){
            $number_of_numbers = count($phone_list);
        }

        $content = $request['content'];
        if (strpos("-" . $content, "{url}") == true) {
            $content = str_replace("{url}"," $bitlyUrl ", $content);
        } else {
            $content .= " $bitlyUrl";
        }

        $is_schedule = false;
        if(!empty($request->schedule_sms)){
            if(!empty($request->schedule_date)) {
                $sms_sent_by = "Schedule SMS Job";
                $is_schedule = true;
                ScheduleSms::create([
                    'user_id' => Auth::user()->id,
                    'content' => $content,
                    'phone_list' => json_encode($phone_list),
                    'schedule_date' => date("Y-m-d H:i:s", strtotime($request->schedule_date))
                ]);
            } else {
                \Session::put('error', "Please Insert Schedule Date");
                return back();
            }
        }
        else {
            // Start Bandwidth Send SMS ============================================================================================
            $sms_sent_by = "Bandwidth";
            $BandWidth = new BandWidthController();

            $NewNumber = $BandWidth->makeOrder("858");
            $prefixed_array = preg_filter('/^/', '+1', $phone_list);
            foreach ( $prefixed_array as $number ){
                if(!empty($number)){
//                  $BandWidth->SendMessage(array($number), $content, "+1$NewNumber");
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
            // END Bandwidth Send SMS ============================================================================================
        }

        $array_data = array(
            "massage" => $content,
            "number Of numbers" => $number_of_numbers,
            "Sent By" => $sms_sent_by,
            "Schedule Date" => ($is_schedule == true ? date("Y-m-d H:i:s", strtotime($request->schedule_date)) : "Immediately")
        );

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Send Pro SMS",
            'user_role' => Auth::user()->role_id,
            'section'   => 'SendSMS',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($array_data)
        ]);

        if($is_schedule == true){
            \Session::put('success', "Your message has been scheduled to be sent at " . date("Y-m-d H:i:s", strtotime($request->schedule_date)));
        } else {
            \Session::put('success', "Your Massage has been sent successfully");
        }
        return back();
    }

    public function generateURL_index(Request $request){
        return view('marketing_sections.generateURL');
    }

    public function generateURL_submit(Request $request){
        $this->validate($request, [
            'url' => 'required',
            'google_w',
            'google_x',
            'google_y',
            'google_z',
            'google_token',
            'visitor_id'
        ]);

        $msg_url = $request->url."?";
        if( !empty($request->google_w) ){
            $msg_url .= "w=".$request->google_w."&";
        }
        if( !empty($request->google_x) ){
            $msg_url .= "x=".$request->google_x."&";
        }
        if( !empty($request->google_y) ){
            $msg_url .= "y=".$request->google_y."&";
        }
        if( !empty($request->google_z) ){
            $msg_url .= "z=".$request->google_z."&";
        }
        if( !empty($request->google_token) ){
            $msg_url .= "token=".$request->google_token."&";
        }
        if( !empty($request->visitor_id) ){
            $msg_url .= "visitor_id=".$request->visitor_id."&";
        }
        $msg_url = rtrim($msg_url, "&");

        $bitlyUrl = Bitly::getUrl($msg_url);

        $array_data = array(
            "URL" => $msg_url,
            "bitly Url" => $bitlyUrl
        );

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Generate Bitly URL",
            'user_role' => Auth::user()->role_id,
            'section'   => 'SendSMS',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($array_data)
        ]);

        \Session::put('success', "Bitly URL has been generated successfully, here's the URL: $bitlyUrl");
        return back();
    }
}
