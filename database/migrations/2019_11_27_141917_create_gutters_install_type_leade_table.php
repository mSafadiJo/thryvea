<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuttersInstallTypeLeadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutters_install_type_leade', function (Blueprint $table) {
            $table->bigIncrements('gutters_install_type_leade_id');
            $table->text('gutters_install_type_leade_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gutters_install_type_leade');
    }
}
