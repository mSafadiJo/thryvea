<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadAvargeMoneyElecticityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_avg_money_electicity_list', function (Blueprint $table) {
            $table->bigIncrements('lead_avg_money_electicity_list_id');
            $table->string('lead_avg_money_electicity_list_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_avg_money_electicity_list');
    }
}
