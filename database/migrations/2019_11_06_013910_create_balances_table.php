<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('balance',12,2);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('positive_balance')->default(1);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people');    
            $table->unsignedBigInteger('years_id');
            $table->foreign('years_id')->references('id')->on('years');              
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
