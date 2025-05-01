<?php

namespace App\Http\Controllers\BandWidth;

use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use Illuminate\Http\Request;
use BandwidthLib;
use Illuminate\Support\Facades\Log;

class BandWidthController extends Controller
{

    public function saveToEnv($NewNumber = "" , $LeadPhone = ""){
        $this->overWriteEnvFile("NewNumber", $NewNumber);
        $this->overWriteEnvFile("LeadPhone", $LeadPhone);
    }

    public function overWriteEnvFile($type, $val){
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"'.trim($val).'"';
            if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                file_put_contents($path, str_replace(
                    $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                ));
            } else {
                file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            }
        }
    }

    public function SendMessage($toNumber = "" , $content = "", $fromNumber = "")
    {
        $config = new BandwidthLib\Configuration(
            array(
                'messagingBasicAuthUserName' => '330ffd5bb952d274f3f3d787db0c3addbf24f0b8d8bd9691',
                'messagingBasicAuthPassword' => '53fae356ab1ff6cbb23d000445776bf6b37a7f08ede6dc32',
            )
        );

        $client = new BandwidthLib\BandwidthClient($config);

        $messagingClient = $client->getMessaging()->getClient();

        $messagingAccountId = "5007501";

        $body = new BandwidthLib\Messaging\Models\MessageRequest();
        $body->from = $fromNumber;
        $body->to = $toNumber;
        $body->text = $content;

        if( !empty($fromNumber) ){
            $body->from = $fromNumber;
            $body->applicationId = "354cc71c-e484-4410-810f-0d0f43ce52f9";
        } else {
            if(config('app.name') == 'Zone1Remodeling'){
                $body->from = "+12086842399";
            } else {
                $body->from = "+18582470911";
            }
            $body->applicationId = "f71d1844-7d8f-4357-8a14-df98c6aa2508";
        }

        try {
            $response = $messagingClient->createMessage($messagingAccountId, $body);
//             echo "<pre>";
//             print_r($response);
        } catch (Exception $e) {
            // print_r($e);
        }
    }

    public function forward(Request $request){
        $leadNumberNEW = $request->to;
        $leadNumberNEW = substr($leadNumberNEW, -10);
        $original_number = LeadsCustomer::where('band_width_new_number', $leadNumberNEW)
            ->orderby('created_at', 'DESC')->first(['lead_phone_number']);
        if( !empty($original_number) ){
            $leadNumberOLD = $original_number->lead_phone_number;
        } else {
            return false;
        }

        $speakSentence = new BandwidthLib\Voice\Bxml\SpeakSentence("please wait");  //The message the caller hears
        $speakSentence->voice("paul");

        $number = new BandwidthLib\Voice\Bxml\PhoneNumber("+1".$leadNumberOLD); //The phone to which the call will be transferred
        $transfer = new BandwidthLib\Voice\Bxml\Transfer();

        $startRecording = new BandwidthLib\Voice\Bxml\StartRecording();
        $startRecording->recordingAvailableUrl("https://".$_SERVER['SERVER_NAME']."/api/addRecord"); //Receive call recording information

        $transfer->transferCallerId("+1".$leadNumberNEW); //The phone through which the call is being transferred
        $transfer->phoneNumbers(array($number));

        $response = new BandwidthLib\Voice\Bxml\Response();
        $response->addVerb($speakSentence);
        $response->addVerb($startRecording);
        $response->addVerb($transfer);
        return $response->toBxml();
    }

    public function makeOrder($area){
        $login = "salam@globalconsultantspro.com";
        $password = "Xk+HKbos18mm8@U~pSP@KLc";
        $your_account_id = "5007501";
        $client = new \Iris\Client($login, $password, ['url' => 'https://dashboard.bandwidth.com/api/']);
        $account = new \Iris\Account($your_account_id, $client);
        $order = $account->orders()->create([
            "Name" => "Available Telephone Number order",
            "SiteId" => "47357",
            "CustomerOrderId" => "123456789",
            "AreaCodeSearchAndOrderType" => [
                "Quantity" => "1",
                "AreaCode" => $area,
            ]
        ]);
        sleep(10);
        /// for test ///
//        $response = $account->orders()->order("e0438c51-7607-4291-aa75-9cc46adf71e0" , true); // tndetail=true
        $response = $account->orders()->order($order->id, true);
        $OrderStatus = $response->OrderStatus;
        if($OrderStatus == "COMPLETE") {
            $fullNumber = $response->CompletedNumbers->TelephoneNumber['FullNumber'] ;
        } else {
            $fullNumber = "";
        }
        return $fullNumber ;
    }

}
