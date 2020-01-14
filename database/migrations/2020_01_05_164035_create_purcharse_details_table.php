<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurcharseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purcharse_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purcharse_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('purcharse_price',11,2);
            $table->integer('quantity');
            $table->integer('decrease')->default(0);
            $table->timestamps();

            $table->foreign('purcharse_id')->references('id')->on('purcharses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purcharse_details');
    }
}
