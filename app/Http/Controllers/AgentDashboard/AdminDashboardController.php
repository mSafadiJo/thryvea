<?php

namespace App\Http\Controllers\AgentDashboard;

use App\Http\Controllers\Controller;
use App\Models\SalesDashboard;
use App\Services\ApiMain;
use App\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '3-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

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
            ->where('users.account_type', "Sales")
            ->whereNotIn('users.id', [9, 23, 389])
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

        return view('AgentDashboard.Admin.index',
            compact('sdrs', 'sales', 'sales_target', 'daly_sales_max_transfer', 'sdr_target', 'daly_sdr_max_transfer'));
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

        return view('AgentDashboard.Admin.index_callCenter',
            compact('callCenters', 'callCenter_target', 'daly_callCenter_max_transfer'));
    }

    public function storeSetting(Request $request){
        $sales_target = (!empty($request->sales_target) ? $request->sales_target : 0);
        $daly_sales_max_transfer = (!empty($request->daly_sales_max_transfer) ? $request->daly_sales_max_transfer : 0);
        $sdr_target = (!empty($request->sdr_target) ? $request->sdr_target : 0);
        $daly_sdr_max_transfer = (!empty($request->daly_sdr_max_transfer) ? $request->daly_sdr_max_transfer : 0);
        $callCenter_target = (!empty($request->callCenter_target) ? $request->callCenter_target : 0);
        $daly_callCenter_max_transfer = (!empty($request->daly_callCenter_max_transfer) ? $request->daly_callCenter_max_transfer : 0);

        $main_api_file = new ApiMain();

        if($request->type == 1){
            $main_api_file->overWriteEnvFile("SALES_TARGET", $sales_target);
            $main_api_file->overWriteEnvFile("DALY_SALES_MAX_TRANSFER", $daly_sales_max_transfer);
            $main_api_file->overWriteEnvFile("SDR_TARGET", $sdr_target);
            $main_api_file->overWriteEnvFile("DALY_SDR_MAX_TRANSFER", $daly_sdr_max_transfer);
        } else {
            $main_api_file->overWriteEnvFile("CALLCENTER_TARGET", $callCenter_target);
            $main_api_file->overWriteEnvFile("DALY_CALLCENTER_MAX_TRANSFER", $daly_callCenter_max_transfer);
        }

        return true;
    }

    public function storeTransfers(Request $request){
        $input_number_val = $request->input_number_val;
        $admin_id = $request->admin_id;

        $is_set_today = SalesDashboard::where('user_id', $admin_id)
            ->whereBetween('created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"])
            ->first();

        if( empty($is_set_today) ){
            $sales_dashboard = new SalesDashboard();
        } else {
            $sales_dashboard = SalesDashboard::find($is_set_today->id);
        }

        $sales_dashboard->user_id = $admin_id;
        $sales_dashboard->transfer_number = $input_number_val;

        $sales_dashboard->save();

        return true;
    }
}
