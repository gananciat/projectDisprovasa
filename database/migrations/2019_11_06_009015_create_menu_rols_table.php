<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuRolsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_rols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rols_id');
            $table->foreign('rols_id')->references('id')->on('rols')->onUpdate('cascade');             
            $table->unsignedBigInteger('menus_id');
            $table->foreign('menus_id')->references('id')->on('menus')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_rols');
    }
}
