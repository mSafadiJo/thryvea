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
        Schema::create('crm_response_pings', function (Blueprint $table) {
            $table->id();
            $table->integer('ping_id')->nullable();
            $table->integer('lead_id')->nullable();
            $table->integer('campaigns_leads_users_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->longText('response');
            $table->longText('url');
            $table->longText('inputs')->nullable();
            $table->string('method', 50);
            $table->string('httpheader')->nullable();
            $table->float("time", 20)->default(0);
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
        Schema::dropIfExists('crm_response_pings');
    }
};
