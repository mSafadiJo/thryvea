<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolarProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('FirstName')->nullable();
            $table->text('LastName')->nullable();
            $table->text('Address')->nullable();
            $table->text('City')->nullable();
            $table->text('State')->nullable();
            $table->text('ZIPCode')->nullable();
            $table->text('EmailAddress')->nullable();
            $table->text('PhoneNumber')->nullable();
            $table->date('BirthDate')->nullable();
            $table->text('Gender')->nullable();
            $table->text('PropertyUsage')->nullable();
            $table->text('OwnRented')->nullable();
            $table->text('AuthorizedForPropertyChanges')->nullable();
            $table->text('ProjectStatus')->nullable();
            $table->text('SqFootage')->nullable();
            $table->text('PropertyStories')->nullable();
            $table->text('RoofShade')->nullable();
            $table->text('RoofType')->nullable();
            $table->text('ElectricityBill')->nullable();
            $table->text('agent_name')->nullable();
            $table->boolean('is_send')->default(0);
            $table->dateTime('request_date')->nullable();
            $table->text('TransactionId')->nullable();
            $table->text('sold_lead')->nullable();
            $table->text('TotalCount')->nullable();
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
        Schema::dropIfExists('solar_projects');
    }
}
