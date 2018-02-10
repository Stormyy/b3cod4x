<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 06:20
 */

namespace Stormyy\B3\Models;


use App\Server;
use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $connection = 'mysql';
    protected $table = 'screenshots';
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['url'];

    public function server(){
        return $this->belongsTo(B3Server::class);
    }

    public function getUrlAttribute($value)
    {
        return \Storage::disk('screenshots')->url($this->filename);
    }

    public function takenBy(){
        return $this->belongsTo(\Config::get('b3cod4x.usermodel'), 'takenBy_id');
    }

}