<?php

namespace App\Services;

use App\CrmResponse;
use App\Models\CrmResponsePing;

class CrmApi {
    public function api_send_data($url, $httpheader, $leadsCustomerCampaign_id = '', $Lead_data_array = '', $method="GET", $is_return = 0, $campaign_id, $from_job = 0){
        $url = str_replace("#","%23",$url);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $Lead_data_array,
            CURLOPT_HTTPHEADER => $httpheader,
        ));

        $response = curl_exec($curl);

        $curl_info = curl_getinfo($curl);

        if( $from_job == 0 ){
            if( $is_return == 2 ){
                $CrmResponse = new CrmResponsePing();
                $CrmResponse->lead_id = $leadsCustomerCampaign_id;
            } else if( $is_return == 3 ){
                $CrmResponse = new CrmResponsePing();
                $CrmResponse->ping_id = $leadsCustomerCampaign_id;
            } else {
                $CrmResponse = new CrmResponse();
                $CrmResponse->campaigns_leads_users_id = $leadsCustomerCampaign_id;
            }

            $CrmResponse->response = $response;
            $CrmResponse->campaign_id = $campaign_id;
            $CrmResponse->url = $url;
            $CrmResponse->inputs = (is_array($Lead_data_array) ? json_encode($Lead_data_array) : $Lead_data_array);
            $CrmResponse->method = $method;
            $CrmResponse->httpheader = json_encode($httpheader);
            $CrmResponse->time = $curl_info['total_time'];

            $CrmResponse->save();
        }

        if( $is_return != 0 ){
            return $response;
        }
    }

    public function send_multi_ping_apis($campaign_responses){
        $ping_post_arr = array();
        $campaigns_arr = array();
        $data_crm_responses = array();
        $save_response = 1;

        $master = curl_multi_init();
        foreach ($campaign_responses as $key => $item){
            $url = $item['url'];
            $header = $item['header'];
            $inputs = $item['inputs'];
            $method = $item['method'];

            $curl_arr[$key] = curl_init();

            curl_setopt($curl_arr[$key], CURLOPT_URL, $url);
            curl_setopt($curl_arr[$key], CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl_arr[$key], CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl_arr[$key], CURLOPT_POSTFIELDS, $inputs);
            curl_setopt($curl_arr[$key], CURLOPT_PRIVATE, $key);
            curl_setopt($curl_arr[$key], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_arr[$key], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl_arr[$key], CURLOPT_HEADER,0);
            curl_setopt($curl_arr[$key], CURLOPT_TIMEOUT, 15);
            curl_setopt($curl_arr[$key], CURLOPT_ENCODING, "");
            curl_setopt($curl_arr[$key], CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl_arr[$key], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            curl_multi_add_handle($master, $curl_arr[$key]);
        }

        $running = null;
        do {
            curl_multi_exec($master,$running);
            curl_multi_select($master);

            while($done = curl_multi_info_read($master)) {
                $result = curl_multi_getcontent($done['handle']);
                $time = curl_getinfo($done['handle'], CURLINFO_TOTAL_TIME );
                $key = curl_getinfo($done['handle'], CURLINFO_PRIVATE);

                $url = $campaign_responses[$key]['url'];
                $header = $campaign_responses[$key]['header'];
                $inputs = $campaign_responses[$key]['inputs'];
                $method = $campaign_responses[$key]['method'];
                $lead_id = $campaign_responses[$key]['lead_id'];
                $campaign_id = $campaign_responses[$key]['campaign_id'];
                $user_id = $campaign_responses[$key]['user_id'];
                $crm_type = $campaign_responses[$key]['crm_type'];
                $returns_data = $campaign_responses[$key]['returns_data'];
                $service_id = $campaign_responses[$key]['service_id'];
                $campaign_details = $campaign_responses[$key]['campaign_details'];
                $save_response = ($returns_data == 3 ? 0 : $save_response);

                $current_date = date("Y-m-d H:i:s");
                $data_crm_responses[] = [
                    'lead_id' => ($returns_data == 2 ? $lead_id : ""),
                    'ping_id' => ($returns_data == 3 ? $lead_id : ""),
                    'response' => $result,
                    'campaign_id' => $campaign_id,
                    'url' => $url,
                    'inputs' => (is_array($inputs) ? json_encode($inputs) : $inputs),
                    'method' => $method,
                    'httpheader' => json_encode($header),
                    'time' => $time,
                    'created_at' => $current_date,
                    'updated_at' => $current_date
                ];

                $TransactionId = '';
                $Payout = 0;
                $Result = 0;
                $multi_type = 0;

                //prepare and filter the responses
                if( config('app.name', '') == "Zone1Remodeling" ){
                    switch($crm_type){
                        case 3:
                            //Leads Pedia
                            $result2 = json_decode($result, true);
                            if (!empty($result2['result'])) {
                                if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                                    $TransactionId = $result2['ping_id'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                            break;
                        case 7:
                            //Jangle
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
                            break;
                        case 12:
                            //Lead Portal
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
                            break;
                        case 13:
                            //leads pedia track
                            $result2 = json_decode($result, true);
                            if (!empty($result2['result'])) {
                                if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                                    $TransactionId = $result2['ping_id'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                            break;
                        default:
                            switch ($user_id){
                                case 11:
                                    //Allied Digital Media 11
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['response_code'])) {
                                        if ($result2['response_code'] == "true") {
                                            $TransactionId = $result2['transaction_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 312:
                                    //Astoria 312
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
                                    break;
                            }
                    }
                }
                else {
                    switch($crm_type){
                        case 3:
                            //Leads Pedia
                            $result2 = json_decode($result, true);
                            if (!empty($result2['result'])) {
                                if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                                    $TransactionId = $result2['ping_id'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                            break;
                        case 7:
                            //Jangle
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
                            break;
                        case 12:
                            //Lead Portal
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
                            break;
                        case 13:
                            //leads pedia track
                            $result2 = json_decode($result, true);
                            if (!empty($result2['result'])) {
                                if ($result2['result'] == 'success' || $result2['msg'] == 'Ping Accepted') {
                                    $TransactionId = $result2['ping_id'];
                                    $Payout = $result2['price'];
                                    $multi_type = 0;
                                    $Result = 1;
                                }
                            }
                            break;
                        default:
                            switch ($user_id){
                                case 51:
                                    //PX API 51
                                    switch ($service_id) {
                                        case 24:
                                        case 5:
                                            //Auto Insurance
                                            //WALK-IN TUBS
                                            $result2 = json_decode($result, true);
                                            if (!empty($result2['Success'])) {
                                                if ($result2['Success'] == "true") {
                                                    $TransactionId = $result2['TransactionId'];
                                                    $Payout = $result2['Payout'];
                                                    $multi_type = 0;
                                                    $Result = 1;
                                                }
                                            }
                                            break;
                                        default:
                                            $result2 = json_decode($result, true);
                                            if (!empty($result2)) {
                                                if (!empty($result2['Result'])) {
                                                    if ($result2['Result'] == "BaeOK") {
                                                        $TransactionId = $result2['TransactionID'];
                                                        $Payout = $result2['Payout'];
                                                        $multi_type = 0;
                                                        $Result = 1;
                                                    }
                                                }
                                            }
                                    }
                                    break;
                                case 38:
                                    //Astoria
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
                                    break;
                                case 72:
                                    //Adopt A Contractor
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
                                    break;
                                case "OldHomeAdviser":
                                    //home advisor
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'success') == true) {
                                                $TransactionId = $result4['bidKey'];
                                                $Payout = $result4['bidAmount'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 103:
                                    //Quinstreet 103
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if (!empty($result2['STATUS'])) {
                                            if ($result2['STATUS'] == "SUCCESS") {
                                                $TransactionId = $result2['PINGTOKEN'];
                                                $Payout = $result2['PRICE'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 15:
                                    //RGR Marketing	15
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "matched") {
                                            $TransactionId = $result2['ping_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 146:
                                    // ICW Leads
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'Accepted') == true) {
                                                $TransactionId = $result4['PingID'];
                                                $Payout = $result4['Price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 78:
                                    //Sun Run / Clean Energy 78
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if (!empty($result2['status'])) {
                                            if ($result2['status'] == "PING_VALID") {
                                                $TransactionId = $result2['id'];
                                                $Payout = $result2['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 208:
                                    //Clean Energy Authoroty 208
                                    switch ($service_id) {
                                        case 1:
                                            //Windows
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
                                            break;
                                        case 2:
                                            //Solar
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
                                            break;
                                        case 3:
                                            //Home Security
                                            $result2 = json_decode($result, true);
                                            if (!empty($result2['response']['status'])) {
                                                if ($result2['response']['status'] == "Matched") {
                                                    $TransactionId = $result2['response']['lead_id'];
                                                    $Payout = $result2['response']['price'];
                                                    $multi_type = 0;
                                                    $Result = 1;
                                                }
                                            }
                                            break;
                                        case 6:
                                            //Roofing
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
                                            break;
                                    }
                                    break;
                                case 75:
                                    //home advisor New
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['success'])) {
                                        if ($result2['success'] == "true") {
                                            $TransactionId = $result2['cjLeadKey'];
                                            $Payout = $result2['price'];
                                            //$Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 280:
                                    //eLocal 280
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['response'])) {
                                        $result3 = $result2['response'];
                                        if ($result3['status'] == "success") {
                                            $TransactionId = $result3['token'];
                                            $Payout = $result3['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 321:
                                    //digital media solutions The DMS Group	321
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);
                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'accepted') == true) {
                                                $TransactionId = $result4['resvcode'];
                                                $Payout = $result4['payout'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 345:
                                    //Ping Tree System (where is my contractor) 345
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);
                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'Accepted') == true) {
                                                $TransactionId = $result4['Pingid'];
                                                $Payout = $result4['Price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 373:
                                    //Bishamon Media 373
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);
                                        if (!empty($result4)) {
                                            if (strpos("-" . $result, 'true') == true) {
                                                $TransactionId = $result4['OrderID'];
                                                $Payout = $result4['EstimatedPrice'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 454:
                                    //Solar Lead Vision	454
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['status'] == "success") {
                                            $TransactionId = $result2['ping_key'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 525:
                                    //intel house marketing 525
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['status'] == "success") {
                                            $TransactionId = $result2['lead_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 152:
                                    //Porch 152
                                    $result2 = json_decode($result, true);
                                    if(!empty($result2['referenceId']) && !empty($result2['exclusivePayout']['price'])){
                                        $TransactionId = $result2['referenceId'];
                                        $Payout = $result2['exclusivePayout']['price'];
                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                    break;
                                case 536:
                                    //Billy.com 536
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "continue") {
                                            $TransactionId = $result2['promise'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 583:
                                    //Adventum LLC 583
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "success") {
                                            $TransactionId = $result2['bid']['bid_id'];
                                            $Payout = $result2['bid']['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 567:
                                    //TBG Traffic Exchange 567
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == 1) {
                                            $TransactionId = $result2['lead_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 588:
                                    //1800 remodel 588
                                    if (!str_contains($result, 'REJECTED') && str_contains($result, 'bid')) {
                                        $divid = explode("&",$result);
                                        $bidExctract = explode("=", $divid[0]);
                                        $bid = $bidExctract[1];
                                        $bid_keyExtract = explode("=", $divid[1]);
                                        $bid_key = $bid_keyExtract[1];
                                        $TransactionId = $bid_key;
                                        $Payout = $bid;
                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                    break;
                                case 585:
                                    //Avenge Digital lead 585
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if (!empty($result2['Result'])) {
                                            if ($result2['Result'] == "Ok") {
                                                $TransactionId = $result2['PingId'];
                                                $Payout = $result2['Payout'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 630:
                                    //Zone 1 Remodeling 630
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if (!empty($result2['response_code'])) {
                                            if ($result2['response_code'] == "true") {
                                                $TransactionId = $result2['transaction_id'];
                                                $Payout = $result2['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 660:
                                    // ecrux 660
                                    if (str_contains(strtolower($result) , "success")){
                                        $bidLeadIDExctract = explode("|" , $result);
                                        $leadIDecrux = $bidLeadIDExctract[1];
                                        $bid = $bidLeadIDExctract[2];
                                        $TransactionId = $leadIDecrux;
                                        $Payout = $bid;
                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                    break;
                                case 745:
                                    //Networx 745
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);
                                        if (!empty($result4['statusCode'])) {
                                            if ($result4['statusCode'] == 200) {
                                                $TransactionId = $result4['token'];
                                                $Payout = $result4['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 773:
                                    //ContractorClicks.com 773
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['status'] == "success") {
                                            $TransactionId = $result2['cc_lead_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 707:
                                    //Four J's Media 707
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4['status'])) {
                                            if ($result4['status'] == "Success" || $result4['status'] == "Matched") {
                                                $TransactionId = $result4['lead_id'];
                                                $Payout = $Lead_Cost;
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 760:
                                    //HELLO PROJECT USA 760
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['response'])) {
                                        $result3 = $result2['response'];
                                        if (!empty($result3['status'])) {
                                            if ($result3['status'] == "Success") {
                                                $TransactionId = $result3['lead_id'];
                                                $Payout = $result3['price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 844:
                                    //Alpine Digital Group, Inc. 844
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['success'] == "true") {
                                            $TransactionId = $result2['pingId'];
                                            $Payout = $result2['payout'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 849:
                                    //FusedLeads 849
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
                                    break;
                                case 851:
                                    //RingPartner 851
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "ok") {
                                            $TransactionId = $result2['lead_id'];
                                            $Payout = $result2['bid'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 970:
                                    //AIQOO  970
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['bidKey']) && !empty($result2['bidPrice']) && !empty($result2['resMessage'])) {
                                        if ($result2['resMessage'] == "ok") {
                                            $TransactionId = $result2['bidKey'];
                                            $Payout = $result2['bidPrice'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 928:
                                    //LeadLabx 928
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "continue") {
                                            $TransactionId = $result2['promise'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1031:
                                    //Empire Media 1031
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
                                    break;
                                case 1107:
                                    //MKT Remodel 1107
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "Success") {
                                            $TransactionId = $result2['ping_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1135:
                                    //HomeAppointments 1135
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
                                    break;
                                case 1208:
                                    //ExchangeFlo 1208
                                    $unique = $lead_id . $campaign_id;
                                    $result2 = json_decode($result, true);
                                    if(!empty($result2['status'])) {
                                        if($result2['status'] == "success" && !empty($result2['pings'][0])){
                                            $result3 = $result2['pings'][0];
                                            $TransactionId = $result3['ping_id'] . "_" . $result2['submission_id'] . "_" . $unique;
                                            $Payout = $result3['value'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1246:
                                    //VIP Response B.V 1246
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['response'])) {
                                        $result3 = $result2['response'];
                                        if (!empty($result3['status'])) {
                                            if ($result3['status'] == "200") {
                                                $TransactionId = $result3['seller_lead_id'];
                                                $Payout = $result3['bid'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    }
                                    break;
                                case 1273:
                                    //Markytek 1273
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "ACCEPTED") {
                                            $TransactionId = $result2['ping_id'];
                                            $Payout = $result2['bids'][0]['payout'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1291:
                                    //Energy Pal 1291
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['status'])) {
                                        if ($result2['status'] == "accepted") {
                                            $TransactionId = $result2['ping_id'];
                                            $Payout = $result2['price'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1302:
                                    // 1302	Morson
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['success'] == "true"){
                                            $TransactionId = $result2['lead_token'];
                                            $Payout = $result2['payout'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1296:
                                    // 1296	Blue Fire Leads
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2)) {
                                        if ($result2['data']['status'] == "Accepted"){
                                            $TransactionId = $result2['data']['pingId'];
                                            $Payout = $result2['data']['bidPrice'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 1300:
                                    // 1300	iRadius Group
                                    try {
                                        libxml_use_internal_errors(true);
                                        $result2 = simplexml_load_string($result);
                                        $result3 = json_encode($result2);
                                        $result4 = json_decode($result3, TRUE);

                                        if (!empty($result4)) {
                                            if ($result4['Status'] == "Accepted") {
                                                $TransactionId = $result4['LeadId'];
                                                $Payout = $result4['Price'];
                                                $multi_type = 0;
                                                $Result = 1;
                                            }
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;
                                case 375:
                                    // 	Blue ink digital
                                    $result2 = json_decode($response, true);
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
                                    break;

                            }
                    }
                }

                //Check if success response (accepted ping leads)
                if ($Result == 1) {
                    //Check if Payout grater $2
                    if ( !($Payout < 2 || is_numeric($Payout) != 1) ) {
                        //Check Total amount vs lead price
                        $payment_type_method_status = $campaign_details->payment_type_method_status;
                        $payment_type_method_limit = filter_var($campaign_details->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                        $payment_type_method_id = $campaign_details->payment_type_method_id;
                        $totalAmmountUser_value = ( !empty($campaign_details->total_amounts_value) ? $campaign_details->total_amounts_value : 0 );

                        $data_is_identical = 0;
                        if( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']) ){
                            if (abs($totalAmmountUser_value - $Payout) <= $payment_type_method_limit) {
                                $data_is_identical = 1;
                            }
                        } else {
                            if( $totalAmmountUser_value >= $Payout && $totalAmmountUser_value > 0 && $Payout > 0 ){
                                $data_is_identical = 1;
                            }
                        }

                        if($data_is_identical == 1){
                            $ping_post_arr["campaign-$campaign_id"] = array(
                                'TransactionId' => $TransactionId,
                                'Payout' => $Payout,
                                'Result' => $Result,
                                'multi_type' => $multi_type,
                                'campaign_id' => $campaign_id
                            );

                            $campaign_details->campaign_budget_bid_exclusive = $Payout;
                            $campaign_details->campaign_budget_bid_shared = $Payout;

                            $campaigns_arr[] = $campaign_details;
                        }
                    }
                }

                // remove the curl handle that just completed
                curl_multi_remove_handle($master, $done['handle']);
            }
        } while($running > 0);

        curl_multi_close($master);

        //Insert API Responses
        CrmResponsePing::insert($data_crm_responses);

        $response_arr = array(
            'campaigns' => $campaigns_arr,
            'response' => $ping_post_arr
        );
        return $response_arr;
    }
}
