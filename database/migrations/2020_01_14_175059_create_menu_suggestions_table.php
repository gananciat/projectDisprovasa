<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSuggestionsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description',1000)->nullable();
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');                                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_suggestions');
    }
}
