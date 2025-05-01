<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\PingLeads;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PingDataController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '8-14';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index(){
//        $start_date = date('Y-m-d') . ' 00:00:00';
//        $end_date = date('Y-m-d') . ' 23:59:59';

        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $sellers = DB::table('users')->whereIn('role_id', ['4', '5'])->where('user_visibility', 1)->get()->All();
        $states = State::All();

        $data = array(
            'services' => $services,
            'sellers' => $sellers,
            'states' => $states,
        );

//        $DB_MYSQL_NAME = config('database.connections.mysql.DB_MYSQL_NAME', '');

//        $ListOfLeads = PingLeads::join('campaigns', 'campaigns.vendor_id', '=', 'ping_leads.vendor_id')
//            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
//            ->join('users', 'users.id', '=', 'campaigns.user_id')
//            ->join('states', 'states.state_id', '=', 'ping_leads.lead_state_id')
//            ->where('campaigns.campaign_visibility', 1)
//            ->where('service__campaigns.service_is_active', 1)
//            ->whereBetween('ping_leads.created_at', [$start_date, $end_date])
//            ->orderBy('ping_leads.created_at', 'DESC')
//            ->select([
//                "ping_leads.*", "campaigns.campaign_name", "service__campaigns.service_campaign_name",
//                "users.user_business_name", "states.state_code",
//                DB::raw("(SELECT GROUP_CONCAT(CONCAT(camp.campaign_name, ' (', u_s.user_business_name, ')') SEPARATOR ', ') AS camp_name FROM campaigns AS camp
//                        INNER JOIN users AS u_s ON camp.user_id = u_s.id WHERE camp.campaign_id IN
//                        (select distinct substring_index(substring_index(REPLACE(REPLACE(p_l.campaign_id_arr, ']', ''), '[', ''),',',b.help_topic_id+1),',',-1)
//                        from ping_leads p_l join  $DB_MYSQL_NAME.help_topic b  on b.help_topic_id < (length(p_l.campaign_id_arr) - length(replace(p_l.campaign_id_arr,',',''))+1)
//                        WHERE p_l.Lead_id = ping_leads.Lead_id)) AS buyer_camp_names")
//            ])->paginate(10);

        return view('Admin.CampaignLeads.Ping.index', compact('data'));
    }

    public function fetch_data_lead_Ping(Request $request){
        if($request->ajax()) {
            $seller_id = $request->seller_id;
            $service_id = $request->service_id;
            $states = $request->states;
            $environments = $request->environments;
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->start_date . ' 23:59:59';
            //$end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $DB_MYSQL_NAME = config('database.connections.mysql.DB_MYSQL_NAME', '');

            $campaignLeads = PingLeads::join('campaigns', 'campaigns.vendor_id', '=', 'ping_leads.vendor_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('users', 'users.id', '=', 'campaigns.user_id')
                ->join('states', 'states.state_id', '=', 'ping_leads.lead_state_id')
                ->where('campaigns.campaign_visibility', 1)
                ->where('service__campaigns.service_is_active', 1)
                ->where(function ($query) use ($query_search) {
                    $query->where('ping_leads.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('ping_leads.lead_bid_type', 'like', '%' . $query_search . '%');
                    $query->orWhere('ping_leads.price', 'like', '%' . $query_search . '%');
                    $query->orWhere('ping_leads.status', 'like', '%' . $query_search . '%');
                    $query->orWhere('users.user_business_name', 'like', '%' . $query_search . '%');
                    $query->orWhere('campaigns.campaign_name', 'like', '%' . $query_search . '%');
                });

            if (!empty($seller_id)) {
                $campaignLeads->whereIn('campaigns.user_id', $seller_id);
            }

            if (!empty($service_id)) {
                $campaignLeads->whereIn('campaigns.service_campaign_id', $service_id);
            }

            if (!empty($states)) {
                $campaignLeads->whereIn('ping_leads.lead_state_id', $states);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $campaignLeads->whereBetween('ping_leads.created_at', [$start_date, $end_date]);
            }

            if ($environments == 1) {
                $campaignLeads->where('ping_leads.is_test', 1);
            } else {
                $campaignLeads->where('ping_leads.is_test', 0);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $campaignLeads->whereBetween('ping_leads.created_at', [$start_date, $end_date]);
            }

            $ListOfLeads = $campaignLeads->orderBy('ping_leads.created_at', 'DESC')
                ->select([
                    "ping_leads.*", "campaigns.campaign_name", "service__campaigns.service_campaign_name",
                    "users.user_business_name", "states.state_code",
                    DB::raw("(SELECT GROUP_CONCAT(CONCAT(camp.campaign_name, ' (', u_s.user_business_name, ')') SEPARATOR ', ') AS camp_name FROM campaigns AS camp
                        INNER JOIN users AS u_s ON camp.user_id = u_s.id WHERE camp.campaign_id IN
                        (select distinct substring_index(substring_index(REPLACE(REPLACE(p_l.campaign_id_arr, ']', ''), '[', ''),',',b.help_topic_id+1),',',-1)
                        from ping_leads p_l join  $DB_MYSQL_NAME.help_topic b  on b.help_topic_id < (length(p_l.campaign_id_arr) - length(replace(p_l.campaign_id_arr,',',''))+1)
                        WHERE p_l.Lead_id = ping_leads.Lead_id)) AS buyer_camp_names")
                ])
                ->simplePaginate(10);
                //->paginate(10);

            return view('Render.Lead_Ping_Render', compact('ListOfLeads'))->render();
        }
    }

    public function details($id){
        $campaignLeads = PingLeads::join('campaigns', 'campaigns.vendor_id', '=', 'ping_leads.vendor_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('states', 'states.state_id', '=', 'ping_leads.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'ping_leads.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'ping_leads.lead_city_id')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'ping_leads.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'ping_leads.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'ping_leads.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'ping_leads.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'ping_leads.lead_solor_sun_expouser_list_id')
            //->leftjoin('lead_current_utility_provider', 'lead_current_utility_provider.lead_current_utility_provider_id', '=', 'ping_leads.lead_current_utility_provider_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'ping_leads.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'ping_leads.property_type_campaign_id')

            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'ping_leads.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'ping_leads.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'ping_leads.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'ping_leads.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'ping_leads.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'ping_leads.lead_property_type_roofing_id')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'ping_leads.lead_solor_solution_list_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'ping_leads.lead_numberOfItem')

            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'ping_leads.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'ping_leads.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'ping_leads.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'ping_leads.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'ping_leads.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'ping_leads.stairs_reason_lead_id')
            ->leftjoin('furnance_type_lead', 'furnance_type_lead.furnance_type_lead_id', '=', 'ping_leads.furnance_type_lead_id')
            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'ping_leads.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'ping_leads.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'ping_leads.handyman_ammount_work_id')

            ->leftjoin('countertops_service_lead', 'countertops_service_lead.countertops_service_lead_id', '=', 'ping_leads.countertops_service_lead_id')
            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'ping_leads.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'ping_leads.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'ping_leads.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'ping_leads.gutters_meterial_lead_id')

            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'ping_leads.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'ping_leads.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'ping_leads.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'ping_leads.paving_best_describes_priject_id')

            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'ping_leads.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'ping_leads.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'ping_leads.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'ping_leads.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'ping_leads.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'ping_leads.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'ping_leads.painting5_surfaces_textured_id')

            ->where('campaigns.campaign_visibility', 1)
            ->where('ping_leads.lead_id', $id)
            ->first([
                'campaigns.campaign_name', 'lead_priority.lead_priority_name',
                'service__campaigns.service_campaign_name', 'installing_type_campaign.installing_type_campaign',
                'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number', 'users.user_business_name',
                'states.state_name', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list', 'ping_leads.*',
                'lead_installation_preferences.lead_installation_preferences_name',
                'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_name',
                //'lead_current_utility_provider.lead_current_utility_provider_name',
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

        return view('Admin.CampaignLeads.Ping.details')
            ->with('is_with_campaign_details', 1)
            ->with('campaignLeads', $campaignLeads)
            ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
            ->with('painting3_each_feature', $painting3_each_feature)
            ->with('painting4_existing_roof', $painting4_existing_roof)
            ->with('painting5_kindof_texturing', $painting5_kindof_texturing);
    }
}
