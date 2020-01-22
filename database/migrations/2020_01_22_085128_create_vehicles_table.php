<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('placa')->unique();
            $table->string('color');
            $table->smallInteger('anio');
            $table->unsignedBigInteger('license_plates_id');
            $table->foreign('license_plates_id')->references('id')->on('license_plates');
            $table->unsignedBigInteger('vehicle_models_id');
            $table->foreign('vehicle_models_id')->references('id')->on('vehicle_models');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
