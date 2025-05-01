<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestLeadsCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_leads_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lead_id');

            $table->text('lastCampainInArea')->nullable();

            $table->text('listOFCampain_exclusiveDB')->nullable();
            $table->text('listOFCampain_sharedDB')->nullable();
            $table->text('listOFCampain_pingDB')->nullable();

            $table->text('listOFCampainDB_array_exclusive')->nullable();
            $table->text('listOFCampainDB_array_shared')->nullable();
            $table->text('listOFCampainDB_array_ping')->nullable();

            $table->text('campaigns_sh')->nullable();
            $table->text('campaigns_ex')->nullable();

            $table->text('campaigns_sh_col')->nullable();
            $table->text('campaigns_ex_col')->nullable();

            $table->text('listOFCampainDB')->nullable();

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
        Schema::dropIfExists('test_leads_customers');
    }
}
