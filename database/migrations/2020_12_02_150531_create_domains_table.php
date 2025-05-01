<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain_name');
//            $table->integer('service_id');
            $table->integer('Service_type');
            $table->integer('theme_id');
            $table->string('status');
            $table->tinyInteger('session_recording')->default(0);
            $table->tinyInteger('exit_popup')->default(0);
            $table->enum('session_recoding_option', ['all traffic source', 'according traffic source', NULL])->nullable();
            $table->longText('traffic_source_selected')->nullable()->default(null);
            $table->tinyInteger('lead_review')->default(0);
            $table->tinyInteger('join_network')->default(0);
            $table->longText('second_service')->default(null)->nullable();
            $table->text('logo')->default(null)->nullable();
            $table->text('background')->default(null)->nullable();
            $table->text('icon')->default(null)->nullable();
            $table->longText('meta_description')->default(null)->nullable();
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
        Schema::dropIfExists('domains');
    }
}
