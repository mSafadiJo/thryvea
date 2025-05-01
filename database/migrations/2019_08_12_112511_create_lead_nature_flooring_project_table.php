<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadNatureFlooringProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_nature_flooring_project', function (Blueprint $table) {
            $table->bigIncrements('lead_nature_flooring_project_id');
            $table->string('lead_nature_flooring_project_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_nature_flooring_project');
    }
}
