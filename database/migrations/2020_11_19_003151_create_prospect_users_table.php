<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospect_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->string('username');
            $table->string('email', 50)->nullable();
            $table->string('user_owner');
            $table->string('user_business_name');
            $table->string('user_phone_number')->nullable();
            $table->string('user_mobile_number')->nullable();
            $table->bigInteger('zip_code_id')->nullable();
            $table->boolean('user_visibility')->default(1);
            $table->integer('user_type')->default(3);
            $table->string('sdr_claimer')->nullable();
            $table->string('sales_claimer')->nullable();
            $table->string('service')->nullable();
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
        Schema::dropIfExists('prospect_users');
    }
}
