<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderTable extends Migration
{
    public function up()
    {
        Schema::create('provider', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nit')->unique();
            $table->string('name',200);
            $table->string('direction'); 
            $table->string('email')->nullable();
            $table->unsignedBigInteger('municipalities_id');
            $table->foreign('municipalities_id')->references('id')->on('municipalities');  
                  
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provider');
    }
}
