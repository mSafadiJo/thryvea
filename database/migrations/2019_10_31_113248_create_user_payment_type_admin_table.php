<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentTypeAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_type_admin', function (Blueprint $table) {
            $table->bigIncrements('user_payment_type_admin_id');
            $table->bigInteger('buyer_id')->unsigned();
            $table->bigInteger('admin_id')->unsigned();
            $table->text('admin_name');
            $table->bigInteger('confirmed_by_id')->unsigned()->nullable();
            $table->text('confirmed_by_name')->nullable();
            $table->bigInteger('payment_type_method_id')->unsigned()->nullable();
            $table->string('payment_type_method_limit')->nullable();
            $table->boolean('payment_type_method_status')->default(0);
            $table->timestamp('confirmed_at')->nullable();
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
        Schema::dropIfExists('user_payment_type_admin');
    }
}
