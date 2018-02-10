<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 29-4-2017
 * Time: 00:03
 */

namespace Stormyy\B3\Models;


use App\Server;
use Illuminate\Database\Eloquent\Model;

class B3Server extends Model
{
    protected $table = 'b3servers';
    protected $casts = [];

    public function screenshots(){
        return $this->hasMany(Screenshot::class, 'server_id');
    }

    public function takenBy(){
        return $this->belongsTo(\Config::get('b3cod4x.usermodel'), 'takenBy_id');
    }

}