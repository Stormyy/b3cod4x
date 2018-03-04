<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 20:52
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $connection = 'b3';
    protected $table = 'groups';

    public function players(){
        return $this->hasMany(Player::class, 'group_bits');
    }

}