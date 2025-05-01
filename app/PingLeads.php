<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PingLeads extends Model
{
    protected $fillable = [
        'lead_fname', 'lead_lname', 'lead_address', 'lead_email', 'lead_phone_number',
        'lead_numberOfItem', 'lead_ownership', 'lead_type_service_id', 'lead_installing_id',
        'lead_priority_id', 'lead_state_id', 'lead_county_id', 'lead_city_id', 'lead_zipcode_id', 'lead_website',
        'lead_solor_solution_list_id', 'lead_solor_sun_expouser_list_id',
        'lead_current_utility_provider_id', 'lead_avg_money_electicity_list_id', 'property_type_campaign_id',
        'lead_installation_preferences_id', 'lead_have_item_before_it',
        'lead_type_of_flooring_id', 'lead_nature_flooring_project_id', 'lead_walk_in_tub_id',
        'lead_desired_featuers_id', 'lead_type_of_roofing_id', 'lead_nature_of_roofing_id',
        'lead_property_type_roofing_id', 'type_of_siding_lead_id', 'nature_of_siding_lead_id',
        'is_duplicate_lead', 'lead_source_text', 'lead_details_text', 'status', 'hash_legs_sold',
        'submodel', 'coverage_type', 'license_status', 'license_state', 'ticket_date', 'violation_date',
        'accident_date', 'claim_date', 'expiration_date'
    ];
}
