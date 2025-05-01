<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{
    public function listOFInstalling(Request $request){
        $campain_inistallings['installing'] = DB::table('installing_type_campaign')->get();

        return $campain_inistallings;
    }

    public function listOFpriority(Request $request){
        $lead_prioritys['lead_priority'] = DB::table('lead_priority')->get();

        return $lead_prioritys;
    }

    public function listOFNumberOfWindows(Request $request){
        $numberOfWindows['numberOfWindows'] = DB::table('number_of_windows_c')->get();

        return $numberOfWindows;
    }

    public function lead_avg_money_electicity_list(Request $request){
        $listOfAVGMoney['lead_avg_money_electicity_list'] = DB::table('lead_avg_money_electicity_list')->get();

        return $listOfAVGMoney;
    }

    public function lead_current_utility_provider(Request $request){
        $listOfutility_provider['lead_current_utility_provider'] = DB::table('lead_current_utility_provider')->get();

        return $listOfutility_provider;
    }

    public function lead_solor_solution_list(Request $request){
        $listOfsolor_solution['lead_solor_solution_list'] = DB::table('lead_solor_solution_list')->get();

        return $listOfsolor_solution;
    }

    public function lead_solor_sun_expouser_list(Request $request){
        $listOfsun_expouser['lead_solor_sun_expouser_list'] = DB::table('lead_solor_sun_expouser_list')->get();

        return $listOfsun_expouser;
    }

    public function property_type_campaign(Request $request){
        $listOfproperty['property_type_campaign'] = DB::table('property_type_campaign')->get();

        return $listOfproperty;
    }

    public function lead_installation_preferences(Request $request){
        $listOfinstallation_preferences['lead_installation_preferences'] = DB::table('lead_installation_preferences')->get();

        return $listOfinstallation_preferences;
    }

    public function lead_type_of_flooring(Request $request){
        $listOflead_type_of_flooring['lead_type_of_flooring'] = DB::table('lead_type_of_flooring')->get();

        return $listOflead_type_of_flooring;
    }

    public function lead_nature_flooring_project(Request $request){
        $listOflead_nature_flooring_project['lead_nature_flooring_project'] = DB::table('lead_nature_flooring_project')->get();

        return $listOflead_nature_flooring_project;
    }

    public function lead_walk_in_tub(Request $request){
        $listOflead_walk_in_tub['lead_walk_in_tub'] = DB::table('lead_walk_in_tub')->get();

        return $listOflead_walk_in_tub;
    }

    public function lead_desired_featuers(Request $request){
        $listOflead_desired_featuers['lead_desired_featuers'] = DB::table('lead_desired_featuers')->get();

        return $listOflead_desired_featuers;
    }

    public function lead_type_of_roofing(Request $request){
        $listOflead_type_of_roofings['lead_type_of_roofing'] = DB::table('lead_type_of_roofing')->get();

        return $listOflead_type_of_roofings;
    }

    public function lead_nature_of_roofing(Request $request){
        $listOflead_nature_of_roofings['lead_nature_of_roofing'] = DB::table('lead_nature_of_roofing')->get();

        return $listOflead_nature_of_roofings;
    }

    public function lead_property_type_roofing(Request $request){
        $listOflead_property_type_roofings['lead_property_type_roofing'] = DB::table('lead_property_type_roofing')->get();

        return $listOflead_property_type_roofings;
    }

    public function type_of_siding_lead(Request $request){
        $type_of_siding_leads['type_of_siding_lead'] = DB::table('type_of_siding_lead')->get();

        return $type_of_siding_leads;
    }

    public function nature_of_siding_lead(Request $request){
        $nature_of_siding_leads['nature_of_siding_lead'] = DB::table('nature_of_siding_lead')->get();

        return $nature_of_siding_leads;
    }

    public function service_kitchen_lead(Request $request){
        $service_kitchen_leads['service_kitchen_lead'] = DB::table('service_kitchen_lead')->get();

        return $service_kitchen_leads;
    }

    public function campaign_bathroomtype(Request $request){
        $campaign_bathroomtypes['campaign_bathroomtype'] = DB::table('_campaign_bathroomtype')->get();

        return $campaign_bathroomtypes;
    }

    public function stairs_type_lead(Request $request){
        $stairs_type_leads['stairs_type_lead'] = DB::table('stairs_type_lead')->get();

        return $stairs_type_leads;
    }

    public function stairs_reason_lead(Request $request){
        $stairs_reason_leads['stairs_reason_lead'] = DB::table('stairs_reason_lead')->get();

        return $stairs_reason_leads;
    }

    public function furnance_type_lead(Request $request){
        $furnance_type_leads['furnance_type_lead'] = DB::table('furnance_type_lead')->get();

        return $furnance_type_leads;
    }

    public function plumbing_service_list(Request $request){
        $plumbing_service_lists['plumbing_service_list'] = DB::table('plumbing_service_list')->get();

        return $plumbing_service_lists;
    }

    public function sunroom_service_lead(Request $request){
        $sunroom_service_leads['sunroom_service_lead'] = DB::table('sunroom_service_lead')->get();

        return $sunroom_service_leads;
    }

    public function handyman_service_lead(Request $request){
        $handyman_service_leads['handyman_service_lead'] = DB::table('handyman_service_lead')->get();

        return $handyman_service_leads;
    }

    public function handyman_ammount_work(Request $request){
        $handyman_ammount_works['handyman_ammount_work'] = DB::table('handyman_ammount_work')->get();

        return $handyman_ammount_works;
    }

    public function handyman_childproofing_services(Request $request){
        $handyman_childproofing_services['handyman_childproofing_services'] = DB::table('handyman_childproofing_services')->get();

        return $handyman_childproofing_services;
    }

    public function handyman_doors_windows_many(Request $request){
        $handyman_doors_windows_many['handyman_doors_windows_many'] = DB::table('handyman_doors_windows_many')->get();

        return $handyman_doors_windows_many;
    }

    public function handyman_range_of_age(Request $request){
        $handyman_range_of_age['handyman_range_of_age'] = DB::table('handyman_range_of_age')->get();

        return $handyman_range_of_age;
    }

    public function countertops_service_lead(Request $request){
        $countertops_service_leads['countertops_service_lead'] = DB::table('countertops_service_lead')->get();

        return $countertops_service_leads;
    }

    public function door_typeproject_lead(Request $request){
        $door_typeproject_leads['door_typeproject_lead'] = DB::table('door_typeproject_lead')->get();

        return $door_typeproject_leads;
    }

    public function number_of_door_lead(Request $request){
        $number_of_door_leads['number_of_door_lead'] = DB::table('number_of_door_lead')->get();

        return $number_of_door_leads;
    }

    public function gutters_install_type_leade(Request $request){
        $gutters_install_type_leades['gutters_install_type_leade'] = DB::table('gutters_install_type_leade')->get();

        return $gutters_install_type_leades;
    }

    public function gutters_meterial_lead(Request $request){
        $gutters_meterial_leads['gutters_meterial_lead'] = DB::table('gutters_meterial_lead')->get();

        return $gutters_meterial_leads;
    }

    public function paving_service_lead(Request $request){
        $paving_service_lead['paving_service_lead'] = DB::table('paving_service_lead')->get();

        return $paving_service_lead;
    }

    public function paving_asphalt_type(Request $request){
        $paving_asphalt_type['paving_asphalt_type'] = DB::table('paving_asphalt_type')->get();

        return $paving_asphalt_type;
    }

    public function paving_loose_fill_type(Request $request){
        $paving_loose_fill_type['paving_loose_fill_type'] = DB::table('paving_loose_fill_type')->get();

        return $paving_loose_fill_type;
    }

    public function paving_best_describes_priject(Request $request){
        $paving_best_describes_priject['paving_best_describes_priject'] = DB::table('paving_best_describes_priject')->get();

        return $paving_best_describes_priject;
    }

    public function painting_service_lead(Request $request){
        $painting_service_lead['painting_service_lead'] = DB::table('painting_service_lead')->get();

        return $painting_service_lead;
    }

    public function painting1_typeof_project(Request $request){
        $painting1_typeof_project['painting1_typeof_project'] = DB::table('painting1_typeof_project')->get();

        return $painting1_typeof_project;
    }

    public function painting1_stories_number(Request $request){
        $painting1_stories_number['painting1_stories_number'] = DB::table('painting1_stories_number')->get();

        return $painting1_stories_number;
    }

    public function painting1_kindsof_surfaces(Request $request){
        $painting1_kindsof_surfaces['painting1_kindsof_surfaces'] = DB::table('painting1_kindsof_surfaces')->get();

        return $painting1_kindsof_surfaces;
    }

    public function painting2_rooms_number(Request $request){
        $painting2_rooms_number['painting2_rooms_number'] = DB::table('painting2_rooms_number')->get();

        return $painting2_rooms_number;
    }

    public function painting2_typeof_paint(Request $request){
        $painting2_typeof_paint['painting2_typeof_paint'] = DB::table('painting2_typeof_paint')->get();

        return $painting2_typeof_paint;
    }

    public function painting3_each_feature(Request $request){
        $painting3_each_feature['painting3_each_feature'] = DB::table('painting3_each_feature')->get();

        return $painting3_each_feature;
    }

    public function painting4_existing_roof(Request $request){
        $painting4_existing_roof['painting4_existing_roof'] = DB::table('painting4_existing_roof')->get();

        return $painting4_existing_roof;
    }

    public function painting5_kindof_texturing(Request $request){
        $painting5_kindof_texturing['painting5_kindof_texturing'] = DB::table('painting5_kindof_texturing')->get();

        return $painting5_kindof_texturing;
    }

    public function painting5_surfaces_textured(Request $request){
        $painting5_surfaces_textured['painting5_surfaces_textured'] = DB::table('painting5_surfaces_textured')->get();

        return $painting5_surfaces_textured;
    }
}
