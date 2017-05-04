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
    protected $casts = [
        'dbSettings' => 'array',
    ];

    public function server(){
        return $this->belongsTo(Server::class);
    }

    public function screenshots(){
        return $this->hasMany(Screenshot::class, 'server_id');
    }

}