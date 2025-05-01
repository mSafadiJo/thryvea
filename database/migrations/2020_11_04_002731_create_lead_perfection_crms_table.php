<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadPerfectionCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_perfection_crms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id');
            $table->text("lead_perfection_url");
            $table->text("lead_perfection_srs_id");
            $table->text("lead_perfection_pro_id")->nullable();
            $table->string("lead_perfection_pro_desc")->nullable();
            $table->string("lead_perfection_sender")->nullable();
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
        Schema::dropIfExists('lead_perfection_crms');
    }
}
