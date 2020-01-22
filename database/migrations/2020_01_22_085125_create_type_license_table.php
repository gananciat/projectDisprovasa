<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeLicenseTable extends Migration
{
    public function up()
    {
        Schema::create('type_license', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type',1)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_license');
    }
}
