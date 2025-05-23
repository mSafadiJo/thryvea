<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJangleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jangle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Authorization')->nullable();
            $table->string('PingUrl')->nullable();
            $table->string('PostUrl')->nullable();
            $table->integer('campaign_id')->nullable();
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
        Schema::dropIfExists('jangle');
    }
}
