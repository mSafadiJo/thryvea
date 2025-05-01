<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignBathroomtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_campaign_bathroomtype', function (Blueprint $table) {
            $table->bigIncrements('campaign_bathroomtype_id');
            $table->text('campaign_bathroomtype_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_campaign_bathroomtype');
    }
}
