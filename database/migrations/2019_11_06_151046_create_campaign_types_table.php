<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_types', function (Blueprint $table) {
            $table->bigIncrements('campaign_types_id');
            $table->string('campaign_types_name');
            $table->string('campaign_types_logo');
            $table->string('campaign_types_description')->nullable();
            $table->boolean('buyers_status')->default(1);
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
        Schema::dropIfExists('campaign_types');
    }
}
