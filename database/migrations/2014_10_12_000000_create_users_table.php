php<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->string('username');
            $table->string('email', 50)->unique();
            $table->string('user_owner');
            $table->bigInteger('zip_code_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->integer('num_of_login');
            $table->string('password');
            $table->string('user_business_name');
            $table->string('user_phone_number');
            $table->string('user_mobile_number');
            $table->string('user_promocode')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('user_visibility')->default(1);
            $table->bigInteger('payment_type_method_id')->unsigned()->nullable();
            $table->string('payment_type_method_limit')->nullable();
            $table->boolean('payment_type_method_status')->default(0);
            $table->text('account_type')->nullable();
            $table->longText('permission_users')->nullable();
            $table->integer('user_type')->default(2);
            $table->text('contracts')->nullable();
            $table->text('documents')->nullable();
            $table->string('hash_phone_number')->nullable();
            $table->string('hash_mobile_number')->nullable();
            $table->bigInteger('sales_id')->nullable();
            $table->bigInteger('sdr_id')->nullable();
            $table->bigInteger('acc_manger_id')->nullable();
            $table->string('adminIcon1')->nullable();
            $table->string('adminIcon2')->nullable();
            $table->string('adminIcon3')->nullable();
            $table->string('profit_percentage', 50)->nullable();
            $table->tinyInteger('user_auto_pay_status')->nullable();
            $table->string('user_auto_pay_amount')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
