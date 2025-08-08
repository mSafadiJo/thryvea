<?php

namespace App\Http\Controllers;

use App\LeadsCustomer;
use App\LeadTrafficSources;
use App\PingLeads;
use App\Services\AllServicesQuestions;
use App\Services\APIValidations;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\AccessLog;
use App\TotalAmount;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesOrder;
use Rap2hpoutre\FastExcel\FastExcel;

class CampaignLeadsAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function list_of_leads_all()
    {
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id', ['3', '4', '6'])->where('user_visibility', 1)->get()->All();
        //$sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        //$list_of_ts = LeadTrafficSources::pluck('name')->toArray();
        //$list_of_g = DB::table('leads_customers')->groupBy('google_g')->pluck('google_g')->toArray();
        //$list_of_c = DB::table('leads_customers')->groupBy('google_c')->pluck('google_c')->toArray();
        //$list_of_k = DB::table('leads_customers')->groupBy('google_k')->pluck('google_k')->toArray();
        $states = State::All();

        $QA_status = array(
            "Not started",
            "Not a working number",
            "Didn't request",
            "Bogus info",
            "Wrong number",
            "Doesn't qualify",
            "Not interested",
            "N/A",
            "False Advertisement",
            "DNC",
            "Interested",
            "Line Busy",
            "Job done",
            "Invalid Address",
            "Not a home"
        );

        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states,
            //'sellers' => $sellers,
            //'list_of_ts' => $list_of_ts,
            //'list_of_g' => $list_of_g,
            //'list_of_c' => $list_of_c,
            //'list_of_k' => $list_of_k
        );

        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $ListOfLeads = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->where('leads_customers.is_duplicate_lead',"<>", 1)
            ->where(function ($query) {
                $query->whereNull('campaigns_leads_users.is_returned');
                $query->OrWhere('campaigns_leads_users.is_returned', 0);
            })
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0)
            ->whereBetween('leads_customers.created_at', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->Select([
                'service__campaigns.service_campaign_name', 'leads_customers.*',
                'states.state_code', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned'
            ])
            ->simplePaginate(10);
            //->paginate(10);

        return view('Admin.CampaignLeads.all_lead_list')
            ->with('data', $data)
            ->with('QA_status',$QA_status)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_all_list_lead(Request $request){
        if($request->ajax()) {
            $buyer_id = $request->buyer_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $traffic_source = $request->traffic_source;
            $google_g = $request->google_g;
            $google_c = $request->google_c;
            $google_k = $request->google_k;

            $county_id = array();
            if( !empty($request->county_id) ){
                $county_id = explode(',', $request->county_id);
            }

            $city_id = array();
            if( !empty($request->city_id) ){
                $city_id = explode(',', $request->city_id);
            }

            $zipcode_id = array();
            if( !empty($request->zipcode_id) ){
                $zipcode_id = explode(',', $request->zipcode_id);
            }

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->where(function ($query) use($query_search) {
                    $query->where('leads_customers.lead_id', 'like', '%'.$query_search.'%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%'.$query_search.'%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%'.$query_search.'%');
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%'.$query_search.'%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.google_ts', 'like', '%'.$query_search.'%');
                    $query->orWhere('leads_customers.QA_status', 'like', '%'.$query_search.'%');
                });

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($county_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_county_id', $county_id);
            }

            if (!empty($city_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_city_id', $city_id);
            }

            if (!empty($zipcode_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
            }

            if (!empty($traffic_source)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.google_ts', $traffic_source);
            }

            if (!empty($google_g)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.google_g', $google_g);
            }

            if (!empty($google_c)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.google_c', $google_c);
            }

            if (!empty($google_k)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.google_k', $google_k);
            }

            if (!empty($buyer_id)) {
                $ListOfLeadsNotIn->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            if ($environments == 2) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->whereNotIn('leads_customers.status', [1, 2])
                    ->where('campaigns_leads_users.is_returned', '<>', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 3) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 4) {
                $ListOfLeadsNotIn->where('leads_customers.status', 1)
                    ->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 5) {
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 6) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', 1);
            }
            else if ($environments == 7) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 2);
            }
            else if ($environments == 8) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->whereNotIn('leads_customers.status', [1, 2])
                    ->where('campaigns_leads_users.is_returned', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 9) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 3);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 10) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 4);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where(function ($query) {
                        $query->whereNull('campaigns_leads_users.is_returned');
                        $query->OrWhere('campaigns_leads_users.is_returned', 0);
                    })
                    ->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            $ListOfLeads = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->select([
                    'service__campaigns.service_campaign_name', 'leads_customers.*',
                    'states.state_code', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned'
                ])
                ->simplePaginate(10);
                //->paginate(10);

            $QA_status = array(
                "Not started",
                "Not a working number",
                "Didn't request",
                "Bogus info",
                "Wrong number",
                "Doesn't qualify",
                "Not interested",
                "N/A",
                "False Advertisement",
                "DNC",
                "Interested",
                "Line Busy",
                "Job done",
                "Invalid Address",
                "Not a home"
            );

            return view('Render.All_Lead_List_Render', compact('ListOfLeads', 'QA_status'))->render();
        }
    }

    public function list_of_leads_received(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id', ['3', '4', '6'])->where('user_visibility', 1)->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        $states = State::All();
        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states,
            'sellers' => $sellers
        );

        return view('Admin.CampaignLeads.list_received')->with('data', $data);
    }
    public function fetch_data_lead_received(Request $request){
        if($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');

            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where('campaigns_leads_users.is_returned', 0)
                ->where('service__campaigns.service_is_active', 1)
                ->where(function ($query) use ($query_search) {
                    $query->where('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orwhere('campaigns_leads_users.campaigns_leads_users_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.google_ts', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns.campaign_name', 'like', '%' . $query_search . '%');
                });

            if (!empty($buyer_id)) {
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($seller_id)) {
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if (!empty($service_id)) {
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if (!empty($states)) {
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $sort_by =  str_replace('-','.',$sort_by);
            $campaignSoldLeads = $campaignLeads->orderBy($sort_by, $sort_type)
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->select([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'campaigns_leads_users.campaigns_leads_users_note'
                ])
                ->simplePaginate(10);
                //->paginate(10);

            return view('Render.Lead_Received_Render', compact('campaignSoldLeads'))->render();
        }
    }

    public function list_of_leads_lost(){
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $states = State::All();
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        $data = array(
            'services' => $services,
            'states' => $states,
            'sellers' => $sellers
        );

        $ListOfLeads = DB::table('leads_customers')
            ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
            ->whereNull('campaigns_leads_users.lead_id')
            ->leftjoin('campaigns', 'campaigns.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('leads_customers.is_duplicate_lead',"<>", 1)
            ->where('leads_customers.status', 0)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0)
            ->whereBetween('leads_customers.created_at', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->select([
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code'
            ])
            ->simplePaginate(10);
            //->paginate(10);

        return view('Admin.CampaignLeads.list_lead_lost')
            ->with('ListOfLeads', $ListOfLeads)
            ->with('data', $data);
    }
    public function fetch_data_lead_Lost(Request $request){
        if($request->ajax()) {
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $seller_id = $request->seller_id;

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
                ->whereNull('campaigns_leads_users.lead_id')
                ->leftjoin('campaigns', 'campaigns.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where(function ($query) use ($query_search) {
                    $query->where('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.google_ts', 'like', '%' . $query_search . '%');
                });

            if (!empty($seller_id)) {
                $ListOfLeadsNotIn->whereIn('campaigns.user_id', $seller_id);
            }

            if (!empty($environments == 1)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
            }
            else if (!empty($environments == 3)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', 1);
            }
            else if (!empty($environments == 7)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 2);
            }
            else if (!empty($environments == 9)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 3);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }
            else if (!empty($environments == 10)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 4);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }
            else {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }
            if (!empty($states)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }
            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            $ListOfLeads = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->select([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code'
                ])
                ->simplePaginate(10);
                //->paginate(10);

            return view('Render.Lead_Lost_Render', compact('ListOfLeads'))->render();
        }
    }

    public function AffiliateLeads(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id', ['3', '4', '6'])->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        $states = State::All();

        $QA_status = array(
            "Not started",
            "Not a working number",
            "Didn't request",
            "Bogus info",
            "Wrong number",
            "Doesn't qualify",
            "Not interested",
            "N/A",
            "False Advertisement",
            "DNC",
            "Interested",
            "Line Busy",
            "Job done",
            "Invalid Address",
            "Not a home"
        );

        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states,
            'sellers' => $sellers
        );

        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $ListOfLeads = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('leads_customers.is_duplicate_lead',"<>", 1)
            ->where(function ($query) {
                $query->whereNull('campaigns_leads_users.is_returned');
                $query->OrWhere('campaigns_leads_users.is_returned', 0);
            })
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0)
            ->whereBetween('leads_customers.created_at', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->select([
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code','cities.city_name', 'zip_codes_lists.zip_code_list',
                DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned',
                'Seller.user_business_name AS seller_business_name'
            ])
            ->simplePaginate(10);
            //->paginate(10);

        return view('Admin.CampaignLeads.ListOfAffiliateLeads')
            ->with('data', $data)
            ->with('QA_status',$QA_status)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_lead_Affiliate(Request $request){
        if($request->ajax()) {
            $buyer_id = $request->buyer_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $seller_id = $request->seller_id;

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $county_id = array();
            if (!empty($request->county_id)) {
                $county_id = explode(',', $request->county_id);
            }

            $city_id = array();
            if (!empty($request->city_id)) {
                $city_id = explode(',', $request->city_id);
            }

            $zipcode_id = array();
            if (!empty($request->zipcode_id)) {
                $zipcode_id = explode(',', $request->zipcode_id);
            }

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
                ->where(function ($query) use ($query_search) {
                    $query->where('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orwhere('campaigns_leads_users.campaigns_leads_users_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.response_data', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('camp_seller.campaign_name', 'like', '%' . $query_search . '%');
                });

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($county_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_county_id', $county_id);
            }

            if (!empty($city_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_city_id', $city_id);
            }

            if (!empty($zipcode_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
            }

            if (!empty($buyer_id)) {
                $ListOfLeadsNotIn->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            if (!empty($seller_id)) {
                $ListOfLeadsNotIn->whereIn('camp_seller.user_id', $seller_id);
            }

            if ($environments == 2) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0)
                    ->where('campaigns_leads_users.is_returned', '<>', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>", 1);
            }
            else if ($environments == 3) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>", 1);
            }
            else if ($environments == 5) {
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
            }
            else {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>", 1)
                    ->where(function ($query) {
                        $query->whereNull('campaigns_leads_users.is_returned');
                        $query->OrWhere('campaigns_leads_users.is_returned', 0);
                    })
                    ->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            $ListOfLeads = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->select([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code','cities.city_name', 'zip_codes_lists.zip_code_list',
                    DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned',
                    'Seller.user_business_name AS seller_business_name'
                ])
                ->simplePaginate(10);
                //->paginate(10);

            $QA_status = array(
                "Not started",
                "Not a working number",
                "Didn't request",
                "Bogus info",
                "Wrong number",
                "Doesn't qualify",
                "Not interested",
                "N/A",
                "False Advertisement",
                "DNC",
                "Interested",
                "Line Busy",
                "Job done",
                "Invalid Address",
                "Not a home"
            );

            return view('Render.Lead_Affiliate_Render', compact('ListOfLeads', 'QA_status'))->render();
        }
    }

    public function list_of_leads_Refund(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id',  ['3', '4', '6'])->where('user_visibility', 1)->get()->All();
        $states = State::All();
        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states
        );

        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';

        $ListOfLeads = DB::table('campaigns_leads_users')->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('tickets.ticket_type', 2)
            ->where('tickets.ticket_status', 3)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns_leads_users.is_returned', 1)
            ->whereBetween('leads_customers.created_at', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->select([
                'users.username', 'service__campaigns.service_campaign_name', 'users.user_business_name',
                'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_id',
                'leads_customers.trusted_form', 'leads_customers.lead_source_text', 'leads_customers.google_ts', 'leads_customers.converted',
                'tickets.created_at', 'tickets.reason_lead_returned_id', 'tickets.campaigns_leads_users_type_bid',
                'campaigns.campaign_name', 'tickets.ticket_id', 'tickets.campaigns_leads_users_id', 'states.state_code',
                'campaigns_leads_users.date AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid'
            ])->paginate(10);

        return view('Admin.CampaignLeads.refundlead')
            ->with('data', $data)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_lead_Refund(Request $request){
        if($request->ajax()) {
            $buyer_id = $request->buyer_id;
            $states = $request->states;
            $service_id = $request->service_id;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $listOfLeads = DB::table('campaigns_leads_users')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where('tickets.ticket_type', 2)
                ->where('tickets.ticket_status', 3)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.is_returned', 1)
                ->where(function ($query) use ($query_search) {
                    $query->where('campaigns_leads_users.campaigns_leads_users_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns.campaign_name', 'like', '%' . $query_search . '%');
                });


            if (!empty($buyer_id)) {
                $listOfLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($service_id)) {
                $listOfLeads->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
                $listOfLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $listOfLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $ListOfLeads = $listOfLeads->orderBy('leads_customers.created_at', 'DESC')
                ->select([
                    'users.username', 'service__campaigns.service_campaign_name', 'users.user_business_name',
                    'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_id',
                    'leads_customers.trusted_form', 'leads_customers.lead_source_text', 'leads_customers.google_ts', 'leads_customers.converted',
                    'tickets.created_at', 'tickets.reason_lead_returned_id', 'tickets.campaigns_leads_users_type_bid',
                    'campaigns.campaign_name', 'tickets.ticket_id', 'tickets.campaigns_leads_users_id', 'states.state_code',
                    'campaigns_leads_users.date AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid'
                ])->paginate(10);

            return view('Render.Lead_ReFund_Render', compact('ListOfLeads'))->render();
        }
    }

    public function list_of_leads_Archive(){

        $states = State::All();
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';
        $data = array(
            'services' => $services,
            'states' => $states
        );

        $ListOfLeads = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('leads_customers.status', 1)
            ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
            ->whereNull('campaigns_leads_users.lead_id')
            ->whereBetween('leads_customers.created_at', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->select([
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code'
            ])->paginate(10);

        return view('Admin.CampaignLeads.archive_list')
            ->with('data', $data)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_lead_Archive(Request $request){
        if($request->ajax()) {
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where('leads_customers.status', 1)
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->whereNull('campaigns_leads_users.lead_id')
                ->where(function ($query) use ($query_search) {
                    $query->where('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                });

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            $ListOfLeads = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->select([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code'
                ])->paginate(10);

            return view('Render.Lead_Archive_Render', compact('ListOfLeads'))->render();
        }
    }

    public function list_of_leads_receivedCallCenter(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id', ['3', '4', '6'])->where('user_visibility', 1)->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        $states = State::All();
        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $today = date('Y-m-d') . ' 23:59:59';
        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states,
            'sellers' => $sellers
        );

        $ListOfLeads = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('campaigns_leads_users.is_returned',0)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns_leads_users.call_center', 1)
            ->whereBetween('campaigns_leads_users.date', [$yesterday, $today])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
            ->select([
                'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                'campaigns_leads_users.campaigns_leads_users_note', 'campaigns_leads_users.is_returned'
            ])->paginate(10);

        return view('Admin.CampaignLeads.callCenterLeads')
            ->with('data', $data)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_lead_CallCenter(Request $request){
        if($request->ajax()) {
            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where('campaigns_leads_users.is_returned', 0)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.call_center', 1)
                ->where(function ($query) use ($query_search) {
                    $query->where('campaigns_leads_users.campaigns_leads_users_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns.campaign_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns_leads_users.agent_name', 'like', '%' . $query_search . '%');
                });

            if (!empty($buyer_id)) {
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($seller_id)) {
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if (!empty($service_id)) {
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if (!empty($states)) {
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $ListOfLeads = $campaignLeads->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->select([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'campaigns_leads_users.campaigns_leads_users_note', 'campaigns_leads_users.is_returned'
                ])->paginate(10);

            return view('Render.Lead_CallCenter_Render', compact('ListOfLeads'))->render();

        }
    }

    public function list_of_leads_CallCenterReturns(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $buyers = DB::table('users')->whereIn('role_id', ['3', '4', '6'])->where('user_visibility', 1)->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', [4, 5])->where('user_visibility', 1)->get()->All();
        $states = State::All();
        $data = array(
            'services' => $services,
            'buyers' => $buyers,
            'states' => $states,
            'sellers' => $sellers
        );

        $start_date = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';

        $ListOfLeads = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('campaigns_leads_users.is_returned', 1)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns_leads_users.call_center', 1)
            ->whereBetween('campaigns_leads_users.date', [$start_date, $end_date])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
            ->select([
                'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                'campaigns_leads_users.campaigns_leads_users_note', 'campaigns_leads_users.is_returned',
                'tickets.created_at AS return_date', 'tickets.reason_lead_returned_id'
            ])->paginate(10);

        return view('Admin.CampaignLeads.callCenterReturnLeads')
            ->with('data', $data)
            ->with('ListOfLeads', $ListOfLeads);
    }
    public function fetch_data_lead_CallCenter_Returns(Request $request){
        if($request->ajax()) {
            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->where('campaigns_leads_users.is_returned', 1)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.call_center', 1)
                ->where(function ($query) use ($query_search) {
                    $query->where('campaigns_leads_users.campaigns_leads_users_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('leads_customers.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns.campaign_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns_leads_users.agent_name', 'like', '%' . $query_search . '%');
                });

            if (!empty($buyer_id)) {
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($seller_id)) {
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if (!empty($service_id)) {
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if (!empty($states)) {
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $ListOfLeads = $campaignLeads->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->select([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'campaigns_leads_users.campaigns_leads_users_note', 'campaigns_leads_users.is_returned',
                    'tickets.created_at AS return_date', 'tickets.reason_lead_returned_id'
                ])->paginate(10);

            return view('Render.Lead_CallCenterReturn_Render', compact('ListOfLeads'))->render();
        }
    }

    public function list_of_leads_sms_email(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $states = State::All();

        $data = array(
            'services' => $services,
            'states' => $states
        );

        return view('Admin.CampaignLeads.leads_sms_email_list')->with('data', $data);
    }
    public function list_of_leads_sms_email_ajax(Request $request){
        $service_id = $request->service_id;
        $states = $request->states;
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';


        $ListOfLeadsNotIn = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id');

        if (!empty($service_id)) {
            $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

        if (!empty($states)) {
            $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
        }

        $ListOfLeadsNotIn = $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
            ->where(function ($query) {
                $query->whereNull('campaigns_leads_users.is_returned');
                $query->OrWhere('campaigns_leads_users.is_returned', 0);
            })
            ->whereIn('leads_customers.google_ts', ['SMS', 'sms', 'Sms', 'email', 'Email', 'EMAIL'])
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->get([
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code',
                DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned'
            ])->all();

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '<table id="datatable4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lead Name</th>
                                    <th>State</th>
                                    <th>Service</th>
                                    <th>Source</th>
                                    <th>TS</th>
                                    <th>Status</th>
                                    <th>SoldTo</th>
                                    <th>type</th>
                                    <th>Bid</th>
                                    <th>Sold Date</th>
                                    <th>Created At</th>';
        if (empty($permission_users) || in_array('8-10', $permission_users)) {
            $dataJason .= '<th>Action</th>';
        }
        $dataJason .= '</tr>
                       </thead>
                       <tbody>';

        if (!empty($ListOfLeadsNotIn)) {
            foreach ($ListOfLeadsNotIn as $val) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $val->lead_id . '</td>';
                $dataJason .= '<td>' . $val->lead_fname . ' ' . $val->lead_lname . '</td>';
                $dataJason .= '<td>' . $val->state_code . '</td>';
                $dataJason .= '<td>' . $val->service_campaign_name . '</td>';
                $dataJason .= '<td>' . $val->lead_source_text;
                if( $val->converted == 1 ){
                    $dataJason .= ' > R';
                }
                $dataJason .= '</td>';
                $dataJason .= '<td>' . $val->google_ts . '</td>';

                if ($val->status == 1) {
                    $dataJason .= '<td>Deleted</td>';
                } else if ($val->status == 2) {
                    $dataJason .= '<td>Blocked</td>';
                } else {
                    if ($val->is_duplicate_lead == 1) {
                        $dataJason .= '<td>Duplicated</td>';
                    } else {
                        if (!empty($val->bid_type) || $val->bid_type != 0) {
                            $val->is_returned_concat = str_replace("1","Returned",$val->is_returned_concat);
                            $val->is_returned_concat = str_replace("0","Sold",$val->is_returned_concat);
                            $dataJason .= '<td>'. $val->is_returned_concat . '</td>';
//                            if( $val->is_returned == 1 ){
//                                $dataJason .= '<td>Returned</td>';
//                            } else {
//                                $dataJason .= '<td>Sold</td>';
//                            }
                        } else {
                            $dataJason .= '<td>Not Match</td>';
                        }
                    }
                }

                $dataJason .= '<td>' . $val->buyerUser . '</td>';
                $dataJason .= '<td>' . $val->bid_type . '</td>';
                $dataJason .= '<td>' . number_format($val->sum_bid, 2, '.', ',') . '</td>';

                $dataJason .= '<td>' . $val->sold_date . '</td>';
                $dataJason .= '<td>' . $val->created_at . '</td>';
                if (empty($permission_users) || in_array('8-10', $permission_users)) {
                    $dataJason .= '<td>';
                    if (empty($permission_users) || in_array('8-10', $permission_users)) {
                        $dataJason .= '<a href="/Admin/lead/' . $val->lead_id . '/details/Lost" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                       <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                   </a>';
                    }
                    $dataJason .= '</td>';
                }
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '  </tbody>
                            </table>';

        return $dataJason;
    }

    public function ShowCampaignLeads_receivedDetails($id)
    {
        $campaignLeads = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->leftjoin('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'leads_customers.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'leads_customers.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'leads_customers.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'leads_customers.lead_solor_sun_expouser_list_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'leads_customers.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'leads_customers.property_type_campaign_id')
            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'leads_customers.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'leads_customers.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'leads_customers.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'leads_customers.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'leads_customers.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'leads_customers.lead_property_type_roofing_id')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'leads_customers.lead_solor_solution_list_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'leads_customers.lead_numberOfItem')
            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'leads_customers.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'leads_customers.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'leads_customers.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'leads_customers.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'leads_customers.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'leads_customers.stairs_reason_lead_id')
            ->leftjoin('furnance_type_lead', 'furnance_type_lead.furnance_type_lead_id', '=', 'leads_customers.furnance_type_lead_id')
            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'leads_customers.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'leads_customers.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'leads_customers.handyman_ammount_work_id')
            ->leftjoin('countertops_service_lead', 'countertops_service_lead.countertops_service_lead_id', '=', 'leads_customers.countertops_service_lead_id')
            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'leads_customers.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'leads_customers.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'leads_customers.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'leads_customers.gutters_meterial_lead_id')
            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'leads_customers.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'leads_customers.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'leads_customers.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'leads_customers.paving_best_describes_priject_id')
            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'leads_customers.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'leads_customers.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'leads_customers.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'leads_customers.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'leads_customers.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'leads_customers.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'leads_customers.painting5_surfaces_textured_id')
            ->where('campaigns_leads_users.campaigns_leads_users_id', $id)
            ->first([
                'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'lead_priority.lead_priority_name',
                'service__campaigns.service_campaign_name', 'installing_type_campaign.installing_type_campaign',
                'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number', 'users.user_business_name',
                'states.state_name', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'leads_customers.*', 'campaigns_leads_users.campaigns_leads_users_bid', 'campaigns_leads_users.lead_id_token_md',
                'lead_installation_preferences.lead_installation_preferences_name',
                'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_name',
                'lead_avg_money_electicity_list.lead_avg_money_electicity_list_name', 'property_type_campaign.property_type_campaign',

                'lead_type_of_flooring.lead_type_of_flooring_name', 'lead_nature_flooring_project.lead_nature_flooring_project_name',
                'lead_walk_in_tub.lead_walk_in_tub_name', 'lead_type_of_roofing.lead_type_of_roofing_name',
                'lead_nature_of_roofing.lead_nature_of_roofing_name', 'lead_property_type_roofing.lead_property_type_roofing_name',
                'lead_solor_solution_list.lead_solor_solution_list_name', 'number_of_windows_c.number_of_windows_c_type',

                'type_of_siding_lead.type_of_siding_lead_type', 'nature_of_siding_lead.nature_of_siding_lead_type',
                'service_kitchen_lead.service_kitchen_lead_type', '_campaign_bathroomtype.campaign_bathroomtype_type',
                'stairs_type_lead.stairs_type_lead_type', 'stairs_reason_lead.stairs_reason_lead_type',
                'furnance_type_lead.furnance_type_lead_type', 'installing_type_campaign.installing_type_campaign_id',
                'plumbing_service_list.plumbing_service_list_type', 'sunroom_service_lead.sunroom_service_lead_type',
                'handyman_ammount_work.handyman_ammount_work_type',

                'countertops_service_lead.countertops_service_lead_type',
                'door_typeproject_lead.door_typeproject_lead_type', 'number_of_door_lead.number_of_door_lead_type',
                'gutters_install_type_leade.gutters_install_type_leade_type', 'gutters_meterial_lead.gutters_meterial_lead_type',

                'paving_service_lead.paving_service_lead_type', 'paving_asphalt_type.paving_asphalt_type',
                'paving_loose_fill_type.paving_loose_fill_type',
                'paving_best_describes_priject.paving_best_describes_priject_type',

                'painting_service_lead.painting_service_lead_type', 'painting1_typeof_project.painting1_typeof_project_type',
                'painting1_stories_number.painting1_stories_number_type', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_type',
                'painting2_rooms_number.painting2_rooms_number_type', 'painting2_typeof_paint.painting2_typeof_paint_type',
                'painting5_surfaces_textured.painting5_surfaces_textured_type',
            ]);

        $listOFlead_desired_featuers = DB::table('lead_desired_featuers')->get()->All();
        $painting3_each_feature = DB::table('painting3_each_feature')->get()->All();
        $painting4_existing_roof = DB::table('painting4_existing_roof')->get()->All();
        $painting5_kindof_texturing = DB::table('painting5_kindof_texturing')->get()->All();

        return view('Admin.CampaignLeads.ReceviedLeadDetails')
            ->with('is_with_campaign_details', 1)
            ->with('campaignLeads', $campaignLeads)
            ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
            ->with('painting3_each_feature', $painting3_each_feature)
            ->with('painting4_existing_roof', $painting4_existing_roof)
            ->with('painting5_kindof_texturing', $painting5_kindof_texturing);
    }

    ////////////edit and update lead customer////////////////////////
    public function EditCustomerLead($lead_id)
    {
        $leadCustomer = DB::table('leads_customers')
            // ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'leads_customers.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'leads_customers.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'leads_customers.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'leads_customers.lead_solor_sun_expouser_list_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'leads_customers.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'leads_customers.property_type_campaign_id')
            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'leads_customers.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'leads_customers.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'leads_customers.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'leads_customers.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'leads_customers.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'leads_customers.lead_property_type_roofing_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'leads_customers.lead_numberOfItem')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'leads_customers.lead_solor_solution_list_id')
            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'leads_customers.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'leads_customers.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'leads_customers.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'leads_customers.gutters_meterial_lead_id')
            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'leads_customers.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'leads_customers.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'leads_customers.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'leads_customers.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'leads_customers.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'leads_customers.stairs_reason_lead_id')
            ->leftjoin('furnance_type_lead', 'furnance_type_lead.furnance_type_lead_id', '=', 'leads_customers.furnance_type_lead_id')
            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'leads_customers.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'leads_customers.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'leads_customers.handyman_ammount_work_id')
            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'leads_customers.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'leads_customers.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'leads_customers.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'leads_customers.paving_best_describes_priject_id')
            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'leads_customers.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'leads_customers.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'leads_customers.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'leads_customers.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'leads_customers.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'leads_customers.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'leads_customers.painting5_surfaces_textured_id')
            ->where('lead_id', $lead_id)
            ->first([
                'lead_priority.lead_priority_name', 'installing_type_campaign.installing_type_campaign', 'leads_customers.*',
                'states.state_name', 'states.state_id', 'cities.city_id', 'counties.county_id',
                'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'lead_installation_preferences.lead_installation_preferences_name',
                'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_name',
                'lead_avg_money_electicity_list.lead_avg_money_electicity_list_name', 'property_type_campaign.property_type_campaign',
                'lead_type_of_flooring.lead_type_of_flooring_name', 'lead_nature_flooring_project.lead_nature_flooring_project_name',
                'lead_walk_in_tub.lead_walk_in_tub_name', 'lead_type_of_roofing.lead_type_of_roofing_name',
                'lead_nature_of_roofing.lead_nature_of_roofing_name', 'lead_property_type_roofing.lead_property_type_roofing_name',
                'lead_solor_solution_list.lead_solor_solution_list_name', 'number_of_windows_c.number_of_windows_c_type',

                'type_of_siding_lead.type_of_siding_lead_type', 'nature_of_siding_lead.nature_of_siding_lead_type',
                'service_kitchen_lead.service_kitchen_lead_type', '_campaign_bathroomtype.campaign_bathroomtype_type',
                'stairs_type_lead.stairs_type_lead_type', 'stairs_reason_lead.stairs_reason_lead_type',
                'furnance_type_lead.furnance_type_lead_type', 'installing_type_campaign.installing_type_campaign_id',
                'plumbing_service_list.plumbing_service_list_type', 'sunroom_service_lead.sunroom_service_lead_type',
                'handyman_ammount_work.handyman_ammount_work_type',

                'door_typeproject_lead.door_typeproject_lead_type', 'number_of_door_lead.number_of_door_lead_type',
                'gutters_install_type_leade.gutters_install_type_leade_type', 'gutters_meterial_lead.gutters_meterial_lead_type',

                'paving_service_lead.paving_service_lead_type', 'paving_asphalt_type.paving_asphalt_type',
                'paving_loose_fill_type.paving_loose_fill_type',
                'paving_best_describes_priject.paving_best_describes_priject_type',

                'painting_service_lead.painting_service_lead_type', 'painting1_typeof_project.painting1_typeof_project_type',
                'painting1_stories_number.painting1_stories_number_type', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_type',
                'painting2_rooms_number.painting2_rooms_number_type', 'painting2_typeof_paint.painting2_typeof_paint_type',
                'painting5_surfaces_textured.painting5_surfaces_textured_type',
            ]);

        $states = DB::table('states')->get()->All();
        $listOFlead_desired_featuers = DB::table('lead_desired_featuers')->get()->All();

        ///// to get all question option /////////

        //Mains
        $campain_inistallings = DB::table('installing_type_campaign')->get();
        $lead_prioritys = DB::table('lead_priority')->get();
        $listOfproperty = DB::table('property_type_campaign')->get();

        //Windows
        $numberOfWindows = DB::table('number_of_windows_c')->get();

        //Solar
        $listOfsolor_solution = DB::table('lead_solor_solution_list')->get();
        $listOfutility_provider = DB::table('lead_current_utility_provider')->get();
        $listOfAVGMoney = DB::table('lead_avg_money_electicity_list')->orderBy('lead_avg_money_electicity_list_name')->get();
        $listOfsun_expouser = DB::table('lead_solor_sun_expouser_list')->get();

        //Home Security
        $listOfinstallation_preferences = DB::table('lead_installation_preferences')->get();

        //Flooring
        $listOflead_type_of_flooring = DB::table('lead_type_of_flooring')->get();
        $listOflead_nature_flooring_project = DB::table('lead_nature_flooring_project')->get();

        //Walk In Tubs
        $listOflead_walk_in_tub = DB::table('lead_walk_in_tub')->get();
        $listOflead_desired_featuers = DB::table('lead_desired_featuers')->get();

        //Roofing
        $listOflead_type_of_roofings = DB::table('lead_type_of_roofing')->get();
        $listOflead_nature_of_roofings = DB::table('lead_nature_of_roofing')->get();
        $listOflead_property_type_roofings = DB::table('lead_property_type_roofing')->get();

        //Home Siding
        $type_of_siding_leads = DB::table('type_of_siding_lead')->get();
        $nature_of_siding_leads = DB::table('nature_of_siding_lead')->get();

        //kitchen
        $service_kitchen_leads = DB::table('service_kitchen_lead')->get();

        //Bathroom
        $campaign_bathroomtypes = DB::table('_campaign_bathroomtype')->get();

        //Stairlifts
        $stairs_type_leads = DB::table('stairs_type_lead')->get();
        $stairs_reason_leads = DB::table('stairs_reason_lead')->get();

        //Furnace
        $furnance_type_leads = DB::table('furnance_type_lead')->get();

        //Plumbing
        $plumbing_service_lists = DB::table('plumbing_service_list')->get();

        //Sunrooms
        $sunroom_service_leads = DB::table('sunroom_service_lead')->get();

        //Handyman
        $handyman_ammount_works = DB::table('handyman_ammount_work')->get();

        //Countertops
        $countertops_service_leads = DB::table('countertops_service_lead')->get();

        //Doors
        $door_typeproject_leads = DB::table('door_typeproject_lead')->get();
        $number_of_door_leads = DB::table('number_of_door_lead')->get();

        //Gutter
        $gutters_meterial_leads = DB::table('gutters_meterial_lead')->get();

        //Paving
        $paving_service_lead = DB::table('paving_service_lead')->get();
        $paving_asphalt_type = DB::table('paving_asphalt_type')->get();
        $paving_loose_fill_type = DB::table('paving_loose_fill_type')->get();
        $paving_best_describes_priject = DB::table('paving_best_describes_priject')->get();

        //Painting
        $painting_service_lead = DB::table('painting_service_lead')->get();
        $painting1_typeof_project = DB::table('painting1_typeof_project')->get();
        $painting1_stories_number = DB::table('painting1_stories_number')->get();
        $painting1_kindsof_surfaces = DB::table('painting1_kindsof_surfaces')->get();
        $painting2_rooms_number = DB::table('painting2_rooms_number')->get();
        $painting2_typeof_paint = DB::table('painting2_typeof_paint')->get();
        $painting3_each_feature = DB::table('painting3_each_feature')->get();
        $painting4_existing_roof = DB::table('painting4_existing_roof')->get();
        $painting5_kindof_texturing = DB::table('painting5_kindof_texturing')->get();
        $painting5_surfaces_textured = DB::table('painting5_surfaces_textured')->get();
        ///////////////////

        return view('Admin.CampaignLeads.CustomerLeadEdit')
            ->with('leadCustomer', $leadCustomer)
            ->with('states', $states)
            ->with('is_with_campaign_details', 0)
            ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
            ->with('painting3_each_feature', $painting3_each_feature)
            ->with('painting4_existing_roof', $painting4_existing_roof)
            ->with('painting5_kindof_texturing', $painting5_kindof_texturing)
            ->with('numberOfWindows', $numberOfWindows)
            ->with('listOfsolor_solution', $listOfsolor_solution)
            ->with('listOfutility_provider', $listOfutility_provider)
            ->with('listOfAVGMoney', $listOfAVGMoney)
            ->with('listOfsun_expouser', $listOfsun_expouser)
            ->with('listOfinstallation_preferences', $listOfinstallation_preferences)
            ->with('listOflead_type_of_flooring', $listOflead_type_of_flooring)
            ->with('listOflead_nature_flooring_project', $listOflead_nature_flooring_project)
            ->with('listOflead_walk_in_tub', $listOflead_walk_in_tub)
            ->with('listOflead_desired_featuers', $listOflead_desired_featuers)
            ->with('listOflead_type_of_roofings', $listOflead_type_of_roofings)
            ->with('listOflead_nature_of_roofings', $listOflead_nature_of_roofings)
            ->with('listOflead_property_type_roofings', $listOflead_property_type_roofings)
            ->with('type_of_siding_leads', $type_of_siding_leads)
            ->with('nature_of_siding_leads', $nature_of_siding_leads)
            ->with('service_kitchen_leads', $service_kitchen_leads)
            ->with('campaign_bathroomtypes', $campaign_bathroomtypes)
            ->with('stairs_type_leads', $stairs_type_leads)
            ->with('stairs_reason_leads', $stairs_reason_leads)
            ->with('furnance_type_leads', $furnance_type_leads)
            ->with('plumbing_service_lists', $plumbing_service_lists)
            ->with('sunroom_service_leads', $sunroom_service_leads)
            ->with('handyman_ammount_works', $handyman_ammount_works)
            ->with('countertops_service_leads', $countertops_service_leads)
            ->with('door_typeproject_leads', $door_typeproject_leads)
            ->with('number_of_door_leads', $number_of_door_leads)
            ->with('gutters_meterial_leads', $gutters_meterial_leads)
            ->with('paving_service_lead', $paving_service_lead)
            ->with('paving_asphalt_type', $paving_asphalt_type)
            ->with('paving_loose_fill_type', $paving_loose_fill_type)
            ->with('paving_best_describes_priject', $paving_best_describes_priject)
            ->with('painting_service_lead', $painting_service_lead)
            ->with('painting1_typeof_project', $painting1_typeof_project)
            ->with('painting1_stories_number', $painting1_stories_number)
            ->with('painting1_kindsof_surfaces', $painting1_kindsof_surfaces)
            ->with('painting2_rooms_number', $painting2_rooms_number)
            ->with('painting2_typeof_paint', $painting2_typeof_paint)
            ->with('painting5_surfaces_textured', $painting5_surfaces_textured)
            ->with('campain_inistallings', $campain_inistallings)
            ->with('lead_prioritys', $lead_prioritys)
            ->with('listOfproperty', $listOfproperty);
    }

    public function UpdateCustomerLead(Request $request)
    {
        //start window questions ==========================================================================
        $api_validations = new APIValidations();
        $questions = $api_validations->check_questions_ids_array($request);
        $dataMassageForBuyers = $questions['dataMassageForBuyers'];
        $Leaddatadetails = $questions['Leaddatadetails'];
        $LeaddataIDs = $questions['LeaddataIDs'];
        $dataMassageForDB = $questions['dataMassageForDB'];
        //end window questions ==========================================================================

        try {
            $dbQuery = LeadsCustomer::where('lead_id', $request->lead_id);

            $allservicesQues = new AllServicesQuestions();

            $allservicesQues->leadCustomerLeadUpdate($dbQuery, $request, $dataMassageForDB);

            //Access LOG
            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => $request->lead_id,
                'section_name' => $request->fname." ".$request->lname,
                'user_role' => Auth::user()->role_id,
                'section'   => 'LeadManagement',
                'action'    => 'Update Lead',
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
            ]);
        } catch (Exception $e) {

        }
        return redirect()->route('list_of_leads_lost');
    }

    public function QAChangeStatusLead(Request $request){
        $this->validate($request, [
            'lead_id' => ['required']
        ]);

        $changeLeadStatus = DB::table('leads_customers')->where('lead_id', $request->lead_id)->update(['QA_status'=> $request->status]);
        return response()->json($changeLeadStatus, 200);
    }
    /////////////////////////////////////

    public function ShowCampaignLeads_lostDetails($lead_id){
        $campaignLeads = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'leads_customers.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'leads_customers.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'leads_customers.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'leads_customers.lead_solor_sun_expouser_list_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'leads_customers.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'leads_customers.property_type_campaign_id')
            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'leads_customers.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'leads_customers.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'leads_customers.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'leads_customers.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'leads_customers.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'leads_customers.lead_property_type_roofing_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'leads_customers.lead_numberOfItem')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'leads_customers.lead_solor_solution_list_id')

            ->leftjoin('countertops_service_lead', 'countertops_service_lead.countertops_service_lead_id', '=', 'leads_customers.countertops_service_lead_id')
            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'leads_customers.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'leads_customers.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'leads_customers.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'leads_customers.gutters_meterial_lead_id')

            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'leads_customers.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'leads_customers.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'leads_customers.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'leads_customers.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'leads_customers.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'leads_customers.stairs_reason_lead_id')
            ->leftjoin('furnance_type_lead', 'furnance_type_lead.furnance_type_lead_id', '=', 'leads_customers.furnance_type_lead_id')
            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'leads_customers.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'leads_customers.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'leads_customers.handyman_ammount_work_id')

            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'leads_customers.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'leads_customers.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'leads_customers.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'leads_customers.paving_best_describes_priject_id')

            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'leads_customers.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'leads_customers.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'leads_customers.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'leads_customers.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'leads_customers.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'leads_customers.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'leads_customers.painting5_surfaces_textured_id')

            ->where('lead_id', $lead_id)
            ->first([
                'lead_priority.lead_priority_name', 'service__campaigns.service_campaign_name',
                'installing_type_campaign.installing_type_campaign', 'leads_customers.*',
                'states.state_name', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'lead_installation_preferences.lead_installation_preferences_name',
                'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_name',
                'lead_avg_money_electicity_list.lead_avg_money_electicity_list_name', 'property_type_campaign.property_type_campaign',
                'lead_type_of_flooring.lead_type_of_flooring_name', 'lead_nature_flooring_project.lead_nature_flooring_project_name',
                'lead_walk_in_tub.lead_walk_in_tub_name', 'lead_type_of_roofing.lead_type_of_roofing_name',
                'lead_nature_of_roofing.lead_nature_of_roofing_name', 'lead_property_type_roofing.lead_property_type_roofing_name',
                'lead_solor_solution_list.lead_solor_solution_list_name', 'number_of_windows_c.number_of_windows_c_type',

                'type_of_siding_lead.type_of_siding_lead_type', 'nature_of_siding_lead.nature_of_siding_lead_type',
                'service_kitchen_lead.service_kitchen_lead_type', '_campaign_bathroomtype.campaign_bathroomtype_type',
                'stairs_type_lead.stairs_type_lead_type', 'stairs_reason_lead.stairs_reason_lead_type',
                'furnance_type_lead.furnance_type_lead_type', 'installing_type_campaign.installing_type_campaign_id',
                'plumbing_service_list.plumbing_service_list_type','sunroom_service_lead.sunroom_service_lead_type',
                'handyman_ammount_work.handyman_ammount_work_type',

                'countertops_service_lead.countertops_service_lead_type',
                'door_typeproject_lead.door_typeproject_lead_type', 'number_of_door_lead.number_of_door_lead_type',
                'gutters_install_type_leade.gutters_install_type_leade_type', 'gutters_meterial_lead.gutters_meterial_lead_type',

                'paving_service_lead.paving_service_lead_type', 'paving_asphalt_type.paving_asphalt_type',
                'paving_loose_fill_type.paving_loose_fill_type',
                'paving_best_describes_priject.paving_best_describes_priject_type',

                'painting_service_lead.painting_service_lead_type', 'painting1_typeof_project.painting1_typeof_project_type',
                'painting1_stories_number.painting1_stories_number_type', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_type',
                'painting2_rooms_number.painting2_rooms_number_type', 'painting2_typeof_paint.painting2_typeof_paint_type',
                'painting5_surfaces_textured.painting5_surfaces_textured_type',
            ]);

        $listOFlead_desired_featuers = DB::table('lead_desired_featuers')->get()->All();
        $painting3_each_feature = DB::table('painting3_each_feature')->get()->All();
        $painting4_existing_roof = DB::table('painting4_existing_roof')->get()->All();
        $painting5_kindof_texturing = DB::table('painting5_kindof_texturing')->get()->All();

        return view('Admin.CampaignLeads.ReceviedLeadDetails')
            ->with('campaignLeads', $campaignLeads)
            ->with('is_with_campaign_details', 0)
            ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
            ->with('painting3_each_feature', $painting3_each_feature)
            ->with('painting4_existing_roof', $painting4_existing_roof)
            ->with('painting5_kindof_texturing', $painting5_kindof_texturing);
    }

    public function export_lead_data(Request $request){
        $type = $request->type;
        $allLead = $request->alllead;
        $type_name = "";

        if(!empty($allLead)) {
            $type = 0 ;
        }

        if( $type == 1 ){
            //Received Lead
            $type_name = "Sold Leads";
            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->where('campaigns_leads_users.is_returned', 0)
                ->where('service__campaigns.service_is_active', 1);

            if( !empty($buyer_id) ){
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if( !empty($seller_id) ){
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if( !empty($service_id) ){
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if( !empty($states) ){
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $lead_data = $campaignLeads->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->get([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.transactionId','campaigns_leads_users.campaigns_leads_users_note'
                ]);
        }
        else if( $type == 2 ){
            //Lost Lead
            $type_name = "Unsold Leads";
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $seller_id = $request->seller_id;

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->leftjoin('campaigns', 'campaigns.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
                ->whereNull('campaigns_leads_users.lead_id');

            if( !empty($seller_id) ){
                $ListOfLeadsNotIn->whereIn('campaigns.user_id', $seller_id);
            }

            if (!empty($environments == 1)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
            } else if (!empty($environments == 3)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', 1);
            } else if (!empty($environments == 7)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 2);
            } else if (!empty($environments == 9)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 3);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            } else if (!empty($environments == 10)) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', "<>",1)
                    ->where('leads_customers.status', 4);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            } else {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            if( !empty($service_id) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if( !empty($states) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            $lead_data = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->get([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list'
                ])->all();
        }
        else if( $type == 3 ) {
            //Return Lead
            $type_name = "Return Leads";
            $buyer_id = $request->buyer_id;
            $states = $request->states;
            $service_id = $request->service_id;
            $start_date = $request->start_date  . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $listOfLeads = DB::table('campaigns_leads_users')->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->leftjoin('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
                ->where('tickets.ticket_type', 2)
                ->where('tickets.ticket_status', 3)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.is_returned', 1);

            if( !empty($buyer_id) ){
                $listOfLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if( !empty($service_id) ){
                $listOfLeads->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if( !empty($states) ){
                $listOfLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $listOfLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $lead_data = $listOfLeads->orderBy('leads_customers.created_at', 'DESC')
                ->get([
                    'users.username', 'service__campaigns.service_campaign_name', 'users.user_business_name',
                    'leads_customers.*', 'Seller.user_business_name AS seller_business_name',
                    'tickets.created_at AS ReturnDate', 'tickets.reason_lead_returned_id', 'tickets.campaigns_leads_users_type_bid',
                    'campaigns.campaign_name', 'tickets.ticket_id', 'tickets.campaigns_leads_users_id', 'states.state_code',
                    'campaigns_leads_users.date AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list'
                ]);
        }
        else if( $type == 4 ) {
            //Archive Lead
            $type_name = "Archive Leads";
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->where('leads_customers.status', 1)
                ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
                ->whereNull('campaigns_leads_users.lead_id');

            if( !empty($service_id) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if( !empty($states) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            $lead_data = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->get([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                ])->all();
        }
        else if( $type == 5 ) {
            //All Lead
            $type_name = "All Leads";
            $buyer_id = $request->buyer_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $traffic_source = $request->traffic_source;
            $google_g = $request->google_g;
            $google_c = $request->google_c;
            $google_k = $request->google_k;

            $county_id = array();
            if( !empty($request->county_id[0]) ){
                $county_id = explode(',', $request->county_id[0]);
            }

            $city_id = array();
            if( !empty($request->city_id[0]) ){
                $city_id = explode(',', $request->city_id[0]);
            }

            $zipcode_id = array();
            if( !empty($request->zipcode_id[0]) ){
                $zipcode_id = explode(',', $request->zipcode_id[0]);
            }

            $zipcode_id_list = array();
            if( $request->hasFile('listOfzipcode') ){
                $dataexcel = Excel::toArray(new SalesOrder, $request->file('listOfzipcode'));
                $dataexcel1 = $dataexcel[0];
                $dataexcel2 = collect($dataexcel1);
                $dataexcel3 = $dataexcel2->pluck('0');
                $dataexcel4 = json_encode($dataexcel3);
                $dataexcel5 = json_decode($dataexcel4, true);
                $zipcode_id_list = $dataexcel5;
            }

            $counties_id_list = array();
            if( $request->hasFile('ListOfCountiesFile') ){
                $dataexcel = Excel::toArray(new SalesOrder, $request->file('ListOfCountiesFile'));
                $dataexcel1 = $dataexcel[0];
                $dataexcel2 = collect($dataexcel1);
                $dataexcel3 = $dataexcel2->pluck('0');
                $dataexcel4 = json_encode($dataexcel3);
                $dataexcel5 = json_decode($dataexcel4, true);
                $counties_id_list = $dataexcel5;
            }

            $cities_id_list = array();
            if( $request->hasFile('ListOfCitiesFile') ){
                $dataexcel = Excel::toArray(new SalesOrder, $request->file('ListOfCitiesFile'));
                $dataexcel1 = $dataexcel[0];
                $dataexcel2 = collect($dataexcel1);
                $dataexcel3 = $dataexcel2->pluck('0');
                $dataexcel4 = json_encode($dataexcel3);
                $dataexcel5 = json_decode($dataexcel4, true);
                $cities_id_list = $dataexcel5;
            }

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id');

            if( !empty($service_id) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if( !empty($states) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($county_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_county_id', $county_id);
            }

            if (!empty($city_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_city_id', $city_id);
            }

            if (!empty($zipcode_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
            }

            if (!empty($zipcode_id_list)) {
                $ListOfLeadsNotIn->whereIn('zip_codes_lists.zip_code_list', $zipcode_id_list);
            }

            if (!empty($counties_id_list)) {
                $ListOfLeadsNotIn->Where(function ($query) use($counties_id_list) {
                    for ($i = 0; $i < count($counties_id_list); $i++){
                        $query->orwhere('counties.county_name', 'like',  '%' . $counties_id_list[$i] .'%');
                    }
                });
//                $ListOfLeadsNotIn->whereIn('counties.county_name', $counties_id_list);
            }

            if (!empty($cities_id_list)) {
                $ListOfLeadsNotIn->Where(function ($query) use($cities_id_list) {
                    for ($i = 0; $i < count($cities_id_list); $i++){
                        $query->orwhere('cities.city_name', 'like',  '%' . $cities_id_list[$i] .'%');
                    }
                });
//                $ListOfLeadsNotIn->whereIn('cities.city_name', $cities_id_list);
            }

            if( !empty($traffic_source) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.google_ts', $traffic_source);
            }

            if( !empty($google_g) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.google_g', $google_g);
            }

            if( !empty($google_c) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.google_c', $google_c);
            }

            if( !empty($google_k) ){
                $ListOfLeadsNotIn->whereIn('leads_customers.google_k', $google_k);
            }

            if( !empty($buyer_id) ){
                $ListOfLeadsNotIn->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            if ($environments == 2) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->whereNotIn('leads_customers.status', [1, 2])
                    ->where('campaigns_leads_users.is_returned', '<>', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 3) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 4) {
                $ListOfLeadsNotIn->where('leads_customers.status', 1);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 5) {
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 6) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead', 1);
            } else if ($environments == 7) {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.status', 2);
            } else if ($environments == 8) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->whereNotIn('leads_customers.status', [1, 2])
                    ->where('campaigns_leads_users.is_returned', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 9) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 3);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else if ($environments == 10) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 4);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            } else {
                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where(function ($query) {
                        $query->whereNull('campaigns_leads_users.is_returned');
                        $query->OrWhere('campaigns_leads_users.is_returned', 0);
                    })
                    ->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            $lead_data = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->get([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code',
                    DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                    'campaigns_leads_users.created_at AS sold_date',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'campaigns_leads_users.is_returned'
                ])->all();
        }
        else if( $type == 6 ) {
            //Affiliate Lead
            $type_name = "Affiliate Leads";
            $buyer_id = $request->buyer_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';
            $environments = $request->environments;
            $seller_id = $request->seller_id;

            $county_id = array();
            if( !empty($request->county_id) ){
                if( is_array($request->county_id) ){
                    if( $request->county_id[0] == null ){
                        $county_id = array();
                    } else {
                        $county_id = $request->county_id;
                    }
                } else {
                    $county_id = explode(',', $request->county_id);
                }
            }

            $city_id = array();
            if( !empty($request->city_id) ){
                if( is_array($request->city_id) ){
                    if( $request->city_id[0] == null ){
                        $city_id = array();
                    } else {
                        $city_id = $request->city_id;
                    }
                } else {
                    $city_id = explode(',', $request->city_id);
                }
            }

            $zipcode_id = array();
            if( !empty($request->zipcode_id) ){
                if( is_array($request->zipcode_id) ){
                    if( $request->zipcode_id[0] == null ){
                        $zipcode_id = array();
                    } else {
                        $zipcode_id = $request->zipcode_id;
                    }
                } else {
                    $zipcode_id = explode(',', $request->zipcode_id);
                }
            }

            $ListOfLeadsNotIn = DB::table('leads_customers')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->leftJoin('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id');

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_state_id', $states);
            }

            if (!empty($county_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_county_id', $county_id);
            }

            if (!empty($city_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_city_id', $city_id);
            }

            if (!empty($zipcode_id)) {
                $ListOfLeadsNotIn->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
            }

            if (!empty($buyer_id)) {
                $ListOfLeadsNotIn->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
            }

            if (!empty($seller_id)) {
                $ListOfLeadsNotIn->whereIn('camp_seller.user_id', $seller_id);
            }

            if ($environments == 2) {
                $ListOfLeadsNotIn->whereNotNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0)
                    ->where('campaigns_leads_users.is_returned', '<>', 1);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
//                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 3) {
                $ListOfLeadsNotIn->whereNull('campaigns_leads_users.lead_id');
                $ListOfLeadsNotIn->where('leads_customers.status', 0);
                $ListOfLeadsNotIn->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
//                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else if ($environments == 5) {
                $ListOfLeadsNotIn->where(function ($query) {
                    $query->where('leads_customers.lead_fname', "test");
                    $query->OrWhere('leads_customers.lead_lname', "test");
                    $query->OrWhere('leads_customers.lead_fname', "testing");
                    $query->OrWhere('leads_customers.lead_lname', "testing");
                    $query->OrWhere('leads_customers.lead_fname', "Test");
                    $query->OrWhere('leads_customers.lead_lname', "Test");
                    $query->OrWhere('leads_customers.is_test', 1);
                });
//                $ListOfLeadsNotIn->where('leads_customers.is_duplicate_lead',"<>", 1);
            }
            else {
                $ListOfLeadsNotIn
                    //->where('leads_customers.is_duplicate_lead',"<>", 1)
                    ->where('leads_customers.lead_fname', '!=', "test")
                    ->where('leads_customers.lead_lname', '!=', "test")
                    ->where('leads_customers.lead_fname', '!=', "testing")
                    ->where('leads_customers.lead_lname', '!=', "testing")
                    ->where('leads_customers.lead_fname', '!=', "Test")
                    ->where('leads_customers.lead_lname', '!=', "Test")
                    ->where('leads_customers.is_test', 0);
            }

            $lead_data = $ListOfLeadsNotIn->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('leads_customers.lead_id')
                ->get([
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'states.state_code',
                    DB::raw("GROUP_CONCAT(users.user_business_name) AS buyerUser"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.campaigns_leads_users_type_bid) AS bid_type"),
                    DB::raw("GROUP_CONCAT(campaigns_leads_users.is_returned) AS is_returned_concat"),
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sum_bid"),
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.is_returned',
                    'Seller.user_business_name AS seller_business_name',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'campaigns_leads_users.is_returned'
                ])->all();
        }
        else if( $type == 7 ){
            //Lead Reviews
            $type_name = "Lead Reviews";
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $traffic_source = $request->traffic_source;
            $google_g = $request->google_g;
            $google_c = $request->google_c;
            $google_k = $request->google_k;

            $county_id = array();
            if( !empty($request->county_id[0]) ){
                $county_id = explode(',', $request->county_id[0]);
            }

            $city_id = array();
            if( !empty($request->city_id[0]) ){
                $city_id = explode(',', $request->city_id[0]);
            }

            $zipcode_id = array();
            if( !empty($request->zipcode_id[0]) ){
                $zipcode_id = explode(',', $request->zipcode_id[0]);
            }

            $ListOfLeadsNotIn = DB::table('lead_reviews')
                ->leftJoin('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'lead_reviews.lead_type_service_id')
                ->join('states', 'states.state_id', '=', 'lead_reviews.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'lead_reviews.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'lead_reviews.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'lead_reviews.lead_zipcode_id')
                ->whereNotNull('lead_reviews.lead_fname')
                ->whereNotNull('lead_reviews.lead_lname');

            if( !empty($service_id) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_type_service_id', $service_id);
            }

            if( !empty($states) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_state_id', $states);
            }

            if (!empty($county_id)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_county_id', $county_id);
            }

            if (!empty($city_id)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_city_id', $city_id);
            }

            if (!empty($zipcode_id)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_zipcode_id', $zipcode_id);
            }

            if( !empty($traffic_source) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_ts', $traffic_source);
            }

            if( !empty($google_g) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_g', $google_g);
            }

            if( !empty($google_c) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_c', $google_c);
            }

            if( !empty($google_k) ){
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_k', $google_k);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $ListOfLeadsNotIn->whereBetween('lead_reviews.created_at', [$start_date, $end_date]);
            }

            $lead_data = $ListOfLeadsNotIn->orderBy('lead_reviews.created_at', 'DESC')
                ->get([
                    'lead_reviews.*','states.state_code',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    DB::raw('(CASE WHEN lead_reviews.is_multi_service = 1 THEN "Multi Services" ELSE service__campaigns.service_campaign_name END) AS lead_service_name'),
                ]);
        }
        else if( $type == 8 ){
            //PING Leads
            $type_name = "PING Leads";
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $environments = $request->environments;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->start_date . ' 23:59:59';
            //$end_date = $request->end_date . ' 23:59:59';

            $campaignLeads = PingLeads::join('campaigns', 'campaigns.vendor_id', '=', 'ping_leads.vendor_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('users', 'users.id', '=', 'campaigns.user_id')
                ->join('states', 'states.state_id', '=', 'ping_leads.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'ping_leads.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'ping_leads.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'ping_leads.lead_zipcode_id')
                ->where('service__campaigns.service_is_active', 1);

            if( !empty($seller_id) ){
                $campaignLeads->whereIn('campaigns.user_id', $seller_id);
            }

            if( !empty($service_id) ){
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if( !empty($states) ){
                $campaignLeads->whereIn('ping_leads.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $campaignLeads->whereBetween('ping_leads.created_at', [$start_date, $end_date]);
            }

            if( $environments == 1 ){
                $campaignLeads->where('ping_leads.is_test', 1);
            } else {
                $campaignLeads->where('ping_leads.is_test', 0);
            }

            $DB_MYSQL_NAME = config('database.connections.mysql.DB_MYSQL_NAME', '');
            $lead_data = $campaignLeads->orderBy('ping_leads.created_at', 'DESC')
                ->get([
                    'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'ping_leads.*',
                    'states.state_code', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    DB::raw("(SELECT GROUP_CONCAT(CONCAT(camp.campaign_name, ' (', u_s.user_business_name, ')') SEPARATOR ', ') AS camp_name FROM campaigns AS camp
                        INNER JOIN users AS u_s ON camp.user_id = u_s.id WHERE camp.campaign_id IN
                        (select distinct substring_index(substring_index(REPLACE(REPLACE(p_l.campaign_id_arr, ']', ''), '[', ''),',',b.help_topic_id+1),',',-1)
                        from ping_leads p_l join  $DB_MYSQL_NAME.help_topic b  on b.help_topic_id < (length(p_l.campaign_id_arr) - length(replace(p_l.campaign_id_arr,',',''))+1)
                        WHERE p_l.Lead_id = ping_leads.Lead_id)) AS buyer_camp_names")
                ]);
        }
        else if( $type == 9 ){
            //CallCenter Leads
            $type_name = "Call Center Leads";
            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date  . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->where('campaigns_leads_users.is_returned', 0)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.call_center', 1);

            if( !empty($buyer_id) ){
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if( !empty($seller_id) ){
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if( !empty($service_id) ){
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if( !empty($states) ){
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $lead_data = $campaignLeads->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->get([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.transactionId'
                ]);
        }
        else if( $type == 10 ){
            //Return CallCenter Leads
            $type_name = "Return Call Center Leads";
            $buyer_id = $request->buyer_id;
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date  . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $campaignLeads = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('tickets', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
                ->leftjoin('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
                ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->where('campaigns_leads_users.is_returned', 1)
                ->where('service__campaigns.service_is_active', 1)
                ->where('campaigns_leads_users.call_center', 1);

            if( !empty($buyer_id) ){
                $campaignLeads->whereIn('campaigns_leads_users.user_id', $buyer_id);
            }

            if( !empty($seller_id) ){
                $campaignLeads->whereIn('camp_seller.user_id', $seller_id);
            }

            if( !empty($service_id) ){
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if( !empty($states) ){
                $campaignLeads->whereIn('leads_customers.lead_state_id', $states);
            }

            if( !empty($start_date) && !empty($end_date) ){
                $campaignLeads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
            }

            $lead_data = $campaignLeads->orderBy('leads_customers.created_at', 'DESC')
                ->groupBy('campaigns_leads_users.campaigns_leads_users_id')
                ->get([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'users.username', 'users.user_business_name',
                    'service__campaigns.service_campaign_name', 'leads_customers.*', 'campaigns_leads_users.agent_name',
                    'campaigns_leads_users.campaigns_leads_users_type_bid', 'states.state_code',
                    'campaigns_leads_users.created_at AS created_at_lead', 'campaigns_leads_users.campaigns_leads_users_bid',
                    'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'campaigns_leads_users.created_at AS sold_date', 'campaigns_leads_users.transactionId',
                    'tickets.created_at AS ReturnDate'
                ]);
        }

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export $type_name",
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Export',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
        $info_permission = 0;
        if( empty($permission_users) || in_array('8-23', $permission_users) ){
            $info_permission = 1;
        }

        if( $type == 1 ){
            //Sold Lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->campaigns_leads_users_id,
                    'Lead Id' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Bid' => $lead->campaigns_leads_users_bid,
                    'Type' => $lead->campaigns_leads_users_type_bid,
                    'Buyer' => $lead->user_business_name,
                    'Campaign' => $lead->campaign_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Sold Date' => date('m/d/Y H:i:s', strtotime($lead->sold_date)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'Status' => $lead->campaigns_leads_users_note,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'transactionId' => $lead->transactionId,
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3,
                    'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                    'TCPA Language' => $lead->tcpa_consent_text
                ];
            });
        }
        else if( $type == 2 ){
            //Unsold lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'Flag' => (isset($lead->flag) && in_array($lead->flag, [1, 2]) ? true : false),
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3,
                    'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                    'TCPA Language' => $lead->tcpa_consent_text
                ];
            });
        }
        else if( $type == 3 ) {
            //Return Lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->campaigns_leads_users_id,
                    'Lead Id' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Bid' => $lead->campaigns_leads_users_bid,
                    'Sold Lead' => date('m/d/Y H:i:s', strtotime($lead->created_at_lead)),
                    'Type' => $lead->campaigns_leads_users_type_bid,
                    'Buyer' => $lead->user_business_name,
                    'Campaign' => $lead->campaign_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    "Lead From" => (!empty($lead->seller_business_name) ? $lead->seller_business_name : "O&O"),
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Return Date' => date('m/d/Y H:i:s', strtotime($lead->ReturnDate)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3
                ];
            });
        }
        else if( $type == 4 ) {
            //Archive Lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'Flag' => (isset($lead->flag) && in_array($lead->flag, [1, 2]) ? true : false),
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3
                ];
            });
        }
        else if( $type == 5 ) {
            //All Lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Status' => (!empty($lead->sum_bid) ? ($lead->is_returned_concat == 1 ? "Returned" : "Sold") : "Unsold"),
                    'Bid' => (!empty($lead->sum_bid) ? $lead->sum_bid : "0"),
                    'SoldTo' => $lead->buyerUser,
                    'Sold Type' => $lead->bid_type,
                    'Sold Date' => (!empty($lead->sum_bid) ? date('m/d/Y H:i:s', strtotime($lead->sold_date)) : ""),
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'QA Status' => $lead->QA_status,
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'Flag' => (isset($lead->flag) && in_array($lead->flag, [1, 2]) ? true : false),
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3,
                    'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                    'TCPA Language' => $lead->tcpa_consent_text
                ];
            });
        }
        else if( $type == 6 ){
            //AFFILIATE  Lead
            $start_date = Carbon::parse($request->start_date . ' 00:00:00');
            $end_date = Carbon::parse($request->end_date . ' 23:59:59');
            $diff_days = $end_date->diffInDays($start_date);

            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($diff_days, $info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Status' => (!empty($lead->sum_bid) ? (implode(",", array_unique(explode(",", $lead->is_returned_concat))) == 1 ? "Returned" : "Sold") : "Unsold"),
                    'Selling Price' => (!empty($lead->sum_bid) ? $lead->sum_bid : "0"),
                    'SoldTo' => $lead->buyerUser,
                    'Sold Date' => (!empty($lead->sum_bid) ? date('m/d/Y H:i:s', strtotime($lead->sold_date)) : ""),
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Response' => $lead->response_data,
//                    "Rejected By" => ($diff_days <= 7 && $lead->response_data == "All buyers have rejected this lead" ? implode(", ", DB::table("campaigns")->whereIn("campaign_id", json_decode($lead->ping_campaign_id_arr, true))->pluck('campaign_name')->toArray()) : ""),
                    "Rejected By" => ($diff_days <= 7 && $lead->response_data == "All buyers have rejected this lead" ? $lead->ping_campaign_id_arr : ""),
                    'LeadFrom' => $lead->seller_business_name,
                    'Purchasing Price' => $lead->ping_price,
                    'Transaction ID' => $lead->token,
                    'Returned' => (!empty($lead->is_returned_concat) ? str_replace(1, "True", str_replace(0, "False", $lead->is_returned_concat)) : "False"),
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'QA Status' => $lead->QA_status,
                    'TS' => $lead->google_ts,
                    'Full URL' => $lead->lead_FullUrl,
                    'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                    'TCPA Language' => $lead->tcpa_consent_text
                ];
            });
        }
        else if( $type == 7 ){
            //Lead Review
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Type' => ($lead->is_multi_service == 1 ? "Multi Services" : "Single Service"),
                    'Service' => $lead->lead_service_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'TS' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3
                ];
            });
        }
        else if( $type == 8 ){
            //PING Lead
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->lead_id,
                    'Vendor ID' => $lead->vendor_id,
                    'Seller Name' => $lead->user_business_name,
                    'Campaign Name' => $lead->campaign_name,
                    'Service' => $lead->service_campaign_name,
                    'Status' => $lead->status,
                    'Purchasing Price' => $lead->price,
                    'SoldTo' => $lead->buyer_camp_names,
                    'Selling Price' => $lead->original_price,
                    'Transaction ID' => $lead->transaction_id,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Traffic Source' => $lead->lead_source_text,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'Full URL' => $lead->lead_FullUrl,
                    'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                    'TCPA Language' => $lead->tcpa_consent_text
                ];
            });
        }
        else if( $type == 9 ){
            //Call Center Leads
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->campaigns_leads_users_id,
                    'Lead Id' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Bid' => $lead->campaigns_leads_users_bid,
                    'Type' => $lead->campaigns_leads_users_type_bid,
                    'Buyer' => $lead->user_business_name,
                    'Campaign' => $lead->campaign_name,
                    'Agent Name' => $lead->agent_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'Traffic Source' => $lead->traffic_source,
                    'TS Main' => $lead->lead_source_text,
                    'TS Sub' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'transactionId' => $lead->transactionId,
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3
                ];
            });
        }
        else if( $type == 10 ){
            //Return Call Center Leads
            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) use($info_permission) {
                return [
                    'ID' => $lead->campaigns_leads_users_id,
                    'Lead Id' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                    'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                    'Service' => $lead->service_campaign_name,
                    'Bid' => $lead->campaigns_leads_users_bid,
                    'Type' => $lead->campaigns_leads_users_type_bid,
                    'Buyer' => $lead->user_business_name,
                    'Campaign' => $lead->campaign_name,
                    'Agent Name' => $lead->agent_name,
                    'Address' => ($info_permission == 1 ? $lead->lead_address : ""),
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Website' => $lead->lead_serverDomain,
                    'Time On Browser' => $lead->lead_timeInBrowseData,
                    'IP Address' => $lead->lead_ipaddress,
                    'PC info' => $lead->lead_aboutUserBrowser,
                    'Browser' => $lead->lead_browser_name,
                    'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
                    'Return Date' => date('m/d/Y H:i:s', strtotime($lead->ReturnDate)),
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                    'Traffic Source' => $lead->traffic_source,
                    'TS Main' => $lead->lead_source_text,
                    'TS Sub' => $lead->google_ts,
                    'C' => $lead->google_c,
                    'K' => $lead->google_k,
                    'G' => $lead->google_g,
                    'Token' => $lead->token,
                    'Visitor ID' => $lead->visitor_id,
                    'Full URL' => $lead->lead_FullUrl,
                    'transactionId' => $lead->transactionId,
                    'gclid' => $lead->google_gclid,
                    's1' => $lead->pushnami_s1,
                    's2' => $lead->pushnami_s2,
                    's3' => $lead->pushnami_s3
                ];
            });
        }
    }

    public function deleteSoldLead($id)
    {
        $getCampaignsLead = DB::table('campaigns_leads_users')->where('campaigns_leads_users_id', $id)->first();

        $getLead = DB::table('leads_customers')->where('lead_id',  $getCampaignsLead->lead_id)->first();

        DB::table('campaigns_leads_users')->where('campaigns_leads_users_id', $id)->delete();

        $totalAmmount = TotalAmount::where('user_id', $getCampaignsLead->user_id)->first('total_amounts_value');

        if( empty($totalAmmount) ){
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $getCampaignsLead->user_id;
            $addtotalAmmount->total_amounts_value = $getCampaignsLead->campaigns_leads_users_bid;

            $addtotalAmmount->save();
        } else {
            $total = $getCampaignsLead->campaigns_leads_users_bid + $totalAmmount['total_amounts_value'];
            TotalAmount::where('user_id', $getCampaignsLead->user_id)
                ->update([ 'total_amounts_value' => $total ]);
        }

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $getLead->lead_fname." ".$getLead->lead_lname,
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Delete Sold Lead',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
        ]);

        return back();
    }

    public function deletelead($id)
    {
        $getLead = DB::table('leads_customers')->where('lead_id', '=', $id)->first();

        DB::table('leads_customers')->where('lead_id', '=', $id)->update([ 'status' => 1 ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $getLead->lead_fname." ".$getLead->lead_lname,
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Delete UnSold Lead',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
        ]);

        return back();
    }
}
