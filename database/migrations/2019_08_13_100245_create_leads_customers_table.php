<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_customers', function (Blueprint $table) {
            $table->bigIncrements('lead_id');
            $table->string('lead_fname');
            $table->string('lead_lname');
            $table->string('lead_address');
            $table->string('lead_email');
            $table->string('lead_phone_number');
            $table->text('lead_source_text')->nullable();
            $table->text('lead_details_text')->nullable();
            $table->string('lead_numberOfItem')->nullable();

            $table->boolean('lead_ownership')->nullable();
            $table->bigInteger('lead_type_service_id')->unsigned();
            $table->bigInteger('lead_installing_id')->unsigned()->nullable();
            $table->bigInteger('lead_priority_id')->unsigned()->nullable();
            $table->bigInteger('lead_state_id')->unsigned();
            $table->bigInteger('lead_county_id')->unsigned()->nullable();
            $table->bigInteger('lead_city_id')->unsigned();
            $table->bigInteger('lead_zipcode_id')->unsigned();

            $table->string('lead_serverDomain');
            $table->string('lead_timeInBrowseData');
            $table->string('lead_ipaddress');
            $table->longText('lead_FullUrl');
            $table->longText('lead_aboutUserBrowser');
            $table->string('lead_browser_name');
            $table->string('lead_website');
            $table->text('universal_leadid')->nullable();
            $table->bigInteger('lead_solor_solution_list_id')->unsigned()->nullable();
            $table->bigInteger('lead_solor_sun_expouser_list_id')->unsigned()->nullable();
            //$table->string('lead_current_utility_provider_id')->nullable();
            $table->bigInteger('lead_avg_money_electicity_list_id')->unsigned()->nullable();

            $table->bigInteger('lead_installation_preferences_id')->unsigned()->nullable();
            $table->boolean('lead_have_item_before_it')->nullable();

            $table->bigInteger('lead_type_of_flooring_id')->unsigned()->nullable();
            $table->bigInteger('lead_nature_flooring_project_id')->unsigned()->nullable();

            $table->bigInteger('lead_walk_in_tub_id')->unsigned()->nullable();
            $table->string('lead_desired_featuers_id')->nullable();

            $table->bigInteger('lead_type_of_roofing_id')->unsigned()->nullable();
            $table->bigInteger('lead_nature_of_roofing_id')->unsigned()->nullable();
            $table->bigInteger('lead_property_type_roofing_id')->unsigned()->nullable();
            $table->bigInteger('property_type_campaign_id')->unsigned()->nullable();

            $table->text('traffic_source')->nullable();
            $table->boolean('lead_source')->default(1);
            $table->dateTime('appointment_date')->nullable();


            $table->boolean('is_duplicate_lead')->default(0);
            $table->boolean('status')->default(0);
            $table->string('google_ts')->nullable();
            $table->string('google_c')->nullable();
            $table->string('google_g')->nullable();
            $table->string('google_k')->nullable();
            $table->boolean('is_test')->default(0);
            $table->integer('converted')->nullable();
            $table->string('visitor_leads_id')->nullable();
            $table->boolean('is_sec_service')->default(0);
            $table->boolean('is_multi_service')->default(0);
            $table->string('response_data')->nullable();
            $table->string('hash_legs_sold')->nullable();
            $table->boolean('tcpa_compliant')->default(0);
            $table->string('tcpa_consent_text')->nullable();
            $table->string('band_width_order_id')->nullable();
            $table->string('band_width_new_number')->nullable();
            $table->boolean('band_width_connect')->nullable();
            $table->text('pushnami_s1')->nullable();
            $table->text('pushnami_s2')->nullable();
            $table->text('pushnami_s3')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('lead_ping_id')->nullable();

            $table->text('token')->nullable();
            $table->text('visitor_id')->nullable();

            $table->boolean('is_seen_lead')->default(0);
            $table->string('ping_price', 10)->nullable();
            $table->string('ping_original_price', 10)->nullable();
            $table->text('ping_response_data')->nullable();
            $table->text('ping_campaign_id_arr')->nullable();
            $table->string('ping_lead_bid_type', 50)->nullable();

            $table->boolean('is_verified_phone')->default(0);

            $table->text('google_gclid')->nullable();
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
            $table->string('QA_status','30')->nullable();
            $table->string('debt_amount', 255)->nullable();
            $table->longText('debt_type')->nullable();
            $table->string('submodel')->nullable();
            $table->string('coverage_type')->nullable();
            $table->string('license_status')->nullable();
            $table->string('license_state')->nullable();
            $table->date('ticket_date')->nullable();
            $table->date('violation_date')->nullable();
            $table->date('accident_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->date('expiration_date')->nullable();

            $table->bigInteger('type_of_siding_lead_id')->unsigned()->nullable();
            $table->bigInteger('nature_of_siding_lead_id')->unsigned()->nullable();
            $table->bigInteger('service_kitchen_lead_id')->unsigned()->nullable();
            $table->boolean('campaign_kitchen_r_a_walls_status')->unsigned()->nullable();
            $table->bigInteger('campaign_bathroomtype_id')->unsigned()->nullable();
            $table->bigInteger('stairs_type_lead_id')->unsigned()->nullable();
            $table->bigInteger('stairs_reason_lead_id')->unsigned()->nullable();
            $table->bigInteger('furnance_type_lead_id')->unsigned()->nullable();
            $table->bigInteger('plumbing_service_list_id')->unsigned()->nullable();
            $table->bigInteger('sunroom_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('handyman_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('handyman_ammount_work_id')->unsigned()->nullable();
            $table->bigInteger('countertops_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('door_typeproject_lead_id')->unsigned()->nullable();
            $table->bigInteger('number_of_door_lead_id')->unsigned()->nullable();
            $table->bigInteger('gutters_install_type_leade_id')->unsigned()->nullable();
            $table->bigInteger('gutters_meterial_lead_id')->unsigned()->nullable();

            $table->bigInteger('handyman_childproofing_services_id')->unsigned()->nullable();
            $table->bigInteger('handyman_range_of_age_id')->unsigned()->nullable();
            $table->bigInteger('handyman_doors_windows_many_id')->unsigned()->nullable();
            $table->bigInteger('handyman_builtinstripping')->unsigned()->nullable();

            $table->bigInteger('painting_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('painting1_typeof_project_id')->unsigned()->nullable();
            $table->bigInteger('painting1_stories_number_id')->unsigned()->nullable();
            $table->bigInteger('painting1_kindsof_surfaces_id')->unsigned()->nullable();
            $table->bigInteger('painting2_rooms_number_id')->unsigned()->nullable();
            $table->bigInteger('painting2_typeof_paint_id')->unsigned()->nullable();
            $table->text('painting3_each_feature_id')->nullable();
            $table->text('painting4_existing_roof_id')->nullable();
            $table->text('painting5_kindof_texturing_id')->nullable();
            $table->bigInteger('painting5_surfaces_textured_id')->unsigned()->nullable();
            $table->bigInteger('historical_structure')->unsigned()->nullable();


            $table->timestamps();

            $table->foreign('lead_type_service_id')->references('service_campaign_id')->on('service__campaigns')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_installing_id')->references('installing_type_campaign_id')->on('installing_type_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_priority_id')->references('lead_priority_id')->on('lead_priority')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_state_id')->references('state_id')->on('states')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_county_id')->references('county_id')->on('counties')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_city_id')->references('city_id')->on('cities')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_zipcode_id')->references('zip_code_list_id')->on('zip_codes_lists')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->foreign('lead_solor_solution_list_id')->references('lead_solor_solution_list_id')->on('lead_solor_solution_list')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_solor_sun_expouser_list_id')->references('lead_solor_sun_expouser_list_id')->on('lead_solor_sun_expouser_list')->onDelete('NO ACTION')->onUpdate('NO ACTION');
//            $table->foreign('lead_current_utility_provider_id')->references('lead_current_utility_provider_id')->on('lead_current_utility_provider')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_avg_money_electicity_list_id')->references('lead_avg_money_electicity_list_id')->on('lead_avg_money_electicity_list')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('property_type_campaign_id')->references('property_type_campaign_id')->on('property_type_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->foreign('lead_installation_preferences_id')->references('lead_installation_preferences_id')->on('lead_installation_preferences')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->foreign('lead_type_of_flooring_id')->references('lead_type_of_flooring_id')->on('lead_type_of_flooring')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_nature_flooring_project_id')->references('lead_nature_flooring_project_id')->on('lead_nature_flooring_project')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->foreign('lead_walk_in_tub_id')->references('lead_walk_in_tub_id')->on('lead_walk_in_tub')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->foreign('lead_type_of_roofing_id')->references('lead_type_of_roofing_id')->on('lead_type_of_roofing')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_nature_of_roofing_id')->references('lead_nature_of_roofing_id')->on('lead_nature_of_roofing')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('lead_property_type_roofing_id')->references('lead_property_type_roofing_id')->on('lead_property_type_roofing')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads_customers');
    }
}
