<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadCurrentUtilityProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_current_utility_provider', function (Blueprint $table) {
            $table->bigIncrements('lead_current_utility_provider_id');
            $table->string('lead_current_utility_provider_name');
            $table->bigInteger('state_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_current_utility_provider');
    }
}
