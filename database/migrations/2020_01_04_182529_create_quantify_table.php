<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuantifyTable extends Migration
{
    public function up()
    {
        Schema::create('quantify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('sumary_schools',10,2); 
            $table->decimal('sumary_purchase',10,2)->default(0); 
            $table->decimal('subtraction',10,2)->default(0); 
            $table->string('year');
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quantify');
    }
}
