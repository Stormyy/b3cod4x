<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 29-4-2017
 * Time: 06:21
 */

namespace Stormyy\B3\Helper;

abstract class PermissionHelper
{
    public static function ip($ip){
        if(\Auth::user() == null) {
            return preg_replace('/\.\d+\.\d+$/', '.***.***', $ip);
        } else {
            return $ip;
        }
    }

}