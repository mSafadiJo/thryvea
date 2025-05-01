<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainting1KindsofSurfacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting1_kindsof_surfaces', function (Blueprint $table) {
            $table->bigIncrements('painting1_kindsof_surfaces_id');
            $table->text('painting1_kindsof_surfaces_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting1_kindsof_surfaces');
    }
}
