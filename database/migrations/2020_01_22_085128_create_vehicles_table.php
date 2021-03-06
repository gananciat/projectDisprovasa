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
            $table->string('vin')->unique();
            $table->string('chasis')->unique();
            $table->smallInteger('motor');
            $table->unsignedBigInteger('license_plates_id');
            $table->foreign('license_plates_id')->references('id')->on('license_plates')->onUpdate('cascade');
            $table->unsignedBigInteger('vehicle_models_id');
            $table->foreign('vehicle_models_id')->references('id')->on('vehicle_models')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
