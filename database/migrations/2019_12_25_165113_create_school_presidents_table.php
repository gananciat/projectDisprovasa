<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolPresidentsTable extends Migration
{
    public function up()
    {
        Schema::create('school_presidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');  
            $table->unsignedBigInteger('people_id'); //Usuario que agrega la escuela
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');                       
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_presidents');
    }
}
