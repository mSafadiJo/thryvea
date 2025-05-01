<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsLeadsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_leads_users', function (Blueprint $table) {
            $table->bigIncrements('campaigns_leads_users_id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->bigInteger('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->bigInteger('lead_id')->unsigned();
            $table->foreign('lead_id')->references('lead_id')->on('leads_customers')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->string('campaigns_leads_users_note')->nullable();
            $table->string('campaigns_leads_users_type_bid');
            $table->string('lead_id_token_md');
            $table->float('campaigns_leads_users_bid');
            $table->date('date');
            $table->boolean('is_returned')->default(0);
            $table->text('transactionId')->nullable();
            $table->boolean('is_recorded')->default(0);
            $table->boolean('call_center')->default(0);
            $table->string('agent_name')->nullable();
            $table->longText('buyer_lead_note')->nullable();
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
        Schema::dropIfExists('campaigns_leads_users');
    }
}
