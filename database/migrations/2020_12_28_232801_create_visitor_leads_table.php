<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_sessions')->unique();
            $table->string('lead_serverDomain');
            $table->string('lead_ipaddress');
            $table->longText('lead_FullUrl');
            $table->longText('lead_aboutUserBrowser');
            $table->string('lead_browser_name');
            $table->string('lead_website');
            $table->string('google_ts')->nullable();
            $table->string('google_c')->nullable();
            $table->string('google_g')->nullable();
            $table->string('google_k')->nullable();
            $table->string('token')->nullable();
            $table->string('visitor_id')->nullable();
            $table->boolean('is_lead')->default(0);
            $table->boolean('is_exit_popup')->default(0);
            $table->boolean('is_second_service')->default(0);
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
        Schema::dropIfExists('visitor_leads');
    }
}
