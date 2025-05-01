<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatrPainting1TypeofProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painting1_typeof_project', function (Blueprint $table) {
            $table->bigIncrements('painting1_typeof_project_id');
            $table->text('painting1_typeof_project_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painting1_typeof_project');
    }
}
