<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHatchCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hatch_crms', function (Blueprint $table) {
            $table->id();
            $table->string('dep_id')->nullable();
            $table->string('org_token')->nullable();
            $table->bigInteger('campaign_id');
            $table->string('URL_sub')->nullable();
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
        Schema::dropIfExists('hatch_crms');
    }
}
