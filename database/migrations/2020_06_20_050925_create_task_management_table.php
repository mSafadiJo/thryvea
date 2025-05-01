<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('task_name');
            $table->string('description');
            $table->string('status')->nullable();
            $table->string('priority');
            $table->string('assign_from');
            $table->string('signed_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_management');
    }
}
