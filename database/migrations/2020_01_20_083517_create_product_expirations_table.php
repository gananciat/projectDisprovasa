<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductExpirationsTable extends Migration
{
    public function up()
    {
        Schema::create('product_expirations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->decimal('quantity',10,2);
            $table->decimal('used',10,2)->default(0);
            $table->decimal('return',10,2)->default(0);
            $table->boolean('expiration')->default(0);
            $table->boolean('current')->default(0);
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_expirations');
    }
}
