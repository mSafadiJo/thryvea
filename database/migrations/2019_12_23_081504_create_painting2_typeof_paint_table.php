<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainting2TypeofPaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting2_typeof_paint', function (Blueprint $table) {
            $table->bigIncrements('painting2_typeof_paint_id');
            $table->text('painting2_typeof_paint_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting2_typeof_paint');
    }
}
