<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderPhoneTable extends Migration
{
    public function up()
    {
        Schema::create('provider_phone', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('provider')->onUpdate('cascade');  
                  
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provider_phone');
    }
}
