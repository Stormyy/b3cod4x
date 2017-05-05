<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddB3ServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('b3servers', function (Blueprint $table){
           $table->increments('id');
           $table->string('name');
           $table->string('rcon');
           $table->string('identifier');
           $table->string('host');
           $table->integer('port');
           $table->json('dbSettings');
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
        \Schema::dropIfExists('b3servers');
    }
}
