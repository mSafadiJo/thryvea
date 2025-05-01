<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadPropertyTypeRoofingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_property_type_roofing', function (Blueprint $table) {
            $table->bigIncrements('lead_property_type_roofing_id');
            $table->string('lead_property_type_roofing_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_property_type_roofing');
    }
}
