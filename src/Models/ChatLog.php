<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 20-5-2017
 * Time: 18:53
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    protected $connection = 'b3';
    protected $table = 'chatlog';
    public $timestamps = false;

    public function player(){
        return $this->belongsTo(Player::class, 'client_id');
    }

}