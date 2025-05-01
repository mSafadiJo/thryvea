<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            $table->bigInteger('user_id')->unsigned();
            $table->float('transactions_value');
            $table->string('transactions_comments')->nullable();
            $table->bigInteger('payment_id')->unsigned()->nullable();
            $table->boolean('transaction_status')->default(1);
            $table->boolean('transaction_paypall')->default(0);
            $table->boolean('transactionauthid')->default(0);
            $table->boolean('is_refund')->default(0);
            $table->boolean('accept')->nullable();
            $table->string('payment_type')->nullable();
            $table->boolean('is_seller')->default(0);
            $table->bigInteger('admin_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('payment_id')->references('payment_id')->on('payments')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
