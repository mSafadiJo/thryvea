<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTimeDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_time_delivery', function (Blueprint $table) {
            $table->bigIncrements('campaign_time_delivery_id');

            $table->bigInteger('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('NO ACTION')->onUpdate('NO ACTION');

            $table->boolean('campaign_time_delivery_status')->default(1);

            $table->text('campaign_time_delivery_timezone')->nullable();

            $table->time('start_sun')->nullable();
            $table->time('end_sun')->nullable();
            $table->boolean('status_sun')->default(1);

            $table->time('start_mon')->nullable();
            $table->time('end_mon')->nullable();
            $table->boolean('status_mon')->default(1);

            $table->time('start_tus')->nullable();
            $table->time('end_tus')->nullable();
            $table->boolean('status_tus')->default(1);

            $table->time('start_wen')->nullable();
            $table->time('end_wen')->nullable();
            $table->boolean('status_wen')->default(1);

            $table->time('start_thr')->nullable();
            $table->time('end_thr')->nullable();
            $table->boolean('status_thr')->default(1);

            $table->time('start_fri')->nullable();
            $table->time('end_fri')->nullable();
            $table->boolean('status_fri')->default(1);

            $table->time('start_sat')->nullable();
            $table->time('end_sat')->nullable();
            $table->boolean('status_sat')->default(1);

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
        Schema::dropIfExists('campaign_time_delivery');
    }
}
