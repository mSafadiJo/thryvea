<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_form', function (Blueprint $table) {
            $table->id();

            $table->string('lead_fname')->nullable();
            $table->string('lead_lname')->nullable();
            $table->string('lead_email')->nullable();
            $table->string('lead_phone_number')->nullable();
            $table->string('lead_zipcode')->nullable();
            $table->string('offer')->nullable();
            $table->string('preferred_contact_method')->nullable();

            $table->string('api_version')->nullable();
            $table->string('form_id')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('is_test')->nullable();
            $table->string('gcl_id')->nullable();
            $table->string('adgroup_id')->nullable();
            $table->string('creative_id')->nullable();

            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('trusted_form')->nullable();
            $table->string('leadId')->nullable();
            $table->string('traffic_source')->nullable();

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
        Schema::dropIfExists('lead_form');
    }
}
