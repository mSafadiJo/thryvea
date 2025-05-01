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
        Schema::create('campaigns_leads_users_affs', function (Blueprint $table) {
            $table->bigIncrements('campaigns_leads_users_id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('lead_id')->unsigned();
            $table->string('campaigns_leads_users_note')->nullable();
            $table->string('campaigns_leads_users_type_bid');
            $table->string('lead_id_token_md');
            $table->float('campaigns_leads_users_bid');
            $table->date('date');
            $table->timestamps();
            $table->boolean('is_returned')->default(0);
            $table->text('transactionId')->nullable();
            $table->boolean('is_recorded')->default(0);
            $table->boolean('call_center')->default(0);
            $table->string('agent_name')->nullable();
            $table->longText('buyer_lead_note')->nullable();
            $table->integer('vendor_id_aff')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns_leads_users_affs');
    }
};
