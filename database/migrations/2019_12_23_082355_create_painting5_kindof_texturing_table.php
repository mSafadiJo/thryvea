<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainting5KindofTexturingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting5_kindof_texturing', function (Blueprint $table) {
            $table->bigIncrements('painting5_kindof_texturing_id');
            $table->text('painting5_kindof_texturing_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting5_kindof_texturing');
    }
}
