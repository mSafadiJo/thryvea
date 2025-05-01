<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePavingBestDescribesPrijectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paving_best_describes_priject', function (Blueprint $table) {
            $table->bigIncrements('paving_best_describes_priject_id');
            $table->text('paving_best_describes_priject_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paving_best_describes_priject');
    }
}
