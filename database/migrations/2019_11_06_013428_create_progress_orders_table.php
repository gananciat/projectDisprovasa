<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('progress_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('purchased_amount',10,2); //5
            $table->decimal('original_quantity',10,2); //10
            $table->boolean('check')->default(0);
            $table->unsignedBigInteger('order_statuses_id');
            $table->foreign('order_statuses_id')->references('id')->on('order_statuses')->onUpdate('cascade');
            $table->unsignedBigInteger('detail_orders_id');
            $table->foreign('detail_orders_id')->references('id')->on('detail_orders')->onUpdate('cascade');
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress_orders');
    }
}
