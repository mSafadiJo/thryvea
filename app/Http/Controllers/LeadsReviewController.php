<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\LeadReview;
use App\LeadsCustomer;
use App\LeadTrafficSources;
use App\Service_Campaign;
use App\Services\AllServicesQuestions;
use App\Services\APIValidations;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadsReviewController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function list_of_leads_review(){
        $states = State::All();
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        //$list_of_ts = LeadTrafficSources::pluck('name')->toArray();
        //$list_of_g = DB::table('leads_customers')->groupBy('google_g')->pluck('google_g')->toArray();
        //$list_of_c = DB::table('leads_customers')->groupBy('google_c')->pluck('google_c')->toArray();
        //$list_of_k = DB::table('leads_customers')->groupBy('google_k')->pluck('google_k')->toArray();
        $data = array(
            'services' => $services,
            'states' => $states,
            //'list_of_ts' => $list_of_ts,
            //'list_of_g' => $list_of_g,
            //'list_of_c' => $list_of_c,
            //'list_of_k' => $list_of_k
        );

        $yesterday = date('Y-m-d', strtotime('-1 days')) . ' 00:00';
        $today = date('Y-m-d') . ' 23:59';

        $ListOfLeads = DB::table('lead_reviews')
            ->join('states', 'states.state_id', '=', 'lead_reviews.lead_state_id')
            ->whereNotNull('lead_reviews.lead_fname')
            ->whereNotNull('lead_reviews.lead_lname')
            ->whereBetween('lead_reviews.created_at', [$yesterday, $today])
            ->orderBy('lead_reviews.created_at', 'DESC')
            ->select(['lead_reviews.*','states.state_code',])->paginate(10);

        return view('Admin.LeadsReview.list_lead_review')
            ->with('data', $data)
            ->with('ListOfLeads', $ListOfLeads);
    }

    public function fetch_data_lead_Review(Request $request){
        if($request->ajax()) {
            $service_id = $request->service_id;
            $states = $request->states;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $traffic_source = $request->traffic_source;
            $google_g = $request->google_g;
            $google_c = $request->google_c;
            $google_k = $request->google_k;

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

            $ListOfLeadsNotIn = DB::table('lead_reviews')
                ->join('states', 'states.state_id', '=', 'lead_reviews.lead_state_id')
                ->whereNotNull('lead_reviews.lead_fname')
                ->whereNotNull('lead_reviews.lead_lname')
                ->where(function ($query) use ($query_search) {
                    $query->where('lead_reviews.lead_id', 'like', '%' . $query_search . '%');
                    $query->orWhere('lead_reviews.lead_fname', 'like', '%' . $query_search . '%');
                    $query->orWhere('lead_reviews.lead_lname', 'like', '%' . $query_search . '%');
                    $query->orWhere(DB::raw("concat(lead_reviews.lead_fname, ' ', lead_reviews.lead_lname)"), 'like', "%".$query_search."%");
                    $query->orWhere('lead_reviews.lead_source_text', 'like', '%' . $query_search . '%');
                    $query->orWhere('lead_reviews.status', 'like', '%' . $query_search . '%');
                    $query->orWhere('lead_reviews.google_ts', 'like', '%' . $query_search . '%');
                });

            if (!empty($service_id)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.lead_type_service_id', $service_id);
            }

            if (!empty($states)) {
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

            if (!empty($traffic_source)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_ts', $traffic_source);
            }

            if (!empty($google_g)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_g', $google_g);
            }

            if (!empty($google_c)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_c', $google_c);
            }

            if (!empty($google_k)) {
                $ListOfLeadsNotIn->whereIn('lead_reviews.google_k', $google_k);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $ListOfLeadsNotIn->whereBetween('lead_reviews.created_at', [$start_date, $end_date]);
            }

            $ListOfLeads = $ListOfLeadsNotIn->orderBy('lead_reviews.created_at', 'DESC')
                ->select([
                    'lead_reviews.*', 'states.state_code',
                ])->paginate(10);

            return view('Render.Lead_Review_Render', compact('ListOfLeads'))->render();
        }
    }



    public function deletelead($id)
    {
        $getLead = DB::table('lead_reviews')->where('lead_id', $id)->first();


        DB::table('lead_reviews')->where('lead_id', $id)->delete();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $getLead->lead_fname." ".$getLead->lead_lname,
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Delete Review Lead',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
        ]);

        return back();
    }

    public function Leads_ReviewDetails($lead_id){
        $leadReviews = DB::table('lead_reviews')
            //  ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'lead_reviews.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'lead_reviews.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'lead_reviews.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'lead_reviews.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'lead_reviews.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'lead_reviews.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'lead_reviews.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'lead_reviews.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'lead_reviews.lead_solor_sun_expouser_list_id')
            //->leftjoin('lead_current_utility_provider', 'lead_current_utility_provider.lead_current_utility_provider_id', '=', 'lead_reviews.lead_current_utility_provider_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'lead_reviews.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'lead_reviews.property_type_campaign_id')
            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'lead_reviews.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'lead_reviews.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'lead_reviews.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'lead_reviews.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'lead_reviews.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'lead_reviews.lead_property_type_roofing_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'lead_reviews.lead_numberOfItem')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'lead_reviews.lead_solor_solution_list_id')

            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'lead_reviews.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'lead_reviews.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'lead_reviews.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'lead_reviews.gutters_meterial_lead_id')

            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'lead_reviews.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'lead_reviews.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'lead_reviews.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'lead_reviews.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'lead_reviews.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'lead_reviews.stairs_reason_lead_id')

            ->leftjoin('furnance_type_lead AS furnance_type_lead_A', 'furnance_type_lead_A.furnance_type_lead_id', '=', 'lead_reviews.furnance_type_lead_id')
            ->leftJoin('furnance_type_lead AS furnance_type_lead_B' , 'furnance_type_lead_B.furnance_type_lead_id', '=' , 'lead_reviews.furnance_type_f')
            ->leftJoin('furnance_type_lead AS furnance_type_lead_C' , 'furnance_type_lead_C.furnance_type_lead_id', '=' , 'lead_reviews.furnance_type_b')

            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'lead_reviews.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'lead_reviews.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'lead_reviews.handyman_ammount_work_id')

            ->leftjoin('countertops_service_lead', 'countertops_service_lead.countertops_service_lead_id', '=', 'lead_reviews.countertops_service_lead_id')

            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'lead_reviews.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'lead_reviews.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'lead_reviews.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'lead_reviews.paving_best_describes_priject_id')

            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'lead_reviews.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'lead_reviews.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'lead_reviews.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'lead_reviews.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'lead_reviews.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'lead_reviews.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'lead_reviews.painting5_surfaces_textured_id')

            ->where('lead_id', $lead_id)
            ->first([
                'lead_priority.lead_priority_name', 'installing_type_campaign.installing_type_campaign', 'lead_reviews.*',
                'states.state_name',
                'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
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
                'installing_type_campaign.installing_type_campaign_id',
                'plumbing_service_list.plumbing_service_list_type','sunroom_service_lead.sunroom_service_lead_type',
                'handyman_ammount_work.handyman_ammount_work_type',

                'furnance_type_lead_A.furnance_type_lead_type AS furnance_type_lead_type_A',
                'furnance_type_lead_B.furnance_type_lead_type AS furnance_type_lead_type_B',
                'furnance_type_lead_C.furnance_type_lead_type AS furnance_type_lead_type_C',

                'door_typeproject_lead.door_typeproject_lead_type', 'number_of_door_lead.number_of_door_lead_type',
                'gutters_install_type_leade.gutters_install_type_leade_type', 'gutters_meterial_lead.gutters_meterial_lead_type',

                'countertops_service_lead.countertops_service_lead_type',

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
        $lead_installing_array = array('1'=>'Install', '2'=>'Replace' , '3'=>'Repair');

        return view('Admin.LeadsReview.ReviewLeadDetails')
            ->with('leadReviews', $leadReviews)
            ->with('is_with_campaign_details', 0)
            ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
            ->with('painting3_each_feature', $painting3_each_feature)
            ->with('painting4_existing_roof', $painting4_existing_roof)
            ->with('painting5_kindof_texturing', $painting5_kindof_texturing)
            ->with('lead_installing_array', $lead_installing_array);
    }

    public function Editlead($lead_id){

        $leadReviews = DB::table('lead_reviews')
            // ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'lead_reviews.lead_type_service_id')
            ->join('states', 'states.state_id', '=', 'lead_reviews.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'lead_reviews.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'lead_reviews.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'lead_reviews.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'lead_reviews.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'lead_reviews.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'lead_reviews.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'lead_reviews.lead_solor_sun_expouser_list_id')
            //->leftjoin('lead_current_utility_provider', 'lead_current_utility_provider.lead_current_utility_provider_id', '=', 'lead_reviews.lead_current_utility_provider_id')
            ->leftjoin('lead_avg_money_electicity_list', 'lead_avg_money_electicity_list.lead_avg_money_electicity_list_id', '=', 'lead_reviews.lead_avg_money_electicity_list_id')
            ->leftjoin('property_type_campaign', 'property_type_campaign.property_type_campaign_id', '=', 'lead_reviews.property_type_campaign_id')
            ->leftjoin('lead_type_of_flooring', 'lead_type_of_flooring.lead_type_of_flooring_id', '=', 'lead_reviews.lead_type_of_flooring_id')
            ->leftjoin('lead_nature_flooring_project', 'lead_nature_flooring_project.lead_nature_flooring_project_id', '=', 'lead_reviews.lead_nature_flooring_project_id')
            ->leftjoin('lead_walk_in_tub', 'lead_walk_in_tub.lead_walk_in_tub_id', '=', 'lead_reviews.lead_walk_in_tub_id')
            ->leftjoin('lead_type_of_roofing', 'lead_type_of_roofing.lead_type_of_roofing_id', '=', 'lead_reviews.lead_type_of_roofing_id')
            ->leftjoin('lead_nature_of_roofing', 'lead_nature_of_roofing.lead_nature_of_roofing_id', '=', 'lead_reviews.lead_nature_of_roofing_id')
            ->leftjoin('lead_property_type_roofing', 'lead_property_type_roofing.lead_property_type_roofing_id', '=', 'lead_reviews.lead_property_type_roofing_id')
            ->leftjoin('number_of_windows_c', 'number_of_windows_c.number_of_windows_c_id', '=', 'lead_reviews.lead_numberOfItem')
            ->leftjoin('lead_solor_solution_list', 'lead_solor_solution_list.lead_solor_solution_list_id', '=', 'lead_reviews.lead_solor_solution_list_id')

            ->leftjoin('door_typeproject_lead', 'door_typeproject_lead.door_typeproject_lead_id', '=', 'lead_reviews.door_typeproject_lead_id')
            ->leftjoin('number_of_door_lead', 'number_of_door_lead.number_of_door_lead_id', '=', 'lead_reviews.number_of_door_lead_id')
            ->leftjoin('gutters_install_type_leade', 'gutters_install_type_leade.gutters_install_type_leade_id', '=', 'lead_reviews.gutters_install_type_leade_id')
            ->leftjoin('gutters_meterial_lead', 'gutters_meterial_lead.gutters_meterial_lead_id', '=', 'lead_reviews.gutters_meterial_lead_id')

            ->leftjoin('type_of_siding_lead', 'type_of_siding_lead.type_of_siding_lead_id', '=', 'lead_reviews.type_of_siding_lead_id')
            ->leftjoin('nature_of_siding_lead', 'nature_of_siding_lead.nature_of_siding_lead_id', '=', 'lead_reviews.nature_of_siding_lead_id')
            ->leftjoin('service_kitchen_lead', 'service_kitchen_lead.service_kitchen_lead_id', '=', 'lead_reviews.service_kitchen_lead_id')
            ->leftjoin('_campaign_bathroomtype', '_campaign_bathroomtype.campaign_bathroomtype_id', '=', 'lead_reviews.campaign_bathroomtype_id')
            ->leftjoin('stairs_type_lead', 'stairs_type_lead.stairs_type_lead_id', '=', 'lead_reviews.stairs_type_lead_id')
            ->leftjoin('stairs_reason_lead', 'stairs_reason_lead.stairs_reason_lead_id', '=', 'lead_reviews.stairs_reason_lead_id')
            ->leftjoin('furnance_type_lead', 'furnance_type_lead.furnance_type_lead_id', '=', 'lead_reviews.furnance_type_lead_id')
            ->leftjoin('plumbing_service_list', 'plumbing_service_list.plumbing_service_list_id', '=', 'lead_reviews.plumbing_service_list_id')
            ->leftjoin('sunroom_service_lead', 'sunroom_service_lead.sunroom_service_lead_id', '=', 'lead_reviews.sunroom_service_lead_id')
            ->leftjoin('handyman_ammount_work', 'handyman_ammount_work.handyman_ammount_work_id', '=', 'lead_reviews.handyman_ammount_work_id')

            ->leftjoin('paving_service_lead', 'paving_service_lead.paving_service_lead_id', '=', 'lead_reviews.paving_service_lead_id')
            ->leftjoin('paving_asphalt_type', 'paving_asphalt_type.paving_asphalt_type_id', '=', 'lead_reviews.paving_asphalt_type_id')
            ->leftjoin('paving_loose_fill_type', 'paving_loose_fill_type.paving_loose_fill_type_id', '=', 'lead_reviews.paving_loose_fill_type_id')
            ->leftjoin('paving_best_describes_priject', 'paving_best_describes_priject.paving_best_describes_priject_id', '=', 'lead_reviews.paving_best_describes_priject_id')

            ->leftjoin('painting_service_lead', 'painting_service_lead.painting_service_lead_id', '=', 'lead_reviews.painting_service_lead_id')
            ->leftjoin('painting1_typeof_project', 'painting1_typeof_project.painting1_typeof_project_id', '=', 'lead_reviews.painting1_typeof_project_id')
            ->leftjoin('painting1_stories_number', 'painting1_stories_number.painting1_stories_number_id', '=', 'lead_reviews.painting1_stories_number_id')
            ->leftjoin('painting1_kindsof_surfaces', 'painting1_kindsof_surfaces.painting1_kindsof_surfaces_id', '=', 'lead_reviews.painting1_kindsof_surfaces_id')
            ->leftjoin('painting2_rooms_number', 'painting2_rooms_number.painting2_rooms_number_id', '=', 'lead_reviews.painting2_rooms_number_id')
            ->leftjoin('painting2_typeof_paint', 'painting2_typeof_paint.painting2_typeof_paint_id', '=', 'lead_reviews.painting2_typeof_paint_id')
            ->leftjoin('painting5_surfaces_textured', 'painting5_surfaces_textured.painting5_surfaces_textured_id', '=', 'lead_reviews.painting5_surfaces_textured_id')

            ->where('lead_id', $lead_id)
            ->first([
                'lead_priority.lead_priority_name', 'installing_type_campaign.installing_type_campaign', 'lead_reviews.*',
                'states.state_name','states.state_id','cities.city_id','counties.county_id',
                'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
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
                'plumbing_service_list.plumbing_service_list_type','sunroom_service_lead.sunroom_service_lead_type',
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

        //list of services
        $services = DB::table('service__campaigns')->get();

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

        return view('Admin.LeadsReview.ReviewLeadEdit')
            ->with('leadReviews', $leadReviews)
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
            ->with('painting5_surfaces_textured', $painting5_surfaces_textured )

            ->with('campain_inistallings', $campain_inistallings)
            ->with('lead_prioritys', $lead_prioritys)
            ->with('listOfproperty', $listOfproperty );
    }

    public function UpdateLeadReview(Request $request)
    {
        //start window questions ==========================================================================
        if($request->is_multi_service == 0){
            $api_validations = new APIValidations();
            $questions = $api_validations->check_questions_ids_array($request);
            $dataMassageForBuyers = $questions['dataMassageForBuyers'];
            $Leaddatadetails = $questions['Leaddatadetails'];
            $LeaddataIDs = $questions['LeaddataIDs'];
            $dataMassageForDB = $questions['dataMassageForDB'];
        }
        //end window questions ==========================================================================

        try {
            if($request->is_multi_service == 0){
                $dbQuery = LeadReview::where('lead_id', $request->lead_id);

                $allservicesQues = new AllServicesQuestions();

                $allservicesQues->leadReviewLeadUpdate($dbQuery, $request, $dataMassageForDB);

            } else {
                LeadReview::where('lead_id', $request->lead_id)->update([
                    "lead_fname" => $request['fname'],
                    "lead_lname" => $request['lname'],
                    "lead_address" => $request['Street'],
                    "lead_email" => $request['email'],
                    "lead_phone_number" => $request['phone'],
                    "lead_state_id" => $request['state'],
                    "lead_city_id" => $request['city'],
                    "lead_zipcode_id" => $request['zipcode'],
                    "lead_county_id" => $request['County']
                ]);
            }

            //Access LOG
            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => $request->lead_id,
                'section_name' => $request->fname." ".$request->lname,
                'user_role' => Auth::user()->role_id,
                'section'   => 'LeadManagement',
                'action'    => 'Update Lead Review',
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
            ]);
        } catch (Exception $e) {

        }

        if( $request['completed'] == 1 ) {
            try {
                $leadsCustomer = new LeadsCustomer();

                $allservicesQues = new AllServicesQuestions();

                $leadsCustomer = $allservicesQues->leadReviewCompleteLeadCustomerSave($leadsCustomer, $request, $dataMassageForDB);

                $leadsCustomer->save();
            } catch (Exception $e) {

            }
        }
        return redirect()->route("list_of_leads_Review");
    }


}
