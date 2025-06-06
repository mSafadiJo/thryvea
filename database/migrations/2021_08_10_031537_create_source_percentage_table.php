<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcePercentageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_percentage', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('source_id')->nullable();
            $table->string('percentage_value')->nullable();
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
        Schema::dropIfExists('source_percentage');
    }
}
