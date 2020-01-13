<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisbursementTable extends Migration
{
    public function up()
    {
        Schema::create('disbursement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::insert('insert into disbursement (name) values (?)', ['PRIMER DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['SEGUNDO DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['TERCER DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['CUARTO DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['QUINTO DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['SEXTO DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['SEPTIMO DESEMBOLSO']);
        DB::insert('insert into disbursement (name) values (?)', ['OCTAVO DESEMBOLSO']);
    }

    public function down()
    {
        Schema::dropIfExists('disbursement');
    }
}
