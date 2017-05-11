<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 9-5-2017
 * Time: 22:38
 */

namespace Stormyy\B3\Policies;


use App\User;
use Stormyy\B3\Models\B3Server;

interface B3ServerPolicyInterface
{
    public function before($user, $ability);
    public function screenshot(User $user, B3Server $b3server);

}