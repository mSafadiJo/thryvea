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
                                case 18:
                                    //HELLO PROJECT USA 760
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
                                    case 23:
                                    //HomeQuote 23
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['Result'])) {
                                        if ($result2['Result'] == 'Success') {
                                            $TransactionId = $result2['PingId'];
                                            $Payout = $result2['Payout'];
                                            $multi_type = 0;
                                            $Result = 1;
                                        }
                                    }
                                    break;
                                case 29:
                                    //Clean Energy Authoroty 29
                                    $result2 = json_decode($result, true);
                                    if (!empty($result2['Status']) && $result2['Status'] === 'Match') {
                                        $TransactionId = $result2['PingId'];
                                        // Find the offer with the highest price
                                        $bestOffer = collect($result2['Offers'])->sortByDesc('Price')->first();
                                        // Extract details from best offer
                                        $Price = $bestOffer['Price'];
                                        $OfferId = $bestOffer['OfferId'];
                                        $Payout = $OfferId . '|' . $Price;

                                        $multi_type = 0;
                                        $Result = 1;
                                    }
                                    break;
                                case 32:
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
