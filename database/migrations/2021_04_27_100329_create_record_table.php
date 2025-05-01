<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_bandWidth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('eventType')->nullable();
            $table->string('eventTime')->nullable();
            $table->string('accountId')->nullable();
            $table->string('applicationId')->nullable();
            $table->string('to')->nullable();
            $table->string('from')->nullable();
            $table->string('direction')->nullable();
            $table->string('callId')->nullable();
            $table->string('recordingId')->nullable();
            $table->string('channels')->nullable();

            $table->string('startTime')->nullable();
            $table->string('endTime')->nullable();
            $table->string('duration')->nullable();
            $table->string('fileFormat')->nullable();
            $table->string('callUrl')->nullable();
            $table->string('mediaUrl')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('record');
    }
}
