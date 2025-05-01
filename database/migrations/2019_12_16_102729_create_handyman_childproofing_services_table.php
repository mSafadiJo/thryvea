<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandymanChildproofingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handyman_childproofing_services', function (Blueprint $table) {
            $table->bigIncrements('handyman_childproofing_services_id');
            $table->text('handyman_childproofing_services_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('handyman_childproofing_services');
    }
}
