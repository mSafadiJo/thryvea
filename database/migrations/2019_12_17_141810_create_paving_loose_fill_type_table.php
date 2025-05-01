<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePavingLooseFillTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paving_loose_fill_type', function (Blueprint $table) {
            $table->bigIncrements('paving_loose_fill_type_id');
            $table->text('paving_loose_fill_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paving_loose_fill_type');
    }
}
