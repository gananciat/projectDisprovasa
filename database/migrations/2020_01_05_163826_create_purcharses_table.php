<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurcharsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purcharses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provider_id');
            $table->date('date');
            $table->string('no_prof',90);
            $table->decimal('total',11,2);
            $table->boolean('cancel')->default(0);
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purcharses');
    }
}
