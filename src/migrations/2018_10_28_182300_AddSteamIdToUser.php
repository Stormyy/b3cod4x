<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSteamIdToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table(Config::get('b3cod4x.usertable'), function (Blueprint $table){
            $table->string('steamid')->nullable();
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
            $table->dropColumn('steamid');
        });

    }
}
