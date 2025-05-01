<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service__campaigns', function (Blueprint $table) {
            $table->bigIncrements('service_campaign_id');
            $table->string('service_campaign_name');
            $table->string('service_campaign_description')->nullable();
            $table->boolean('service_is_active')->default(1);
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
        Schema::dropIfExists('service__campaigns');
    }
}
