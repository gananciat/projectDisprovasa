<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('total',11,2);
            $table->integer('bill');
            $table->decimal('total_iva',11,2);
            $table->date('date');
            $table->boolean('cancel')->default(0);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->unsignedBigInteger('vat_id');
            $table->foreign('vat_id')->references('id')->on('vats')->onUpdate('cascade');
            $table->unsignedBigInteger('serie_id');
            $table->foreign('serie_id')->references('id')->on('series')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
