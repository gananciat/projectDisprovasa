<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->index()->nullable();
            $table->string('name');
            $table->string('secret', 100);
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });

        DB::insert('insert into oauth_clients (id,user_id,name,secret,redirect,personal_access_client,password_client,revoked,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?)', 
            [1,null,'Laravel Personal Access Client','yEtb2IYdC9Z3ro6pbITCrLmSCxSbDkO8ED8jVR0Z','http://localhost',1,0,0,Carbon::now(),Carbon::now()]);
        DB::insert('insert into oauth_clients (id,user_id,name,secret,redirect,personal_access_client,password_client,revoked,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?)', 
            [2,null,'Desarrollo Password Grant Client','t6LqVNQoiMx7aYceb44vBupKptrzXLF9MJ5OGdOD','http://localhost',0,1,0,Carbon::now(),Carbon::now()]);

        DB::insert('insert into oauth_clients (id,user_id,name,secret,redirect,personal_access_client,password_client,revoked,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?)', 
            [3,null,'Disprovasa Password Grant Client','UA3HB22ViZrERbMAbZNPtZFT3LDg9pEJbiUAJZ2J','http://www.empresa.disprovasa.org/',0,1,0,Carbon::now(),Carbon::now()]);
        DB::insert('insert into oauth_clients (id,user_id,name,secret,redirect,personal_access_client,password_client,revoked,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?)', 
            [4,null,'Proalsa Password Grant Client','kVVX2xqANGrE2d4hXrMjk2xaYWE9EkcRzZUZQCG6','http://www.proalsa.disprovasa.org/',0,1,0,Carbon::now(),Carbon::now()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
