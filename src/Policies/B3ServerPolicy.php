<?php

namespace Stormyy\B3\Policies;

use App\User;
use Stormyy\B3\Helper\B3Database;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Models\Player;

/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 9-5-2017
 * Time: 22:30
 */
class B3ServerPolicy implements B3ServerPolicyInterface
{
    protected $myplayer;

    public function before($user, $ability)
    {
        return null;
    }

    private function getPlayer(B3Server $b3Server){
        if($this->myplayer == null) {
            $b3database = (new B3Database($b3Server));
            $this->myplayer = $b3database->getMyPlayer();
        }
    }

    public function screenshot(User $user, B3Server $b3Server)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer) {
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.screenshot', 8);
    }

    public function manage(User $user)
    {
        return $user != null;
    }

    public function remove(User $user, B3Server $b3Server)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits == \Config::get('b3cod4x.permissions.remove', 128);
    }

    public function unban(User $user, B3Server $b3Server, Player $player)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.unban', 32);
    }

    public function setrank(User $user, B3Server $b3Server, Player $player)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.setrank', 64) && ($this->myplayer->group_bits > $player->group_bits || $this->myplayer->group_bits == 128);
    }

    public function ban(User $user, B3Server $b3Server, Player $player)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.ban', 16) && $this->myplayer->group_bits > $player->group_bits;
    }

    public function banWithoutProof(User $user, B3Server $b3Server, Player $player)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.ban', 32) && $this->myplayer->group_bits > $player->group_bits;
    }

    public function chat(User $user, B3Server $b3Server)
    {
        $this->getPlayer($b3Server);
        if(!$this->myplayer){
            return false;
        }
        return $this->myplayer->group_bits >= \Config::get('b3cod4x.permissions.chat', 32);
    }
}