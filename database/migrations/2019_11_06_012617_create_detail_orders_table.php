<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('quantity',10,2);
            $table->decimal('sale_price',12,2);
            $table->decimal('subtotal',12,2);
            $table->string('observation',500);
            $table->boolean('complete')->default(0);
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products');              
            $table->unsignedBigInteger('orders_id');
            $table->foreign('orders_id')->references('id')->on('orders');  
            $table->softDeletes();                           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_orders');
    }
}
