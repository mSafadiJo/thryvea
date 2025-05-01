<?php

namespace App\Http\Controllers\Map;

use App\Campaign;
use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mapController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function map(){
        $from_date = date('Y-m-01') . " 00:00:00";
        $to_date = date('Y-m-t') . " 23:59:59";
        $service_id_map = "";
        $status_map = 1;
        $service_map = DB::table('service__campaigns')->select('service_campaign_name','service_campaign_id')->get();

        $lead_volumes = LeadsCustomer::join('states','states.state_id', '=', 'leads_customers.lead_state_id')
            ->where('leads_customers.is_duplicate_lead',  "<>", 1 )
            ->where('leads_customers.status', 0)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=',"test")
            ->where('leads_customers.lead_fname','!=', "testing")
            ->where('leads_customers.lead_lname','!=',"testing")
            ->where('leads_customers.lead_fname', '!=',"Test")
            ->where('leads_customers.lead_lname','!=',"Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.created_at', '>=', $from_date)
            ->where('leads_customers.created_at', '<=', $to_date);

        $lead_volume_data = $lead_volumes->groupBy('states.state_id')->get([
            DB::raw("COUNT(DISTINCT leads_customers.lead_id) AS totallead"),
            "states.state_code AS states",
        ]);

        $array_filiter_state_1 = array();
        $array_filiter_state_2 = array();
        $array_filiter_state_3 = array();
        $array_filiter_state_4 = array();
        $array_filiter_state_5 = array();
        $array_filiter_state_6 = array();
        foreach($lead_volume_data as $lead_volume ){
            if ($lead_volume->totallead > 200) {
                $array_filiter_state_1[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 160 && $lead_volume->totallead <= 199) {
                $array_filiter_state_2[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 90 && $lead_volume->totallead <= 159) {
                $array_filiter_state_3[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 75 && $lead_volume->totallead <= 89) {
                $array_filiter_state_4[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 50 && $lead_volume->totallead <= 74) {
                $array_filiter_state_5[] = $lead_volume->states;
            } else if ($lead_volume->totallead < 50) {
                $array_filiter_state_6[] = $lead_volume->states;
            }
        }

        return view('Reports.LeadMap')
            ->with("array_filiter_state_1",$array_filiter_state_1)
            ->with("array_filiter_state_2",$array_filiter_state_2)
            ->with("array_filiter_state_3",$array_filiter_state_3)
            ->with("array_filiter_state_4",$array_filiter_state_4)
            ->with("array_filiter_state_5",$array_filiter_state_5)
            ->with("array_filiter_state_6",$array_filiter_state_6)
            ->with("service_map",$service_map)
            ->with("service_id_map",$service_id_map)
            ->with("status_map",$status_map)
            ->with("lead_volume_data",$lead_volume_data);
    }

    public function map_search(Request $request){
        $from_date = $request->start_date;
        $to_date = $request->end_date;

        $service_map = DB::table('service__campaigns')->select('service_campaign_name','service_campaign_id')->get();

        $lead_volumes = LeadsCustomer::join('states','states.state_id', '=', 'leads_customers.lead_state_id')
            ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id');

        $service_id_map = array();
        if( !empty($request->service_id) ){
            $service_id_map = $request->service_id;
        }
        if (!empty($service_id_map)) {
            $lead_volumes->whereIn('leads_customers.lead_type_service_id', $service_id_map)
                ->where('leads_customers.is_duplicate_lead',  "<>", 1 )
                ->where('leads_customers.status', 0)
                ->where('leads_customers.lead_fname', '!=', "test")
                ->where('leads_customers.lead_lname', '!=',"test")
                ->where('leads_customers.lead_fname','!=', "testing")
                ->where('leads_customers.lead_lname','!=',"testing")
                ->where('leads_customers.lead_fname', '!=',"Test")
                ->where('leads_customers.lead_lname','!=',"Test")
                ->where('leads_customers.is_test', 0)
                ->where('leads_customers.created_at', '>=', $from_date . " 00:00:00")
                ->where('leads_customers.created_at', '<=', $to_date . " 23:59:59");
        }

        $status_map = $request->status;
        if ($status_map == 2) {
            $lead_volumes->whereNotNull('campaigns_leads_users.lead_id')
                ->pluck('campaigns_leads_users.lead_id')->toArray();
        } else if ($status_map == 3) {
            $lead_volumes->whereNull('campaigns_leads_users.lead_id')
                ->pluck('campaigns_leads_users.lead_id')->toArray();
        }
        $lead_volume_data = $lead_volumes->groupBy('states.state_id')->get([
            DB::raw("COUNT(DISTINCT leads_customers.lead_id) AS totallead"),
            "states.state_code AS states",
        ]);

        $array_filiter_state_1 = array();
        $array_filiter_state_2 = array();
        $array_filiter_state_3 = array();
        $array_filiter_state_4 = array();
        $array_filiter_state_5 = array();
        $array_filiter_state_6 = array();
        foreach($lead_volume_data as $lead_volume ){
            if ($lead_volume->totallead > 200) {
                $array_filiter_state_1[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 160 && $lead_volume->totallead <= 199) {
                $array_filiter_state_2[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 90 && $lead_volume->totallead <= 159) {
                $array_filiter_state_3[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 75 && $lead_volume->totallead <= 89) {
                $array_filiter_state_4[] = $lead_volume->states;
            } else if ($lead_volume->totallead >= 50 && $lead_volume->totallead <= 74) {
                $array_filiter_state_5[] = $lead_volume->states;
            } else if ($lead_volume->totallead < 50) {
                $array_filiter_state_6[] = $lead_volume->states;
            }
        }

        // print_r($service_id_map); die();
        return view('Reports.LeadMap')
            ->with("array_filiter_state_1",$array_filiter_state_1)
            ->with("array_filiter_state_2",$array_filiter_state_2)
            ->with("array_filiter_state_3",$array_filiter_state_3)
            ->with("array_filiter_state_4",$array_filiter_state_4)
            ->with("array_filiter_state_5",$array_filiter_state_5)
            ->with("array_filiter_state_6",$array_filiter_state_6)
            ->with("from_date",$from_date)
            ->with("to_date",$to_date)
            ->with("service_map",$service_map)
            ->with("service_id_map",$service_id_map)
            ->with("status_map",$status_map)
            ->with("lead_volume_data",$lead_volume_data);
    }

    public function mapBuyer(){
        $service_id_map = "";
        $service_map = DB::table('service__campaigns')->select('service_campaign_name','service_campaign_id')->get();

        $campaigns = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->whereNotIn('campaigns.campaign_Type', array(2, 3))
            ->where('users.user_visibility', 1)
            ->where('campaigns.is_seller', 0);

        $Buyers_volume_data = $campaigns->groupBy('users.id')->get([
            DB::raw("COUNT(DISTINCT users.id) AS totalLead"),
            DB::raw("GROUP_CONCAT(campaign_target_area.stateFilter_code) AS stateFilter"),
            'users.id AS USERID'
        ]);

        $array_filiter_state_1 = array();
        $array_filiter_state_2 = array();
        $array_filiter_state_3 = array();
        $array_filiter_state_4 = array();
        $array_filiter_state_5 = array();
        $array_filiter_state_6 = array();
        $array_filiter_state_7 = array();
        $array_data = array();

        foreach($Buyers_volume_data as $lead_volume ){
            if(!empty($lead_volume->stateFilter)) {
                $states = str_replace('[', '', $lead_volume->stateFilter);
                $states = str_replace(']', '', $states);
                $states .="]";
                $states2 ="[".$states;
                if(is_array(json_decode($states2))) {
                    $states2 = array_unique(json_decode($states2));
                } else {
                    $states2 = array(
                        "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS",
                        "KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY",
                        "NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV",
                        "WI","WY","DC",
                    );
                }

                foreach($states2 as $state) {
                    if(!empty($array_data[$state])){
                        $totallead =  $lead_volume->totalLead + $array_data[$state]['total'];
                    } else {
                        $totallead =  $lead_volume->totalLead;
                    }
                    $array_data[$state] = array("state" => $state , "total" => $totallead);
                }
            }
        }

        foreach($array_data as $lead_volume ) {
            if ($lead_volume['total'] > 10) {
                $array_filiter_state_1[] = $lead_volume['state'];
            } else if ($lead_volume['total'] >= 6 && $lead_volume['total'] <= 10) {
                $array_filiter_state_2[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 5) {
                $array_filiter_state_3[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 4) {
                $array_filiter_state_4[] =$lead_volume['state'];
            } else if ($lead_volume['total'] == 3) {
                $array_filiter_state_5[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 2) {
                $array_filiter_state_6[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 1) {
                $array_filiter_state_7[] = $lead_volume['state'];
            }
        }

        return view('Reports.BuyersMap')
            ->with("array_filiter_state_1",$array_filiter_state_1)
            ->with("array_filiter_state_2",$array_filiter_state_2)
            ->with("array_filiter_state_3",$array_filiter_state_3)
            ->with("array_filiter_state_4",$array_filiter_state_4)
            ->with("array_filiter_state_5",$array_filiter_state_5)
            ->with("array_filiter_state_6",$array_filiter_state_6)
            ->with("array_filiter_state_7",$array_filiter_state_7)
            ->with("service_map",$service_map)
            ->with("service_id_map",$service_id_map)
            ->with("Buyers_volume_data",$Buyers_volume_data)
            ->with("array_data",$array_data);
    }

    public function Buyers_map_search(Request $request){
        $service_map = DB::table('service__campaigns')->select('service_campaign_name','service_campaign_id')->get();
        $campaigns = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->whereNotIn('campaigns.campaign_Type', array(2, 3))
            ->where('users.user_visibility', 1)
            ->where('campaigns.is_seller', 0);

        $service_id_map = array();
        if( !empty($request->service_id) ){
            $service_id_map = $request->service_id;
        }
        if (!empty($service_id_map)) {
            $campaigns->whereIn('campaigns.service_campaign_id', $request->service_id);
        }
        if (!empty($request->user_type)) {
            $campaigns->whereIn('users.role_id', $request->user_type);
        }

        $Buyers_volume_data = $campaigns->groupBy('users.id')->get([
            DB::raw("COUNT(DISTINCT users.id) AS totalLead"),
            DB::raw("GROUP_CONCAT(campaign_target_area.stateFilter_code) AS stateFilter"),
            'users.id AS USERID'
        ]);

        $array_filiter_state_1 = array();
        $array_filiter_state_2 = array();
        $array_filiter_state_3 = array();
        $array_filiter_state_4 = array();
        $array_filiter_state_5 = array();
        $array_filiter_state_6 = array();
        $array_filiter_state_7 = array();
        $array_data = array();

        foreach($Buyers_volume_data as $lead_volume ){
            if(!empty($lead_volume->stateFilter)) {
                $states = str_replace('[', '', $lead_volume->stateFilter);
                $states = str_replace(']', '', $states);
                $states .="]";
                $states2 ="[".$states;
                if(is_array(json_decode($states2))) {
                    $states2 = array_unique(json_decode($states2));
                } else {
                    $states2 = array(
                        "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS",
                        "KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY",
                        "NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV",
                        "WI","WY","DC",
                    );
                }

                foreach($states2 as $state) {
                    if(!empty($array_data[$state])){
                        $totallead =  $lead_volume->totalLead + $array_data[$state]['total'];
                    } else {
                        $totallead =  $lead_volume->totalLead;
                    }
                    $array_data[$state] = array("state" => $state , "total" => $totallead);
                }
            }
        }

        foreach($array_data as $lead_volume ) {
            if ($lead_volume['total'] > 10) {
                $array_filiter_state_1[] = $lead_volume['state'];
            } else if ($lead_volume['total'] >= 6 && $lead_volume['total'] <= 10) {
                $array_filiter_state_2[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 5) {
                $array_filiter_state_3[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 4) {
                $array_filiter_state_4[] =$lead_volume['state'];
            } else if ($lead_volume['total'] == 3) {
                $array_filiter_state_5[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 2) {
                $array_filiter_state_6[] = $lead_volume['state'];
            } else if ($lead_volume['total'] == 1) {
                $array_filiter_state_7[] = $lead_volume['state'];
            }
        }

        return view('Reports.BuyersMap')
            ->with("array_filiter_state_1",$array_filiter_state_1)
            ->with("array_filiter_state_2",$array_filiter_state_2)
            ->with("array_filiter_state_3",$array_filiter_state_3)
            ->with("array_filiter_state_4",$array_filiter_state_4)
            ->with("array_filiter_state_5",$array_filiter_state_5)
            ->with("array_filiter_state_6",$array_filiter_state_6)
            ->with("array_filiter_state_7",$array_filiter_state_7)
            ->with("service_map",$service_map)
            ->with("service_id_map",$service_id_map)
            ->with("UserMap",$request->user_type)
            ->with("array_data",$array_data);
    }
}
