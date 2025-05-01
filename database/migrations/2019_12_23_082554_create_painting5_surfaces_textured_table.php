<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainting5SurfacesTexturedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting5_surfaces_textured', function (Blueprint $table) {
            $table->bigIncrements('painting5_surfaces_textured_id');
            $table->text('painting5_surfaces_textured_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting5_surfaces_textured');
    }
}
