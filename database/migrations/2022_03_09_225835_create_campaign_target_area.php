<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTargetArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_target_area', function (Blueprint $table) {
            $table->id()->index('id');
            $table->integer('campaign_id')->index('campaign_id');
            $table->longText('stateFilter_id')->nullable();
            $table->longText('state_id')->nullable();
            $table->longText('county_id')->nullable();
            $table->longText('city_id')->nullable();
            $table->longText('zipcode_id')->nullable();
            $table->longText('zipcode_distance_id')->nullable();
            $table->longText('county_ex_id')->nullable();
            $table->longText('city_ex_id')->nullable();
            $table->longText('zipcode_ex_id')->nullable();
            $table->longText('stateFilter_name')->nullable();
            $table->longText('stateFilter_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_target_area');
    }
}
