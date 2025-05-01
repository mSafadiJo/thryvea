<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadTypeOfRoofingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_type_of_roofing', function (Blueprint $table) {
            $table->bigIncrements('lead_type_of_roofing_id');
            $table->string('lead_type_of_roofing_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_type_of_roofing');
    }
}
