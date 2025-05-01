<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandymanDoorsWindowsManyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handyman_doors_windows_many', function (Blueprint $table) {
            $table->bigIncrements('handyman_doors_windows_many_id');
            $table->text('handyman_doors_windows_many_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('handyman_doors_windows_many');
    }
}
