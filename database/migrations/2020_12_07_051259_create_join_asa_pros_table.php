<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoinAsaProsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('join_asa_pros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('full_name')->nullable();
            $table->text('business_name')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email')->nullable();
            $table->text('note')->nullable();
            $table->text('google_ts')->nullable();
            $table->text('google_c')->nullable();
            $table->text('google_k')->nullable();
            $table->text('google_g')->nullable();
            $table->text('source')->nullable();
            $table->text('ip_address')->nullable();
            $table->text('token')->nullable();
            $table->text('visitor_id')->nullable();
            $table->text('services');
            $table->bigInteger('zip_code')->nullable();
            $table->text('city')->nullable();
            $table->text('resource')->default('Join As A Pro');
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
        Schema::dropIfExists('join_asa_pros');
    }
}
