<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('campaign_id');
            $table->string('campaign_name');
            $table->integer('campaign_Type');
            $table->integer('campaign_count_lead')->default(0)->nullable();
            $table->integer('campaign_budget')->default(0)->nullable();
            $table->integer('campaign_count_lead_exclusive')->default(0)->nullable();
            $table->integer('campaign_budget_exclusive')->default(0)->nullable();

            $table->integer('campaign_budget_bid_exclusive')->default(0)->nullable();
            $table->integer('campaign_budget_bid_shared')->default(0)->nullable();

            $table->bigInteger('period_campaign_count_lead_id')->unsigned()->nullable();
            $table->bigInteger('period_campaign_budget_id')->unsigned()->nullable();
            $table->bigInteger('period_campaign_count_lead_id_exclusive')->unsigned()->nullable();
            $table->bigInteger('period_campaign_budget_id_exclusive')->unsigned()->nullable();
            $table->bigInteger('service_campaign_id')->unsigned()->nullable();
            $table->bigInteger('campaign_status_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('campaign_distance_area')->nullable()->nullable();
            $table->string('campaign_distance_area_expect')->nullable()->nullable();
            $table->boolean('campaign_visibility')->default(1);

            $table->boolean('auto_pay_status')->default(0)->nullable();
            $table->string('auto_pay_amount')->nullable();

            $table->string('file_calltools_id')->nullable();

            $table->string('crm')->nullable();
            $table->text('subject_email')->nullable();
            $table->text('lead_source')->nullable()->default(1);
            $table->boolean('is_seller')->default(0)->nullable();
            $table->integer('vendor_id')->unique();
            $table->text('typeOFLead_Source')->nullable();
            $table->boolean('multi_service_accept')->default(1);
            $table->boolean('sec_service_accept')->default(0);
            $table->boolean("if_static_cost")->default(0);
            $table->string('special_state')->nullable();
            $table->string('special_budget_bid_exclusive')->nullable();

            $table->boolean('is_multi_crms')->default(0);
            $table->tinyInteger('band_width_accept_record')->default(0);
            $table->boolean('is_utility_solar_filter')->nullable()->default(0);
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('email3')->nullable();
            $table->text('email4')->nullable();
            $table->text('email5')->nullable();
            $table->text('email6')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('phone4')->nullable();
            $table->string('phone5')->nullable();
            $table->string('phone6')->nullable();

            $table->boolean('is_ping_account')->default(0);
            $table->integer('virtual_price')->default(0);
            $table->boolean('is_branded_camp')->default(0);
            $table->string('website')->nullable();
            $table->string('transfer_numbers', 15)->nullable();
            $table->longText('click_url')->nullable();
            $table->string('click_text')->nullable();
            $table->longText('exclude_sources')->nullable();
            $table->longText('exclude_url')->nullable();
            $table->longText('special_source')->nullable();
            $table->integer('special_source_price')->nullable();
            $table->longText('percentage_value')->nullable();
            $table->text('campaign_calltools_id')->nullable();
            $table->string('exclude_include_type')->nullable();
            $table->longText('exclude_include_campaigns')->nullable();
            $table->text('script_text')->nullable();
            $table->timestamp('created_percentage_value');
            $table->timestamp('created_exclude_include')->useCurrent();

            $table->longText('delivery_Method_id')->nullable();
            $table->longText('custom_paid_campaign_id')->nullable();
            $table->timestamps();

            $table->foreign('service_campaign_id')->references('service_campaign_id')->on('service__campaigns')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('campaign_status_id')->references('campaign_status_id')->on('campaign_status')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('period_campaign_count_lead_id')->references('period_campaign_id')->on('period_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('period_campaign_budget_id')->references('period_campaign_id')->on('period_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('period_campaign_count_lead_id_exclusive')->references('period_campaign_id')->on('period_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('period_campaign_budget_id_exclusive')->references('period_campaign_id')->on('period_campaign')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
