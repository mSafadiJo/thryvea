<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id')->default(null)->nullable();
            $table->longText('main_body')->default(null)->nullable();
            $table->text('main_header')->default(null)->nullable();
            $table->longText('second_header')->default(null)->nullable();
            $table->text('second_body')->default(null)->nullable();
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
        Schema::dropIfExists('services_content');
    }
}
