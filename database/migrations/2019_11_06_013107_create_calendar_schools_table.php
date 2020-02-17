<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('calendar_schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',50);
            $table->date('date');
            $table->boolean('business')->default(0);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade'); 
            $table->softDeletes();            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_schools');
    }
}
