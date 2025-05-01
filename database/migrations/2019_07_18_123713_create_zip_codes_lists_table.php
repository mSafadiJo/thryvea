<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipCodesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zip_codes_lists', function (Blueprint $table) {
            $table->bigIncrements('zip_code_list_id');
            $table->string('zip_code_list');
            $table->string('zip_code_list_TimeZone');
            $table->bigInteger('state_id')->unsigned();
            $table->bigInteger('county_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('states')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('county_id')->references('county_id')->on('counties')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('city_id')->references('city_id')->on('cities')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zip_codes_lists');
    }
}
