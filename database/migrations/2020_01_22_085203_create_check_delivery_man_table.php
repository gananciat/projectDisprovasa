<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckDeliveryManTable extends Migration
{
    public function up()
    {
        Schema::create('check_delivery_man', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('check');
            $table->unsignedBigInteger('orders_id');
            $table->foreign('orders_id')->references('id')->on('orders');
            $table->unsignedBigInteger('detail_orders_id');
            $table->foreign('detail_orders_id')->references('id')->on('detail_orders');
            $table->unsignedBigInteger('progress_orders_id');
            $table->foreign('progress_orders_id')->references('id')->on('progress_orders');
            $table->unsignedBigInteger('delivery_mans_id');
            $table->foreign('delivery_mans_id')->references('id')->on('delivery_mans');
            $table->unsignedBigInteger('vehicles_id');
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('check_delivery_man');
    }
}
