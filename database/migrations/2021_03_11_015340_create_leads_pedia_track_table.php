<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsPediaTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_pedia_track', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lp_campaign_id');
            $table->string('lp_campaign_key');
            $table->string('ping_url')->nullable();
            $table->string('post_url');
            $table->bigInteger('campaign_id');
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
        Schema::dropIfExists('leads_pedia_track');
    }
}
