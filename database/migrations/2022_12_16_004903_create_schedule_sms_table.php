<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_sms', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->longText("content")->nullable();
            $table->longText("phone_list")->nullable();
            $table->dateTime("schedule_date")->nullable();
            $table->dateTime("send_date")->nullable();
            $table->boolean("is_sent")->default(0);
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
        Schema::dropIfExists('schedule_sms');
    }
};
