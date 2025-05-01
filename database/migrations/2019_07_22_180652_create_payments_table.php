<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->string('payment_full_name');
            $table->string('payment_visa_number');
            $table->string('payment_visa_last4char');
            $table->string('payment_num_trx');
            $table->string('payment_total_ammount');
            $table->string('payment_visa_type');
            $table->string('payment_cvv');
            $table->string('payment_exp_month');
            $table->year('payment_exp_year');
            $table->string('payment_address');
            $table->string('payment_zip_code');
            $table->boolean('payment_primary')->nullable()->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('payment_visibility')->nullable()->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
