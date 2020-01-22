<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('check_schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('check');
            $table->unsignedBigInteger('orders_id');
            $table->foreign('orders_id')->references('id')->on('orders');
            $table->unsignedBigInteger('detail_orders_id');
            $table->foreign('detail_orders_id')->references('id')->on('detail_orders');
            $table->unsignedBigInteger('check_delivery_man_id');
            $table->foreign('check_delivery_man_id')->references('id')->on('check_delivery_man');
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('check_schools');
    }
}
