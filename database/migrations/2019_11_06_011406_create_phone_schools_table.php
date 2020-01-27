<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('phone_schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies')->onUpdate('cascade');               
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phone_schools');
    }
}
