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
        Schema::create('speed_test', function (Blueprint $table) {
            $table->id();
            $table->integer('ping_id');
            $table->integer('services_id');
            $table->string('Time1',20)->nullable();
            $table->string('Time2',20)->nullable();
            $table->string('Time3',20)->nullable();
            $table->string('Time4',20)->nullable();
            $table->string('Time5',20)->nullable();
            $table->string('Time6',20)->nullable();
            $table->string('Time7',20)->nullable();
            $table->string('Time8',20)->nullable();
            $table->string('Time9',20)->nullable();
            $table->string('Time10',20)->nullable();
            $table->string('Time11',20)->nullable();
            $table->string('Time12',20)->nullable();
            $table->string('Time13',20)->nullable();
            $table->string('Time14',20)->nullable();
            $table->string('Time15',20)->nullable();
            $table->string('Time16',20)->nullable();
            $table->string('Time17',20)->nullable();
            $table->string('Time18',20)->nullable();
            $table->string('Time19',20)->nullable();
            $table->string('Time20',20)->nullable();
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
        Schema::dropIfExists('speed_test');
    }
};
