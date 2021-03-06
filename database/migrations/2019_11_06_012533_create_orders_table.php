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
            $table->string('code',30);
            $table->string('description',1000)->nullable();
            $table->string('type_order');
            $table->date('date');
            $table->decimal('total',12,2)->default(0);
            $table->decimal('refund',12,2)->default(0);
            $table->boolean('complete')->default(0);
            $table->boolean('on_route')->default(0);
            $table->boolean('aware')->default(0);
            $table->boolean('invoiced')->default(0);
            $table->smallInteger('repeat')->default(0);
            $table->smallInteger('balances_id')->default(0);
            $table->unsignedBigInteger('schools_id');
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');  
            $table->unsignedBigInteger('months_id');
            $table->foreign('months_id')->references('id')->on('months')->onUpdate('cascade');    
            $table->unsignedBigInteger('years_id');
            $table->foreign('years_id')->references('id')->on('years')->onUpdate('cascade');                                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
