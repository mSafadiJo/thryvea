<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 60)->nullable();
            $table->string('last_name', 60)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone_number', 20)->nullable();

            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('county_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('zipcode_id')->unsigned()->nullable();

            $table->string('lead_serverDomain')->nullable();
            $table->string('lead_timeInBrowseData')->nullable();
            $table->string('lead_ipaddress')->nullable();
            $table->longText('lead_FullUrl')->nullable();
            $table->longText('lead_aboutUserBrowser')->nullable();
            $table->string('lead_browser_name')->nullable();
            $table->string('lead_website')->nullable();

            $table->boolean('tcpa_compliant')->default(0);
            $table->string('tcpa_consent_text')->nullable();
            $table->string('trusted_form', 255)->nullable();
            $table->string('universal_leadid', 255)->nullable();

            $table->string('google_ts', 50)->nullable();
            $table->string('google_c', 50)->nullable();
            $table->string('google_g', 50)->nullable();
            $table->string('google_k', 50)->nullable();
            $table->string('token', 50)->nullable();
            $table->string('visitor_id', 50)->nullable();
            $table->string('google_gclid', 50)->nullable();

            $table->boolean('is_duplicate_lead')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('is_verified_phone')->default(0);

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
        Schema::dropIfExists('call_leads');
    }
}
