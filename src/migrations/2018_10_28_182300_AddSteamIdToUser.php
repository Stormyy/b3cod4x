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
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
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
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });

    }
}
