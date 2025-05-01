<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsPediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_pedia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('leads_pedia_url');
            $table->string('campine_key');
            $table->bigInteger('campaign_id');
            $table->string('leads_pedia_url_ping')->nullable();
            $table->string('IP_Campaign_ID');
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
        Schema::dropIfExists('leads_pedia');
    }
}
