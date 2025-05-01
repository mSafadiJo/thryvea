<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuilderPrimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('builder_prime', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('post_url');
            $table->string('secret_key');
            $table->string('campaign_id');
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
        Schema::table('builder_prime', function (Blueprint $table) {
            //
        });
    }
}
