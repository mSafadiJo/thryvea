<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadReview extends Model
{
    public function state(){
        return $this->belongsTo('App\State');
    }

    public function county(){
        return $this->belongsTo('App\County');
    }

    public function city(){
        return $this->belongsTo('App\City');
    }

    public function service_campaign(){
        return $this->belongsTo('App\Service_Campaign');
    }

    public function zipcode(){
        return $this->belongsTo('App\ZipCodesList');
    }

    protected $fillable = [
        'lead_id','lead_fname', 'lead_lname', 'lead_address', 'lead_email', 'lead_phone_number',
        'lead_numberOfItem', 'lead_ownership', 'lead_type_service_id', 'lead_installing_id',
        'lead_priority_id', 'lead_state_id', 'lead_county_id', 'lead_city_id', 'lead_zipcode_id', 'lead_website',
        'lead_solor_solution_list_id', 'lead_solor_sun_expouser_list_id',
        'lead_current_utility_provider_id', 'lead_avg_money_electicity_list_id', 'property_type_campaign_id',
        'lead_installation_preferences_id', 'lead_have_item_before_it',
        'lead_type_of_flooring_id', 'lead_nature_flooring_project_id', 'lead_walk_in_tub_id',
        'lead_desired_featuers_id', 'lead_type_of_roofing_id', 'lead_nature_of_roofing_id',
        'lead_property_type_roofing_id', 'type_of_siding_lead_id', 'nature_of_siding_lead_id',
        'is_duplicate_lead', 'lead_source_text', 'lead_details_text', 'status', 'vendor_id', 'lead_ping_id',
        'google_ts','google_c','google_g','google_k', 'is_verified_phone', 'is_complete_lead',
        'visitor_leads_id', 'zip_codes_review', 'cities_review',
        'zipcodes_changes', 'cities_changes', 'counties_changes', 'states_changes', 'is_multi_service',
        'pushnami_s1', 'pushnami_s2', 'pushnami_s3', 'google_gclid',
        'VehicleYear', 'VehicleMake', 'car_model', 'more_than_one_vehicle', 'driversNum', 'birthday', 'genders',
        'married', 'license', 'InsuranceCarrier', 'driver_experience', 'number_of_tickets', 'DUI_charges', 'SR_22_need',
        'submodel', 'coverage_type', 'license_status', 'license_state', 'ticket_date', 'violation_date', 'accident_date',
        'claim_date', 'expiration_date'
    ];
}
