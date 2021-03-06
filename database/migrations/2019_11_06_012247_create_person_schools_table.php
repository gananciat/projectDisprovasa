<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('person_schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_person');
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');             
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('person_schools');
    }
}
