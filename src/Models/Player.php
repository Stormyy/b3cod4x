<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 19:01
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'b3';
    protected $table = 'clients';
    public $timestamps = false;

    public function screenshots(){
        return $this->hasMany(Screenshot::class, 'guid', 'guid');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_bits');
    }

    public function aliases(){
        return $this->hasMany(Alias::class, 'client_id');
    }

    public function ipaliases(){
        return $this->hasMany(IpAlias::class, 'client_id');
    }

    public function penalties(){
        return $this->hasMany(Penalty::class, 'client_id')->orderBy('time_add', 'desc');
    }

    public function adminpenalties(){
        return $this->hasMany(Penalty::class, 'admin_id')->orderBy('time_add', 'desc');
    }

    public function owner(){
        return $this->hasOne(\Config::get('b3cod4x.usermodel'), 'guid');
    }

    public function chatlogs(){
        return $this->hasMany(ChatLog::class, 'client_id');
    }

    public function marks(){
        return $this->hasMany(Mark::class, 'guid')->orderBy('created_at', 'desc');
    }

    public function sessions(){
        return $this->hasMany(Session::class, 'guid');
    }
}