<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 19:01
 */

namespace Stormyy\B3\Models;


use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $connection = 'b3';
    protected $table = 'penalties';
    public $timestamps = false;

    public function player(){
        return $this->belongsTo(Player::class, 'client_id');
    }

    public function admin(){
        return $this->belongsTo(Player::class, 'admin_id');
    }

}