<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddB3Claim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table(Config::get('b3cod4x.usertable'), function (Blueprint $table){
            $table->string('guid')->unique()->nullable();
            $table->string('claimCode')->unique()->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table(Config::get('b3cod4x.usertable'), function (Blueprint $table){
            $table->dropColumn('guid');
            $table->dropColumn('claimCode');
        });

    }
}
