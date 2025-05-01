<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayPerClickReport extends Controller
{
    public function index(){
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

        $campaigns = DB::table('campaigns')
            ->where('campaign_Type', 3)
            ->where('campaign_visibility', 1)
            ->select(["campaign_name", "campaign_id"])
            ->paginate(10);

        $click_leads = DB::table('campaigns_leads_users')
            ->Join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->where('campaigns_leads_users.is_returned', 0)
            ->where('campaigns.campaign_Type', 3)
            ->where('campaigns.campaign_visibility', 1)
            ->whereBetween('campaigns_leads_users.date', [$start_date, $end_date])
            ->groupBy('campaigns_leads_users.campaign_id');

        $total_click_leads = $click_leads->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
            ->pluck('totalLeads', "campaignId")->toarray();

        $sum_click_leads = $click_leads->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS Leads_sum, campaigns.campaign_id as campaignId')
            ->pluck('Leads_sum', "campaignId")->toarray();

        $total_conversions_leads = $click_leads->where('campaigns_leads_users.campaigns_leads_users_note', "converted")
            ->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
            ->pluck('totalLeads', "campaignId")->toarray();

        return view('Reports.payPerClickReport', compact("campaigns", "total_click_leads", "sum_click_leads", "total_conversions_leads"));
    }

    public function fetch_data_payPerClick(Request $request){
        if($request->ajax()) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $campaigns = DB::table('campaigns')
                ->where('campaign_Type', 3)
                ->where('campaign_visibility', 1);
            if(!empty($query_search)){
                $campaigns->where('campaign_name', 'like', '%' . $query_search . '%');
            }
            $campaigns = $campaigns->select(["campaign_name", "campaign_id"])->paginate(10);

            $click_leads = DB::table('campaigns_leads_users')
                ->Join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->where('campaigns_leads_users.is_returned', 0)
                ->where('campaigns.campaign_Type', 3)
                ->where('campaigns.campaign_visibility', 1);

            if (!empty($start_date) && !empty($end_date)) {
                $click_leads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $click_leads->groupBy('campaigns_leads_users.campaign_id');

            $total_click_leads = $click_leads->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
                ->pluck('totalLeads', "campaignId")->toarray();

            $sum_click_leads = $click_leads->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS Leads_sum, campaigns.campaign_id as campaignId')
                ->pluck('Leads_sum', "campaignId")->toarray();

            $total_conversions_leads = $click_leads->where('campaigns_leads_users.campaigns_leads_users_note', "converted")
                ->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
                ->pluck('totalLeads', "campaignId")->toarray();

            return view('Render.Reports.PayPerClick_Report_Render', compact("campaigns", "total_click_leads", "sum_click_leads", "total_conversions_leads"))->render();
        }
    }
}
