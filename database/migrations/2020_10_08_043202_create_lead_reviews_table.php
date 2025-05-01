<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_reviews', function (Blueprint $table) {
            $table->bigIncrements('lead_id');
            $table->string('lead_fname')->nullable();
            $table->string('lead_lname')->nullable();
            $table->string('lead_address')->nullable();
            $table->string('lead_email')->nullable();

            $table->string('lead_phone_number')->nullable();
            $table->string('lead_numberOfItem')->nullable();
            $table->integer('lead_ownership')->nullable();
            $table->string('lead_type_service_id')->nullable();

            $table->string('lead_installing_id')->nullable();
            $table->integer('lead_priority_id')->nullable();
            $table->integer('lead_state_id')->nullable();
            $table->integer('lead_county_id')->nullable();

            $table->integer('lead_city_id')->nullable();
            $table->integer('lead_zipcode_id')->nullable();
            $table->string('lead_serverDomain')->nullable();
            $table->string('lead_timeInBrowseData')->nullable();

            $table->string('lead_ipaddress')->nullable();
            $table->string('lead_FullUrl')->nullable();
            $table->string('lead_aboutUserBrowser')->nullable();
            $table->string('lead_browser_name')->nullable();
            $table->string('lead_website')->nullable();

            $table->integer('lead_solor_solution_list_id')->nullable();
            $table->integer('lead_solor_sun_expouser_list_id')->nullable();
            $table->string('lead_current_utility_provider_id')->nullable();

            $table->integer('lead_avg_money_electicity_list_id')->nullable();
            $table->integer('lead_installation_preferences_id')->nullable();
            $table->integer('lead_have_item_before_it')->nullable();
            $table->integer('lead_type_of_flooring_id')->nullable();

            $table->integer('lead_nature_flooring_project_id')->nullable();
            $table->integer('lead_walk_in_tub_id')->nullable();
            $table->string('lead_desired_featuers_id')->nullable();
            $table->integer('lead_type_of_roofing_id')->nullable();


            $table->integer('lead_nature_of_roofing_id')->nullable();
            $table->integer('lead_property_type_roofing_id')->nullable();
            $table->integer('property_type_campaign_id')->nullable();

            $table->timestamps();

            $table->integer('type_of_siding_lead_id')->nullable();
            $table->integer('nature_of_siding_lead_id')->nullable();
            $table->integer('service_kitchen_lead_id')->nullable();
            $table->integer('campaign_kitchen_r_a_walls_status')->nullable();

            $table->integer('campaign_bathroomtype_id')->nullable();
            $table->integer('stairs_type_lead_id')->nullable();
            $table->integer('stairs_reason_lead_id')->nullable();
            $table->integer('furnance_type_lead_id')->nullable();

            $table->integer('plumbing_service_list_id')->nullable();
            $table->integer('sunroom_service_lead_id')->nullable();
            $table->integer('handyman_service_lead_id')->nullable();
            $table->integer('handyman_ammount_work_id')->nullable();

            $table->integer('countertops_service_lead_id')->nullable();
            $table->integer('door_typeproject_lead_id')->nullable();
            $table->integer('number_of_door_lead_id')->nullable();
            $table->integer('gutters_install_type_leade_id')->nullable();

            $table->integer('gutters_meterial_lead_id')->nullable();
            $table->integer('paving_service_lead_id')->nullable();
            $table->integer('paving_asphalt_type_id')->nullable();
            $table->integer('paving_loose_fill_type_id')->nullable();

            $table->integer('paving_best_describes_priject_id')->nullable();
            $table->integer('painting_service_lead_id')->nullable();
            $table->integer('painting1_typeof_project_id')->nullable();
            $table->integer('painting1_stories_number_id')->nullable();

            $table->integer('painting1_kindsof_surfaces_id')->nullable();
            $table->integer('painting2_rooms_number_id')->nullable();
            $table->integer('painting2_typeof_paint_id')->nullable();
            $table->string('painting3_each_feature_id')->nullable();


            $table->string('painting4_existing_roof_id')->nullable();
            $table->string('painting5_kindof_texturing_id')->nullable();
            $table->integer('painting5_surfaces_textured_id')->nullable();
            $table->integer('historical_structure')->nullable();

            $table->string('traffic_source')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('appointment_date')->nullable();
            $table->string('trusted_form')->nullable();

            $table->string('universal_leadid')->nullable();
            $table->string('is_duplicate_lead')->nullable();
            $table->integer('status')->nullable();
            $table->string('lead_source_text')->nullable();

            $table->string('lead_details_text')->nullable();

            $table->string('google_ts')->nullable();
            $table->string('google_c')->nullable();
            $table->string('google_g')->nullable();
            $table->string('google_k')->nullable();

            $table->string('vendor_id')->nullable();
            $table->integer('lead_ping_id')->nullable();
            $table->integer('is_test')->nullable();

            $table->string('token')->nullable();
            $table->string('visitor_id')->nullable();
            $table->integer('is_verified_phone')->nullable();
            $table->integer('is_seen_lead')->nullable();

            $table->integer('is_completed')->nullable();
            $table->string('visitor_leads_id')->nullable();
            $table->text('zip_codes_review')->nullable();
            $table->text('cities_review')->nullable();

            $table->text('pushnami_s1')->nullable();
            $table->text('pushnami_s2')->nullable();
            $table->text('pushnami_s3')->nullable();
            $table->text('google_gclid')->nullable();

            $table->string('house_type', 50)->nullable();
            $table->string('Year_Built', 50)->nullable();
            $table->string('primary_residence', 50)->nullable();
            $table->string('new_purchase', 50)->nullable();
            $table->string('previous_insurance_within_last30', 50)->nullable();
            $table->string('previous_insurance_claims_last3yrs', 50)->nullable();
            $table->string('credit_rating', 50)->nullable();
            $table->string('Height', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->string('amount_coverage', 50)->nullable();
            $table->string('military_personnel_status', 50)->nullable();
            $table->string('military_status', 50)->nullable();
            $table->string('service_branch', 50)->nullable();
            $table->string('CommercialCoverage', 50)->nullable();
            $table->string('company_benefits_quote', 50)->nullable();
            $table->string('business_start_date', 50)->nullable();
            $table->string('estimated_annual_payroll', 50)->nullable();
            $table->string('number_of_employees', 50)->nullable();
            $table->string('coverage_start_month', 50)->nullable();
            $table->string('business_name', 50)->nullable();
            $table->string('pregnancy', 50)->nullable();
            $table->string('tobacco_usage', 50)->nullable();
            $table->string('health_conditions', 50)->nullable();
            $table->string('number_of_people_in_household', 50)->nullable();
            $table->string('addPeople', 50)->nullable();
            $table->string('annual_income', 50)->nullable();

            $table->string('submodel')->nullable();
            $table->string('coverage_type')->nullable();
            $table->string('license_status')->nullable();
            $table->string('license_state')->nullable();
            $table->date('ticket_date')->nullable();
            $table->date('violation_date')->nullable();
            $table->date('accident_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('VehicleYear')->nullable();
            $table->string('VehicleMake')->nullable();
            $table->string('car_model')->nullable();
            $table->string('more_than_one_vehicle')->nullable();
            $table->string('driversNum')->nullable();
            $table->date('birthday')->nullable();
            $table->string('genders')->nullable();
            $table->string('married')->nullable();
            $table->string('license')->nullable();
            $table->string('InsuranceCarrier')->nullable();
            $table->string('driver_experience')->nullable();
            $table->string('number_of_tickets')->nullable();
            $table->string('DUI_charges')->nullable();
            $table->string('SR_22_need')->nullable();
            $table->boolean('furnance_type_b')->nullable();
            $table->boolean('furnance_type_f')->nullable();
            $table->boolean('is_multi_service')->default(0);

            $table->text('zipcodes_changes')->nullable();
            $table->text('cities_changes')->nullable();
            $table->text('counties_changes')->nullable();
            $table->text('states_changes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_reviews');
    }
}
