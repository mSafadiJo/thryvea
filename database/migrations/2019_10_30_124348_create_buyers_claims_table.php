<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyersClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers_claims', function (Blueprint $table) {
            $table->bigIncrements('buyers_claims_id');
            $table->bigInteger('buyer_id');
            $table->bigInteger('admin_id');
            $table->text('claim_type');
            $table->text('admin_name');
            $table->bigInteger('confirmed_by_id')->nullable();
            $table->text('confirmed_by_name')->nullable();
            $table->boolean('is_claim')->default(0);
            $table->timestamp('confirmed_at')->nullable();
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('buyers_claims');
    }
}
