<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('bill')->unique();
            $table->longText('logo')->nullable();
            $table->string('direction');
            $table->string('nit');
            $table->string('code_primary')->nullable();
            $table->string('code_high_school')->nullable();
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('municipalities_id');
            $table->foreign('municipalities_id')->references('id')->on('municipalities');  
            $table->unsignedBigInteger('people_id'); //Usuario que agrega la escuela
            $table->foreign('people_id')->references('id')->on('people');                       
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
