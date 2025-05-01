<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('ticket_id');
            $table->boolean('ticket_type');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned()->nullable();
            $table->text('campaigns_leads_users_type_bid')->nullable();
            $table->bigInteger('campaigns_leads_users_id')->unsigned()->nullable();
            $table->bigInteger('reason_lead_returned_id')->unsigned()->nullable();
            $table->longText('ticket_message')->nullable();
            $table->longText('reject_text')->nullable();
            $table->text('ticket_status');
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
        Schema::dropIfExists('tickets');
    }
}
