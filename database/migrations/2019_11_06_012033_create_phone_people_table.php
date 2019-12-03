<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('phone_people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies');              
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people');              
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phone_people');
    }
}
