<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 19:01
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $connection = 'b3';
    protected $table = 'aliases';

    public function player(){
        return $this->belongsTo(Player::class, 'client_id');
    }

}