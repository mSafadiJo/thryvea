<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainting4ExistingRoofTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting4_existing_roof', function (Blueprint $table) {
            $table->bigIncrements('painting4_existing_roof_id');
            $table->text('painting4_existing_roof_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting4_existing_roof');
    }
}
