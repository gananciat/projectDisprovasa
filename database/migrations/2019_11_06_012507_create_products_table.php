<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->decimal('stock',10,2)->default(0);
            $table->decimal('stock_temporary',10,2)->default(0);
            $table->string('propierty');
            $table->boolean('camouflage')->default(0);
            $table->boolean('persevering')->default(0);
            $table->unsignedBigInteger('categories_id');
            $table->foreign('categories_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->unsignedBigInteger('presentations_id');
            $table->foreign('presentations_id')->references('id')->on('presentations')->onUpdate('cascade');                           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
