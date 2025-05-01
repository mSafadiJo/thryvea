<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_questions', function (Blueprint $table) {

            $table->id()->index('id');

            $table->integer('campaign_id')->index('campaign_id');
            $table->string('home_owned')->nullable();
            $table->string('property_type')->nullable();
            $table->string('installing')->nullable();

            // solar
            $table->string('solar_bill')->nullable();
            $table->string('solar_power_solution')->nullable();
            $table->string('roof_shade')->nullable();
            $table->longText('utility_providers')->nullable();

            // windows
            $table->string('number_of_window')->nullable();

            // Security
            $table->string('security_installing')->nullable();
            $table->string('existing_monitoring_system')->nullable();

            //Flooring
            $table->string('flooring_type')->nullable();

            //Walk-in-tops
            $table->string('walk_in_tup_filter')->nullable();

            //Roofing
            $table->string('roof_type')->nullable();

            //Home Siding
            $table->string('type_of_siding')->nullable();

            //Kitchen
            $table->string('kitchen_service')->nullable();
            $table->string('kitchen_walls')->nullable();

            //Bathroom Service
            $table->string('bathroom_type')->nullable();

            //StairLifts Service
            $table->string('stairs_type')->nullable();
            $table->string('stairs_reason')->nullable();

            //Furnace
            $table->string('furnace_type')->nullable();

            //Boiler
            $table->string('type_of_heating')->nullable();

            //plumbing
            $table->longText('plumbing_service')->nullable();

            //SunRooms
            $table->string('sunroom_service')->nullable();

            //Handyman
            $table->string('handyman_amount_work')->nullable();

            //CounterTops
            $table->string('counter_tops_service')->nullable();

            //Doors
            $table->string('door_type')->nullable();
            $table->string('number_of_door')->nullable();

            //Gutter
            $table->string('gutters_material')->nullable();

            //Paving
            $table->string('paving_service')->nullable();
            $table->string('paving_asphalt')->nullable();
            $table->string('paving_loose_fill')->nullable();
            $table->string('paving_best_desc')->nullable();

            //Painting
            $table->string('painting_service')->nullable();
            $table->string('painting_project_type')->nullable();
            $table->string('painting_stories_number')->nullable();
            $table->string('painting_kinds_of_surfaces')->nullable();
            $table->string('painting_historical_structure')->nullable();
            $table->string('painting_rooms_number')->nullable();
            $table->string('painting_type_of_paint')->nullable();
            $table->longText('painting_each_feature')->nullable();
            $table->longText('painting_existing_roof')->nullable();
            $table->string('painting_asphalt')->nullable();
            $table->string('painting_surfaces_textured')->nullable();
            $table->string('painting_best_desc')->nullable();
            $table->string('painting_kind_of_texturing')->nullable();


            //Auto Insurance
            $table->string('auto_insurance_license')->nullable();
            $table->string('driver_experience')->nullable();

            $table->string('submodel')->nullable();
            $table->string('coverage_type')->nullable();
            $table->string('license_status')->nullable();
            $table->string('license_state')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns_questions');
    }
}
