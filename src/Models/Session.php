<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 19:01
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $connection = 'b3';
    protected $table = 'ctime';

    public function player(){
        return $this->belongsTo(Player::class, 'guid', 'guid');
    }

}