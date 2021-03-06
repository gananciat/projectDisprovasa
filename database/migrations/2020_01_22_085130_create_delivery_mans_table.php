<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryMansTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_mans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('current')->default(1);
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');
            $table->unsignedBigInteger('type_license_id');
            $table->foreign('type_license_id')->references('id')->on('type_license')->onUpdate('cascade');
            $table->unsignedBigInteger('vehicles_id');
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_mans');
    }
}
