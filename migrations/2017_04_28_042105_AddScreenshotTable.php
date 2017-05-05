<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScreenshotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('screenshots', function (Blueprint $table){
           $table->increments('id');
           $table->string('filename');
           $table->string('guid');
           $table->string('name');
           $table->unsignedInteger('server_id');
           $table->timestamps();

            /*$table->foreign('server_id')
                ->references('id')->on('servers')
                ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('screenshots');
    }
}
