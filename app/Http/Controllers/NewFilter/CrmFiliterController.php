<?php

namespace App\Http\Controllers\NewFilter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class CrmFiliterController extends Controller
{
    public function __construct(Request $request)
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        $request->permission_page = '3-16';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function listCRMReportShow(){
        $users = DB::table('users')
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('users.user_visibility', 1)
            ->orderBy('users.created_at', 'DESC')
            ->get(['user_business_name', 'id']);

        return view('Reports.crm_report', compact('users'));
    }


    public function ShowCRMAjax(Request $request){
        if($request->ajax()) {
            $user_id = (!empty($request->buyer_id) ? $request->buyer_id : 0);
            $crm_id = $request->environments;
            $start_date = date('Y-m-d', strtotime($request->start_date)) . " 00:00:00";
            $end_date = date('Y-m-d', strtotime($request->end_date)) . " 23:59:59";
            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $campaign_ids = DB::table('campaigns')
                ->where('is_seller', 0)
                ->where('campaign_visibility', 1)
                ->where('user_id', $user_id)
                ->whereJsonContains('delivery_Method_id', "3")
                ->pluck('campaign_id')->toArray();

            //Return Crm response
            if($crm_id == 1) {
                $Type = "ping";
                $CrmReport = DB::table('crm_response_pings')
                    ->join('campaigns', 'campaigns.campaign_id', '=', 'crm_response_pings.campaign_id')
                    ->whereIn('crm_response_pings.campaign_id',  $campaign_ids)
                    ->where(function ($query) {
                        $query->where('crm_response_pings.campaigns_leads_users_id', 0);
                        $query->OrwhereNull('crm_response_pings.campaigns_leads_users_id');
                    })
                    ->whereBetween('crm_response_pings.created_at', [$start_date, $end_date])
                    ->where(function ($query) use($query_search) {
                        $query->where('crm_response_pings.lead_id', 'like', '%'.$query_search.'%');
                        $query->orWhere('crm_response_pings.ping_id', 'like', '%'.$query_search.'%');
                        $query->orWhere(DB::raw('lower(crm_response_pings.response)'), 'like', '%'.strtolower($query_search).'%');
                        $query->orWhere(DB::raw('lower(campaigns.campaign_name)'), 'like', '%'.strtolower($query_search).'%');
                    })
                    ->orderBy('crm_response_pings.created_at', 'DESC')
                    ->select([
                        'crm_response_pings.*','campaigns.campaign_name'
                    ])->paginate(10);
            } else {
                $Type = "Post";
                $CrmReport = DB::table('crm_responses')
                    ->join('campaigns', 'campaigns.campaign_id', '=', 'crm_responses.campaign_id')
                    ->whereIn('crm_responses.campaign_id',  $campaign_ids)
                    ->where(function ($query) {
                        $query->where('crm_responses.lead_id', 0);
                        $query->OrwhereNull('crm_responses.lead_id');
                    })
                    ->where(function ($query) {
                        $query->where('crm_responses.ping_id', 0);
                        $query->OrwhereNull('crm_responses.ping_id');
                    })
                    ->whereBetween('crm_responses.created_at', [$start_date, $end_date])
                    ->where(function ($query) use($query_search) {
                        $query->where('crm_responses.campaigns_leads_users_id', 'like', '%'.$query_search.'%');
                        $query->orWhere(DB::raw('lower(crm_responses.response)'), 'like', '%'.strtolower($query_search).'%');
                        $query->orWhere(DB::raw('lower(campaigns.campaign_name)'), 'like', '%'.strtolower($query_search).'%');
                    })
                    ->orderBy('crm_responses.created_at', 'DESC')
                    ->select([
                        'crm_responses.*','campaigns.campaign_name'
                    ])->paginate(10);
            }

            return view('Render.Reports.crm_report_Render', compact('CrmReport', 'Type', 'crm_id'))->render();
        }
    }

    public function export(Request $request)
    {
        $user_id = (!empty($request->buyer_id) ? $request->buyer_id : 0);
        $crm_id = $request->environments;
        $start_date = date('Y-m-d', strtotime($request->start_date)) . " 00:00:00";
        $end_date = date('Y-m-d', strtotime($request->end_date)) . " 23:59:59";

        $campaign_ids = DB::table('campaigns')
            ->where('is_seller', 0)
            ->where('campaign_visibility', 1)
            ->where('user_id', $user_id)
            ->whereJsonContains('delivery_Method_id', "3")
            ->pluck('campaign_id')->toArray();

        //Return Crm response
        if ($crm_id == 1) {
            $Type = "ping";
            $CrmReport = DB::table('crm_response_pings')
                // Buyer campaign (direct relation)
                ->join('campaigns as buyer_campaigns', 'buyer_campaigns.campaign_id', '=', 'crm_response_pings.campaign_id')

                // Ping leads relation
                ->leftJoin('ping_leads', 'ping_leads.lead_id', '=', 'crm_response_pings.ping_id')

                // Seller campaign (via ping_leads.vendor_id)
                ->leftJoin('campaigns as seller_campaigns', 'seller_campaigns.vendor_id', '=', 'ping_leads.vendor_id')

                //->join('campaigns', 'campaigns.campaign_id', '=', 'crm_response_pings.campaign_id')
                ->whereIn('crm_response_pings.campaign_id', $campaign_ids)
                ->where(function ($query) {
                    $query->where('crm_response_pings.campaigns_leads_users_id', 0);
                    $query->OrwhereNull('crm_response_pings.campaigns_leads_users_id');
                })
                ->whereBetween('crm_response_pings.created_at', [$start_date, $end_date])
                ->orderBy('crm_response_pings.created_at', 'DESC')
                ->get([
                    'crm_response_pings.*',
                    'buyer_campaigns.campaign_name as buyer_campaign_name',
                    'seller_campaigns.campaign_name as seller_campaign_name',
                    'ping_leads.traffic_source as ping_traffic_source',
                ]);
        } else {
            $Type = "Post";
            $CrmReport = DB::table('crm_responses')
                //->join('campaigns', 'campaigns.campaign_id', '=', 'crm_responses.campaign_id')
                // Buyer campaign (direct relation)
                ->join('campaigns as buyer_campaigns', 'buyer_campaigns.campaign_id', '=', 'crm_response_pings.campaign_id')

                // Ping leads relation
                ->leftJoin('ping_leads', 'ping_leads.lead_id', '=', 'crm_response_pings.ping_id')

                // Seller campaign (via ping_leads.vendor_id)
                ->leftJoin('campaigns as seller_campaigns', 'seller_campaigns.vendor_id', '=', 'ping_leads.vendor_id')

                ->whereIn('crm_responses.campaign_id', $campaign_ids)
                ->where(function ($query) {
                    $query->where('crm_responses.lead_id', 0);
                    $query->OrwhereNull('crm_responses.lead_id');
                })
                ->where(function ($query) {
                    $query->where('crm_responses.ping_id', 0);
                    $query->OrwhereNull('crm_responses.ping_id');
                })
                ->whereBetween('crm_responses.created_at', [$start_date, $end_date])
                ->orderBy('crm_responses.created_at', 'DESC')
                ->get([
                    'crm_response_pings.*',
                    'buyer_campaigns.campaign_name as buyer_campaign_name',
                    'seller_campaigns.campaign_name as seller_campaign_name',
                    'ping_leads.traffic_source as ping_traffic_source',
                ]);
        }

        return (new FastExcel($CrmReport))->download('Responses.csv', function ($Crm) use($crm_id, $Type){
            return [
                'ID' => $Crm->id,
                'Lead Id' => ($crm_id == 1 ? $Crm->lead_id : $Crm->campaigns_leads_users_id),
                'PING Id' => $Crm->ping_id,
                'Buyer Campaign Name' => $Crm->buyer_campaign_name,
                'Seller Campaign Name' => $Crm->seller_campaign_name,
                'Traffic Source' => $Crm->ping_traffic_source,
                'Type' => $Type,
                'Result' => $Crm->response,
                'Time' => $Crm->time,
                'Create Date' => $Crm->created_at,
            ];
        });
    }
}
