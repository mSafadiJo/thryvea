<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadSolorSunExpouserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_solor_sun_expouser_list', function (Blueprint $table) {
            $table->bigIncrements('lead_solor_sun_expouser_list_id');
            $table->string('lead_solor_sun_expouser_list_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_solor_sun_expouser_list');
    }
}
