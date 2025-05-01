<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ping_leads', function (Blueprint $table) {
            $table->bigIncrements('lead_id');
            $table->string('lead_address');

            $table->string('lead_numberOfItem')->nullable();
            $table->boolean('lead_ownership')->nullable();
            $table->bigInteger('lead_type_service_id')->unsigned();
            $table->bigInteger('lead_installing_id')->unsigned()->nullable();
            $table->bigInteger('lead_priority_id')->unsigned()->nullable();
            $table->bigInteger('lead_state_id')->unsigned();
            $table->bigInteger('lead_county_id')->unsigned()->nullable();
            $table->bigInteger('lead_city_id')->unsigned();
            $table->bigInteger('lead_zipcode_id')->unsigned();

            $table->string('lead_serverDomain')->nullable();
            $table->string('lead_timeInBrowseData')->nullable();
            $table->string('lead_ipaddress')->nullable();
            $table->longText('lead_FullUrl')->nullable();
            $table->longText('lead_aboutUserBrowser')->nullable();
            $table->string('lead_browser_name')->nullable();
            $table->string('lead_website')->nullable();

            $table->bigInteger('lead_solor_solution_list_id')->unsigned()->nullable();
            $table->bigInteger('lead_solor_sun_expouser_list_id')->unsigned()->nullable();
            $table->string('lead_current_utility_provider_id')->nullable();
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

            $table->bigInteger('paving_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('paving_asphalt_type_id')->unsigned()->nullable();
            $table->bigInteger('paving_loose_fill_type_id')->unsigned()->nullable();
            $table->bigInteger('paving_best_describes_priject_id')->unsigned()->nullable();

            $table->bigInteger('painting_service_lead_id')->unsigned()->nullable();
            $table->bigInteger('painting1_typeof_project_id')->unsigned()->nullable();
            $table->bigInteger('painting1_stories_number_id')->unsigned()->nullable();
            $table->bigInteger('painting1_kindsof_surfaces_id')->unsigned()->nullable();
            $table->bigInteger('painting2_rooms_number_id')->unsigned()->nullable();
            $table->bigInteger('painting2_typeof_paint_id')->unsigned()->nullable();
            $table->bigInteger('painting3_each_feature_id')->nullable();
            $table->bigInteger('painting4_existing_roof_id')->nullable();
            $table->bigInteger('painting5_kindof_texturing_id')->nullable();
            $table->bigInteger('painting5_surfaces_textured_id')->unsigned()->nullable();
            $table->bigInteger('historical_structure')->unsigned()->nullable();

            $table->text('universal_leadid')->nullable();
            $table->text('trusted_form')->nullable();
            $table->text('traffic_source')->nullable();
            $table->boolean('lead_source')->default(1);
            $table->dateTime('appointment_date')->nullable();
            $table->boolean('is_duplicate_lead')->default(0);
            $table->text('status')->nullable();
            $table->text('lead_details_text')->nullable();

            $table->text('price')->nullable();
            $table->text('original_price')->nullable();
            $table->text('ping_post_data_arr')->nullable();
            $table->text('campaign_id_arr')->nullable();
            $table->text('transaction_id')->nullable();
            $table->text('lead_source_text')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->text('lead_bid_type')->nullable();

            $table->boolean('is_test')->default(0);
            $table->longText('hash_legs_sold')->nullable();
            $table->boolean('tcpa_compliant')->default(0);
            $table->longText('tcpa_consent_text')->nullable();
            $table->string('VehicleYear', 50)->nullable();
            $table->string('VehicleMake', 50)->nullable();
            $table->string('car_model', 50)->nullable();
            $table->string('more_than_one_vehicle', 50)->nullable();
            $table->string('driversNum', 50)->nullable();
            $table->date('birthday')->nullable();
            $table->string('genders', 50)->nullable();
            $table->string('married', 50)->nullable();
            $table->string('license', 50)->nullable();
            $table->string('InsuranceCarrier', 50)->nullable();
            $table->string('driver_experience', 50)->nullable();
            $table->string('number_of_tickets', 50)->nullable();
            $table->string('DUI_charges', 50)->nullable();
            $table->string('SR_22_need', 50)->nullable();
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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ping_leads');
    }
}
