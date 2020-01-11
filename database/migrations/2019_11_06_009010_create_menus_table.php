<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',150);
            $table->string('route',150);
            $table->string('route_name',100);
            $table->unsignedBigInteger('father');
            $table->boolean('hide')->default(0);
            $table->string('icon',50);           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
