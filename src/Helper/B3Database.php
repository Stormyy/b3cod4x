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
use Stormyy\B3\Models\Penalty;
use Stormyy\B3\Models\Player;

class B3Database
{
    private $b3server;

    public function __construct(B3Server $server)
    {
        $this->b3server = $server;
        \Config::set('database.connections.b3', json_decode(\Crypt::decrypt(($server->dbSettings)), true));
    }

    public function getActiveCurrentClients(B3Server $b3server)
    {
        \Config::set('database.connections.b3-' . $b3server->id, json_decode(\Crypt::decrypt(($b3server->dbSettings)), true));
        return \DB::connection('b3-' . $b3server->id)->table('current_clients')->count();
    }

    public function getMaxSlots(B3Server $b3server)
    {
        \Config::set('database.connections.b3-' . $b3server->id, json_decode(\Crypt::decrypt(($b3server->dbSettings)), true));
        return \DB::connection('b3-' . $b3server->id)->table('current_svars')->where('name', 'sv_maxclients')->value('value');
    }


    public function getCurrentClients(){
        return \DB::connection('b3')->select('SELECT * FROM current_clients');
    }

    public function getUser($guid)
    {
        return Player::where('guid', $guid)->firstOrFail();
    }

    private function parsePlayer($player){
        $player->ip = PermissionHelper::ipToFlag($player->ip)." ".PermissionHelper::ip($player->ip);
        $player->lastseen = Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString();
        return $player;
    }

    public function search($query)
    {
        $ids = [];
        $players = [];

        $otherPlayers = Player::whereNotIn ('id', $ids)->where('name', 'like', '%'.$query.'%')->orWhere('guid', 'like', '%'.$query.'%')->orWhere('ip', 'like', '%'.$query.'%')->orderBy('connections', 'desc')->orderBy('time_edit', 'desc')->orderBy('time_edit', 'desc')->limit(10-count($players))->get();
        foreach($otherPlayers as $player){
            $player = $this->parsePlayer($player);
            if(!isset($players[$player->id])){
                $players[$player->id] =$player;
                $ids[] = $player->id;
            }
        }



        if(count($players) < 10){
            $aliases = Alias::where('alias', 'like', '%'.$query.'%')->orderBy('num_used', 'desc')->limit(10-count($players))->with('player')->get();
            foreach($aliases as $alias){
                $player = $this->parsePlayer($alias->player);
                if(!isset($players[$player->id])){
                    $players[$player->id] = $player;
                    $ids[] = $player->id;
                }
            }
        }

        if(count($players) < 10){
            $ipaliases = IpAlias::where('ip', 'like', '%'.$query.'%')->orderBy('num_used', 'desc')->limit(10-count($players))->with('player')->get();
            foreach($ipaliases as $otherip){
                $player = $this->parsePlayer($otherip->player);
                if(!isset($players[$player->id])){
                    $players[$player->id] = $player;
                    $ids[] = $player->id;
                }
            }
        }

        return $players;


    }

    public function getMyPlayer(){
        $user = \Auth::user();
        if($user != null && $user->guid != null){
            return Player::where('guid', $user->guid)->first();
        }
        return null;
    }

    public function getAllProfiles($guid){
        $b3servers = B3Server::get();
        $servers = [];
        foreach($b3servers as $b3server){
            \Config::set('database.connections.b3-'.$b3server->id, json_decode(\Crypt::decrypt(($b3server->dbSettings)), true));
            $player = new Player();
            $player = $player->setConnection('b3-'.$b3server->id)->where('guid', $guid)->with('group')->first();



            if($player != null && $player->exists){
                $servers[$b3server->id]['name'] = $b3server->name;
                $penalty = (new Penalty())->setConnection('b3-'.$b3server->id);
                $isBanned = $penalty->where('client_id', $player->id)->where('inactive', 0)->whereIn('type', ['Ban', 'TempBan'])->where(function($query){
                    $query->where('time_expire', '-1');
                    $query->orWhere('time_expire', '>', Carbon::now()->getTimestamp());
                })->count();

                $player->isBanned = $isBanned;
                $servers[$b3server->id]['player'] = $player;
            }
        }

        return $servers;
    }

    public function isPlayerBanned($guid){
        return \Cache::remember('player-'.$guid, 60, function () use($guid) {
            $b3servers = B3Server::get();
            foreach ($b3servers as $b3server) {
                \Config::set('database.connections.b3-' . $b3server->id, json_decode(\Crypt::decrypt(($b3server->dbSettings)), true));
                $player = new Player();
                $player = $player->setConnection('b3-'.$b3server->id)->where('guid', $guid)->first();
                if($player) {

                    $penalties = new Penalty();
                    $penalties = $penalties->setConnection('b3-' . $b3server->id)->where('client_id', $player->id)->get();

                    /** @var Penalty $penalty */
                    foreach ($penalties as $penalty) {
                        if ($penalty->isActive()) {
                            return true;
                        }
                    }
                }
            }
            return false;
        });
    }

}