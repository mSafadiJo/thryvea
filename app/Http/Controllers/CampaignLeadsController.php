<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignLeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer']);
    }

    public function ShowCampaignLeadsDetails($id){
        $campaignLeads = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->leftjoin('installing_type_campaign', 'installing_type_campaign.installing_type_campaign_id', '=', 'leads_customers.lead_installing_id')
            ->leftjoin('lead_priority', 'lead_priority.lead_priority_id', '=', 'leads_customers.lead_priority_id')
            ->leftjoin('lead_installation_preferences', 'lead_installation_preferences.lead_installation_preferences_id', '=', 'leads_customers.lead_installation_preferences_id')
            ->leftjoin('lead_solor_sun_expouser_list', 'lead_solor_sun_expouser_list.lead_solor_sun_expouser_list_id', '=', 'leads_customers.lead_solor_sun_expouser_list_id')
            //->leftjoin('lead_current_utility_provider', 'lead_current_utility_provider.lead_current_utility_provider_id', '=', 'leads_customers.lead_current_utility_provider_id')
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

            ->where('campaigns_leads_users.user_id', Auth::user()->id)
            //->where('campaigns.campaign_visibility', 1)
            ->where('campaigns_leads_users.campaigns_leads_users_id', $id)
            ->first([
                'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name', 'lead_priority.lead_priority_name',
                'service__campaigns.service_campaign_name', 'installing_type_campaign.installing_type_campaign',
                'states.state_name', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'leads_customers.*', 'campaigns_leads_users.campaigns_leads_users_bid', 'campaigns_leads_users.lead_id_token_md',
                'lead_installation_preferences.lead_installation_preferences_name', 'campaigns_leads_users.is_recorded',
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

        if( !empty($campaignLeads) ){
            return view('Buyers.CampaignLeads.leadDetails')
                ->with('campaignLeads', $campaignLeads)
                ->with('listOFlead_desired_featuers', $listOFlead_desired_featuers)
                ->with('painting3_each_feature', $painting3_each_feature)
                ->with('painting4_existing_roof', $painting4_existing_roof)
                ->with('painting5_kindof_texturing', $painting5_kindof_texturing);
        } else {
            return redirect()->route('home');
        }

    }
}
