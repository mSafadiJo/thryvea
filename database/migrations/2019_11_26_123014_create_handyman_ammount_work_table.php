<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandymanAmmountWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handyman_ammount_work', function (Blueprint $table) {
            $table->bigIncrements('handyman_ammount_work_id');
            $table->text('handyman_ammount_work_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('handyman_ammount_work');
    }
}
