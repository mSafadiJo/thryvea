<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\CampaignType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CampaignType::create([
            'campaign_types_name' => 'Pay Per Aged Lead',
            'campaign_types_logo' => 'fa-calendar',
            'campaign_types_description' => 'For selling aged leads',
            'buyers_status' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
