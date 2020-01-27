<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentencesTable extends Migration
{
    public function up()
    {
        Schema::create('sentences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products')->onUpdate('cascade');              
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentences');
    }
}
