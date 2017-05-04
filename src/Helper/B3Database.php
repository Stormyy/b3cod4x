<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 17:04
 */

namespace Stormyy\B3\Helper;


use App\Server;
use Carbon\Carbon;
use Stormyy\B3\Models\Alias;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Models\IpAlias;
use Stormyy\B3\Models\Player;

class B3Database
{
    public function __construct(B3Server $server)
    {
        \Config::set('database.connections.b3', $server->dbSettings);
    }

    public function getCurrentClients(){
        return \DB::connection('b3')->select('SELECT * FROM current_clients');
    }

    public function getUser($guid)
    {
        return Player::where('guid', $guid)->first();
    }

    public function search($query)
    {
        $ids = [];
        $players = [];

        $otherPlayers = Player::whereNotIn ('id', $ids)->where('name', 'like', '%'.$query.'%')->orWhere('guid', 'like', '%'.$query.'%')->orWhere('ip', 'like', '%'.$query.'%')->orderBy('connections', 'desc')->orderBy('time_edit', 'desc')->orderBy('time_edit', 'desc')->limit(10-count($players))->get();
        foreach($otherPlayers as $player){
            $player->ip = PermissionHelper::ip($player->ip);
            $player->lastseen = Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString();
            if(!in_array($player, $players)){
                $players[] =$player;
                $ids[] = $player->id;
            }
        }



        if(count($players) < 10){
            $aliases = Alias::where('alias', 'like', '%'.$query.'%')->orderBy('num_used', 'desc')->limit(10-count($players))->with('player')->get();
            foreach($aliases as $alias){
                $player = $alias->player;
                $player->ip = PermissionHelper::ip($player->ip);
                $player->lastseen = Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString();
                if(!in_array($player, $players)){
                    $players[] = $player;
                    $ids[] = $player->id;
                }
            }
        }

        if(count($players) < 10){
            $ipaliases = IpAlias::where('ip', 'like', '%'.$query.'%')->orderBy('num_used', 'desc')->limit(10-count($players))->with('player')->get();
            foreach($ipaliases as $otherip){
                $player = $otherip->player;
                $player->ip = PermissionHelper::ip($player->ip);
                $player->lastseen = Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString();
                if(!in_array($player, $players)){
                    $players[] = $player;
                    $ids[] = $player->id;
                }
            }
        }

        return $players;


    }

}