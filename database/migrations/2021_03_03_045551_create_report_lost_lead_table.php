<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportLostLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_lost_lead', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Lead_id');
            $table->longText('step1')->nullable();   //target location
            $table->longText('step2')->nullable();   //Q And Service
            $table->longText('step3_1')->nullable(); //no multi
            $table->longText('step3_2')->nullable(); //Time Delivery
            $table->longText('step3_3')->nullable();  //out capacity
            $table->longText('step3_4')->nullable(); //reject ping
            $table->longText('step3_5')->nullable(); //campaign with Out check
            $table->longText('step3_6')->nullable(); //campaign with check
            $table->longText('step3_7')->nullable(); //
            $table->longText('step4_1')->nullable(); //No budget
            $table->longText('step4_2')->nullable(); //Lead Reject

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
        Schema::dropIfExists('report_lost_lead');
    }
}
