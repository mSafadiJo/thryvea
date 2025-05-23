<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesforceCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesforce_crms', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('retURL')->nullable();
            $table->string('oid')->nullable();
            $table->bigInteger('campaign_id')->nullable();
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
        Schema::dropIfExists('salesforce_crms');
    }
}
