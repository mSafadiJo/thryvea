<?php

namespace App\Http\Controllers\AgentDashboard;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class SalesDashboardController extends Controller
{
    public function index(){
        $sdrs = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereIn('users.role_id', [1, 2])
            ->where('users.user_visibility', 1)
            ->where('users.account_type', "Sdr")
            ->orderBy('users.created_at')
            ->get([
                'sales_dashboards.transfer_number', 'users.*'
            ]);

        $sales = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereIn('users.role_id', [1, 2])
            ->where('users.user_visibility', 1)
            ->whereNotIn('users.id', [9, 23, 389])
            ->where('users.account_type', "Sales")
            ->orderBy('users.created_at')
            ->get([
                'sales_dashboards.transfer_number', 'users.*'
            ]);

        $sales_target = config('services.SALES_DASHBOARD.SALES_TARGET', '5');
        $sales_target = ( !empty($sales_target) ? $sales_target : 5 );
        $daly_sales_max_transfer = config('services.SALES_DASHBOARD.DALY_SALES_MAX_TRANSFER', '10');
        $daly_sales_max_transfer = ( !empty($daly_sales_max_transfer) ? $daly_sales_max_transfer : 10 );

        $sdr_target = config('services.SALES_DASHBOARD.SDR_TARGET', '5');
        $sdr_target = ( !empty($sdr_target) ? $sdr_target : 5 );
        $daly_sdr_max_transfer = config('services.SALES_DASHBOARD.DALY_SDR_MAX_TRANSFER', '10');
        $daly_sdr_max_transfer = ( !empty($daly_sdr_max_transfer) ? $daly_sdr_max_transfer : 10 );

        return view('AgentDashboard.sales',
            compact('sdrs', 'sales', 'sales_target', 'daly_sales_max_transfer', 'sdr_target', 'daly_sdr_max_transfer'));
    }

    public function reload(Request $request){
        $sdrs = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereNotIn('users.role_id', [3, 4])
            ->where('users.user_visibility', 1)
            ->where('users.account_type', "Sdr")
            ->orderBy('users.created_at')
            ->get([
                'sales_dashboards.transfer_number', 'users.*'
            ]);

        $sales = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereNotIn('users.role_id', [3, 4])
            ->where('users.user_visibility', 1)
            ->whereNotIn('users.id', [9, 23, 389])
            ->where('users.account_type', "Sales")
            ->orderBy('users.created_at')
            ->get([
                'sales_dashboards.transfer_number', 'users.*'
            ]);

        $sales_target = config('services.SALES_DASHBOARD.SALES_TARGET', '5');
        $sales_target = ( !empty($sales_target) ? $sales_target : 5 );
        $daly_sales_max_transfer = config('services.SALES_DASHBOARD.DALY_SALES_MAX_TRANSFER', '10');
        $daly_sales_max_transfer = ( !empty($daly_sales_max_transfer) ? $daly_sales_max_transfer : 10 );

        $sdr_target = config('services.SALES_DASHBOARD.SDR_TARGET', '5');
        $sdr_target = ( !empty($sdr_target) ? $sdr_target : 5 );
        $daly_sdr_max_transfer = config('services.SALES_DASHBOARD.DALY_SDR_MAX_TRANSFER', '10');
        $daly_sdr_max_transfer = ( !empty($daly_sdr_max_transfer) ? $daly_sdr_max_transfer : 10 );

        $sales_transfers_dashboard_div_1 = "";
        $sales_transfers_dashboard_body_1 = "";
        foreach($sales as $val){
            $sales_transfers_dashboard_div_1 .= '<div class="row mb-3">';
            $sales_transfers_dashboard_div_1 .= '<div class="col-sm-12">';
            $sales_transfers_dashboard_div_1 .= '<div class="row">';
            $sales_transfers_dashboard_div_1 .= '<div class="col-sm-2">';
            $sales_transfers_dashboard_div_1 .= '<div class="custom-name-progress">'.$val->username.'</div>';
            $sales_transfers_dashboard_div_1 .= '</div>';
            $sales_transfers_dashboard_div_1 .= '<div class="col-sm-10">';
            $sales_transfers_dashboard_div_1 .= '<div class="main-progress">';
            $data_perc = round(($val->transfer_number / $daly_sales_max_transfer) * 100);
            if( $data_perc > 95 ){
                $data_perc = 95;
            }
            if( $data_perc < 0 ){
                $data_perc = 0;
            }
            if( $data_perc == 0 ){
                if(empty($val->adminIcon1)){
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/man-user.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon1).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } elseif( $data_perc == 95 ){
                if(empty($val->adminIcon3)){
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/break-dance.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon3).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } else {
                if(empty($val->adminIcon2)){
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/man-walking-directions-button.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_1 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon2).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            }
            $sales_transfers_dashboard_div_1 .= '</div>';
            $sales_transfers_dashboard_div_1 .= '</div>';
            $sales_transfers_dashboard_div_1 .= '</div>';
            $sales_transfers_dashboard_div_1 .= '</div>';
            $sales_transfers_dashboard_div_1 .= '</div>';

            //===========================================================================
            $sales_transfers_dashboard_body_1 .= '<tr>';
            $sales_transfers_dashboard_body_1 .= '<th scope="row">'.$val->id.'</th>';
            $sales_transfers_dashboard_body_1 .= '<th>'.$val->username.'</th>';
            $color = "Red";
            if( !empty($val->transfer_number) ){
                if( $val->transfer_number >= $sales_target ){
                    $color = "Green";
                }
            }
            $transfer_number = ( !empty($val->transfer_number) ? $val->transfer_number : 0 );
            $sales_transfers_dashboard_body_1 .= '<td><span class="custom-sales-number" style="color: '.$color.'">'.$transfer_number.'</span></td>';
            $sales_transfers_dashboard_body_1 .= '</tr>';
        }

        $sales_transfers_dashboard_div_2 = "";
        $sales_transfers_dashboard_body_2 = "";
        foreach($sdrs as $val){
            $sales_transfers_dashboard_div_2 .= '<div class="row mb-3">';
            $sales_transfers_dashboard_div_2 .= '<div class="col-sm-12">';
            $sales_transfers_dashboard_div_2 .= '<div class="row">';
            $sales_transfers_dashboard_div_2 .= '<div class="col-sm-2">';
            $sales_transfers_dashboard_div_2 .= '<div class="custom-name-progress">'.$val->username.'</div>';
            $sales_transfers_dashboard_div_2 .= '</div>';
            $sales_transfers_dashboard_div_2 .= '<div class="col-sm-10">';
            $sales_transfers_dashboard_div_2 .= '<div class="main-progress">';
            $data_perc = round(($val->transfer_number / $daly_sdr_max_transfer) * 100);
            if( $data_perc > 95 ){
                $data_perc = 95;
            }
            if( $data_perc < 0 ){
                $data_perc = 0;
            }
            if( $data_perc == 0 ){
                if(empty($val->adminIcon1)){
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/man-user.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon1).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } elseif( $data_perc == 95 ){
                if(empty($val->adminIcon3)){
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/break-dance.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon3).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } else {
                if(empty($val->adminIcon2)){
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/man-walking-directions-button.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $sales_transfers_dashboard_div_2 .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon2).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            }
            $sales_transfers_dashboard_div_2 .= '</div>';
            $sales_transfers_dashboard_div_2 .= '</div>';
            $sales_transfers_dashboard_div_2 .= '</div>';
            $sales_transfers_dashboard_div_2 .= '</div>';
            $sales_transfers_dashboard_div_2 .= '</div>';

            //=========================================================================================
            $sales_transfers_dashboard_body_2 .= '<tr>';
            $sales_transfers_dashboard_body_2 .= '<th scope="row">'.$val->id.'</th>';
            $sales_transfers_dashboard_body_2 .= '<th>'.$val->username.'</th>';
            $color = "Red";
            if( !empty($val->transfer_number) ){
                if( $val->transfer_number >= $sdr_target ){
                    $color = "Green";
                }
            }
            $transfer_number = ( !empty($val->transfer_number) ? $val->transfer_number : 0 );
            $sales_transfers_dashboard_body_2 .= '<td><span class="custom-sales-number" style="color: '.$color.'">'.$transfer_number.'</span></td>';
            $sales_transfers_dashboard_body_2 .= '</tr>';
        }

        $data = array(
            "sales_transfers_dashboard_div_1" => $sales_transfers_dashboard_div_1,
            "sales_transfers_dashboard_body_1" => $sales_transfers_dashboard_body_1,
            "sales_transfers_dashboard_div_2" => $sales_transfers_dashboard_div_2,
            "sales_transfers_dashboard_body_2" => $sales_transfers_dashboard_body_2,
        );

        return $data;
    }

    public function index_callCenter(){
        $callCenters = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereIn('users.role_id', [1, 2])
            ->where('users.user_visibility', 1)
            ->where('users.account_type', "Call Center")
            ->orderBy('users.created_at')
            ->get(['sales_dashboards.transfer_number', 'users.*']);

        $callCenter_target = config('services.SALES_DASHBOARD.CALLCENTER_TARGET', '5');
        $callCenter_target = ( !empty($callCenter_target) ? $callCenter_target : 5 );
        $daly_callCenter_max_transfer = config('services.SALES_DASHBOARD.DALY_CALLCENTER_MAX_TRANSFER', '10');
        $daly_callCenter_max_transfer = ( !empty($daly_callCenter_max_transfer) ? $daly_callCenter_max_transfer : 10 );

        return view('AgentDashboard.callCenter',
            compact('callCenters', 'callCenter_target', 'daly_callCenter_max_transfer'));
    }

    public function reload_callCenter(Request $request){
        $callCenters = User::leftJoin('sales_dashboards', function($join) {
            $join->on('sales_dashboards.user_id', '=', 'users.id')
                ->whereBetween('sales_dashboards.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"]);
            })
            ->whereIn('users.role_id', [1, 2])
            ->where('users.user_visibility', 1)
            ->where('users.account_type', "Call Center")
            ->orderBy('users.created_at')
            ->get(['sales_dashboards.transfer_number', 'users.*']);

        $callCenter_target = config('services.SALES_DASHBOARD.CALLCENTER_TARGET', '5');
        $callCenter_target = ( !empty($callCenter_target) ? $callCenter_target : 5 );
        $daly_callCenter_max_transfer = config('services.SALES_DASHBOARD.DALY_CALLCENTER_MAX_TRANSFER', '10');
        $daly_callCenter_max_transfer = ( !empty($daly_callCenter_max_transfer) ? $daly_callCenter_max_transfer : 10 );

        $callCenter_transfers_dashboard_div = "";
        $callCenter_transfers_dashboard_body = "";
        foreach($callCenters as $val){
            $callCenter_transfers_dashboard_div .= '<div class="row mb-3">';
            $callCenter_transfers_dashboard_div .= '<div class="col-sm-12">';
            $callCenter_transfers_dashboard_div .= '<div class="row">';
            $callCenter_transfers_dashboard_div .= '<div class="col-sm-2">';
            $callCenter_transfers_dashboard_div .= '<div class="custom-name-progress">'.$val->user_business_name.'</div>';
            $callCenter_transfers_dashboard_div .= '</div>';
            $callCenter_transfers_dashboard_div .= '<div class="col-sm-10">';
            $callCenter_transfers_dashboard_div .= '<div class="main-progress">';
            $data_perc = round(($val->transfer_number / $daly_callCenter_max_transfer) * 100);
            if( $data_perc > 95 ){
                $data_perc = 95;
            }
            if( $data_perc < 0 ){
                $data_perc = 0;
            }
            if( $data_perc == 0 ){
                if(empty($val->adminIcon1)){
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/man-user.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon1).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } elseif( $data_perc == 95 ){
                if(empty($val->adminIcon3)){
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/break-dance.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon3).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            } else {
                if(empty($val->adminIcon2)){
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/man-walking-directions-button.png').'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                } else {
                    $callCenter_transfers_dashboard_div .= '<img src="'.asset('/images/salesDashboard/'.$val->adminIcon2).'" class="img-sales-custom" style="left: '.$data_perc .'%;">';
                }
            }
            $callCenter_transfers_dashboard_div .= '</div>';
            $callCenter_transfers_dashboard_div .= '</div>';
            $callCenter_transfers_dashboard_div .= '</div>';
            $callCenter_transfers_dashboard_div .= '</div>';
            $callCenter_transfers_dashboard_div .= '</div>';

            //===========================================================================
            $callCenter_transfers_dashboard_body .= '<tr>';
            $callCenter_transfers_dashboard_body .= '<th scope="row">'.$val->id.'</th>';
            $callCenter_transfers_dashboard_body .= '<th>'.$val->user_business_name.'</th>';
            $color = "Red";
            if( !empty($val->transfer_number) ){
                if( $val->transfer_number >= $callCenter_target ){
                    $color = "Green";
                }
            }
            $transfer_number = ( !empty($val->transfer_number) ? $val->transfer_number : 0 );
            $callCenter_transfers_dashboard_body .= '<td><span class="custom-sales-number" style="color: '.$color.'">'.$transfer_number.'</span></td>';
            $callCenter_transfers_dashboard_body .= '</tr>';
        }

        $data = array(
            "callCenter_transfers_dashboard_div" => $callCenter_transfers_dashboard_div,
            "callCenter_transfers_dashboard_body" => $callCenter_transfers_dashboard_body
        );

        return $data;
    }

    public function powerSolarSlot(){
        //For Power Solar Slots
        $start = time() . '000';
        $end = strtotime("+5 month") . '999';
        $calendarId = "MwEyqDpzfXVYcvo9pHlT";
        $timezone = "America/Los_Angeles";
        $userId = "vALNnNOD0oYhjkv3faXC";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest.gohighlevel.com/v1/appointments/slots?calendarId=$calendarId&startDate=$start&endDate=$end&timezone=$timezone&userId=$userId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer cf40fe17-72c3-4d48-9782-bcfbe750c81f'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo "<pre>";
        print_r(json_decode($response, true));die;
    }
}
