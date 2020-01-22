<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensePlatesTable extends Migration
{
    public function up()
    {
        Schema::create('license_plates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50)->unique();
            $table->string('type',1)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('license_plates');
    }
}
