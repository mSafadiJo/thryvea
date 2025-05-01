<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaigns_leads_users_id')->nullable();
            $table->longText('response');
            $table->float("time", 20)->default(0);
            $table->longText('url');
            $table->longText('inputs')->nullable();
            $table->text('method');
            $table->integer('ping_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->string('httpheader')->nullable();
            $table->integer('lead_id')->nullable();
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
        Schema::dropIfExists('crm_responses');
    }
}
