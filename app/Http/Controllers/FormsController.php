<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FormsController extends Controller
{
    //Leads ===============================================================================================================================
    public function index(){
        //States
        $states = State::All();

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
        $listOfAVGMoney = DB::table('lead_avg_money_electicity_list')->get();
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

        return view('Forms.index',
            compact(
                'states', 'services',
                'lead_prioritys', 'campain_inistallings', 'listOfproperty',
                'numberOfWindows',
                'listOfsolor_solution', 'listOfutility_provider', 'listOfAVGMoney', 'listOfsun_expouser',
                'listOfinstallation_preferences',
                'listOflead_type_of_flooring', 'listOflead_nature_flooring_project',
                'listOflead_walk_in_tub', 'listOflead_desired_featuers',
                'listOflead_type_of_roofings', 'listOflead_nature_of_roofings', 'listOflead_property_type_roofings',
                'type_of_siding_leads', 'nature_of_siding_leads',
                'service_kitchen_leads',
                'campaign_bathroomtypes',
                'stairs_type_leads', 'stairs_reason_leads',
                'furnance_type_leads',
                'plumbing_service_lists',
                'sunroom_service_leads',
                'handyman_ammount_works',
                'countertops_service_leads',
                'door_typeproject_leads', 'number_of_door_leads',
                'gutters_meterial_leads',
                'paving_service_lead', 'paving_asphalt_type', 'paving_loose_fill_type', 'paving_best_describes_priject',
                'painting_service_lead', 'painting1_typeof_project', 'painting1_stories_number', 'painting1_kindsof_surfaces',
                'painting2_rooms_number', 'painting2_typeof_paint', 'painting3_each_feature', 'painting4_existing_roof',
                'painting5_kindof_texturing', 'painting5_surfaces_textured'
            )
        );
    }
    //Leads ===============================================================================================================================

}