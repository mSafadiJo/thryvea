<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePipeDriveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipe_drive', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_token');
            $table->string('persons_domain');
            $table->bigInteger('campaign_id');
            $table->boolean('persons')->default(1);
            $table->boolean('deals_leads')->default(0);
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
        Schema::dropIfExists('pipe_drive');
    }
}
