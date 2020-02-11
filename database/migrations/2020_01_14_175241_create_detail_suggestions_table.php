<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailSuggestionsTable extends Migration
{
    public function up()
    {
        Schema::create('detail_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('observation',500);
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products')->onUpdate('cascade');              
            $table->unsignedBigInteger('menu_suggestions_id');
            $table->foreign('menu_suggestions_id')->references('id')->on('menu_suggestions')->onUpdate('cascade');                           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_suggestions');
    }
}
