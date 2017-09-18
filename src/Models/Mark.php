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

class Mark extends Model
{
    protected $connection = 'mysql';
    protected $table = 'marks';
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['url'];

}