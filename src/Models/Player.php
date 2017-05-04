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
}