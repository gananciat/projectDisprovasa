<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order')->unique();
            $table->string('title');
            $table->string('description',1000)->nullable();
            $table->string('type_order');
            $table->date('date');
            $table->decimal('total',12,2)->default(0);
            $table->boolean('complete')->default(0);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people');   
            $table->unsignedBigInteger('months_id');
            $table->foreign('months_id')->references('id')->on('months');    
            $table->unsignedBigInteger('years_id');
            $table->foreign('years_id')->references('id')->on('years');                                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
