<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EncryptB3Data extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('b3servers', function (Blueprint $table) {
            $table->string('rcon', 250)->change();
        });

        $b3servers = \Stormyy\B3\Models\B3Server::get();
        foreach ($b3servers as $b3server) {
            $b3server->dbSettings = Crypt::encrypt($b3server->dbSettings);
            $b3server->rcon = Crypt::encrypt($b3server->rcon);
            $b3server->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
