<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('user_name');
            $table->bigInteger('user_role')->unsigned()->nullable();
            $table->foreign('user_role')->references('role_id')->on('User_Role')->onDelete('cascade');
            $table->string('section');
            $table->bigInteger('section_id');
            $table->string('section_name');
            $table->string('action');  // created / updated / deleted
            $table->string('ip_address');
            $table->longText('location');
            $table->longText('request_method');
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
        Schema::dropIfExists('access_logs');
    }
}
