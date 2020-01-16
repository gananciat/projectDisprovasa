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
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products');              
            $table->unsignedBigInteger('menu_suggestions_id');
            $table->foreign('menu_suggestions_id')->references('id')->on('menu_suggestions');                           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_suggestions');
    }
}
