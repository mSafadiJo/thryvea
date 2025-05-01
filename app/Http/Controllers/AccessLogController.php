<?php

namespace App\Http\Controllers;

use App\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class AccessLogController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '10-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index(Request $request, $section){
//        ini_set('max_execution_time', '0');
//        ini_set('memory_limit', '-1');
//        exec('echo "" > ' . storage_path('/logs/laravel.log'));
//        return 1;

        $title_arr = array(
            "ProspectUsers"                 => "Prospects User",
            "SendSMS"                       => "Send SMS",
            "BlockLead"                     => "Block Leads Info",
            "Services"                      => "Services",
            "PromoCode"                     => "Promo Codes",
            "Admin"                         => "Admin User",
            "Buyers"                        => "Buyers User",
            "Campaign"                      => "Buyer Campaigns",
            "Ticket"                        => "Ticket",
            "Payment"                       => "User Payments",
            "LeadManagement"                => "Lead Management",
            "MarketingPlatform"             => "Marketing Platforms",
            "MarketingTrafficSources"       => "Marketing TS",
            "SellerCampaign"                => "Seller Campaigns",
            "Authentication"                => "Authentication",
            "ThemeTemplates"                => "Themes",
            "DomainTemplates"               => "Domains",
            "ShopLeads"                     => "Shop Leads",
            "CallCenterSource"              => "Call Center Source",
            "LeadCostByTS"                  => "Lead Cost By TS"
        );

        $start_date = date('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';

        $accessLogs = AccessLog::join('user_role', 'user_role.role_id', '=', 'access_logs.user_role')
            ->whereBetween('access_logs.created_at', [$start_date, $end_date]);

        if( $section == "Authentication" ){
            $accessLogs->whereIn('action', array('login','Registered') );
        } else {
            $accessLogs->where('section', $section);
        }

        $accessLogs = $accessLogs->orderBy('access_logs.created_at', 'DESC')->get(['access_logs.*', 'user_role.role_type']);

        return view('SuperAdmin.AccessLog.AccessLogList', compact('accessLogs', 'title_arr', 'section'));
    }

    public function search(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $section = $request->section;

        $accessLogs = AccessLog::join('user_role', 'user_role.role_id', '=', 'access_logs.user_role')
            ->whereBetween('access_logs.created_at', [$start_date, $end_date]);

        if( $section == "Authentication" ){
            $accessLogs->whereIn('action', array('login','Registered') );
        } else {
            $accessLogs->where('section', $section);
        }

        $accessLogs = $accessLogs->orderBy('access_logs.created_at', 'DESC')
            ->get(['access_logs.*', 'user_role.role_type']);

        $dataTable = '';

        $dataTable .= '<table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>User Role</th>
                                    <th>Action</th>
                                    <th>Ip Address</th>
                                    <th>Location</th>
                                    <th>Request Method</th>
                                    <th>Created At</th>
                                </tr>
                            <tbody>';

        if( !empty($accessLogs) ){
            foreach ( $accessLogs as $item ){
                $dataTable .= '<tr>';
                $dataTable .= '<td>' . $item->section_name . '</td>';
                $dataTable .= '<td>' . $item->user_name . '</td>';
                $dataTable .= '<td>' . $item->role_type . '</td>';
                $dataTable .= '<td>' . $item->action . '</td>';
                $dataTable .= '<td>' . $item->ip_address . '</td>';
                $dataTable .= '<td>';

                $location = $item->location;
                $location = str_replace("{", "", $location);
                $location = str_replace("}", "", $location);
                $location = str_replace('"', '', $location);
                $location = str_replace(',', '<br>', $location);
                $location = str_replace(':', '&#160;&#160;&#160;:&#160;&#160;&#160;', $location);

                $dataTable .= '<input type="hidden" value="' . $location . '" id="accessLogServicelocation-' . $item->id . '">
                                 <span onclick=\'ShowLocationpopup("' . $item->id . '");\' class="showpopup" data-toggle="modal" data-target="#con-close-modal">Show Info</span>';
                $dataTable .= '</td>';
                $dataTable .= '<td>';
                $requestData = $item->request_method;
                $requestData = str_replace("{", "", $requestData);
                $requestData = str_replace("}", "", $requestData);
                $requestData = str_replace('"', '', $requestData);
                $requestData = str_replace(',', '<br>', $requestData);
                $requestData = str_replace(':', '&#160;&#160;&#160;:&#160;&#160;&#160;', $requestData);

                $dataTable .= '<input type="hidden" value="' . $requestData . '" id="accessLogServicerequestData-' . $item->id . '">
                                     <span onclick=\'ShowrequestDatapopup("' . $item->id . '");\' class="showpopup" data-toggle="modal" data-target="#con-close-modal">Show Info</span>';
                $dataTable .= '</td>';
                $dataTable .= '<td>' . $item->created_at . '</td>';
                $dataTable .= '</tr>';
            }
        }

        $dataTable .= ' </tbody>
                            </thead>
                        </table>';

        return $dataTable;
    }
}
