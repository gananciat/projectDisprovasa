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
            $table->decimal('balance',12,2); //El monto asignado por el MINEDUC
            $table->decimal('subtraction',12,2)->default(0); //El monto total despues de la resta al realizar la facturación
            $table->decimal('subtraction_temporary',12,2)->default(0); //El monto total despues de la resta al realizar un pedido
            $table->date('start_date'); //Inicio de la fecha del desembolso
            $table->date('end_date'); //Fin de la fecha del desembolso
            $table->string('code',100); //Código de Primaria o Preprimaria 
            $table->string('type_balance',20); //El monto puede ser asignado a ALIMENTACIÓN, GRATUIDAD o UTILES
            $table->smallInteger('year'); //Año en que fue asignado el monto
            $table->boolean('current')->default(1); //Llevar control si el monto se encuentra activo
            $table->unsignedBigInteger('schools_id'); //ID de la escuela
            $table->foreign('schools_id')->references('id')->on('schools')->onUpdate('cascade');
            $table->unsignedBigInteger('people_id'); //ID de la persona que registra la información
            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade');    
            $table->unsignedBigInteger('disbursement_id'); //ID del titulo del desembolso
            $table->foreign('disbursement_id')->references('id')->on('disbursement')->onUpdate('cascade');             
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
