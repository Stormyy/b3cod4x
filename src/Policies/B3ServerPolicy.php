<?php

namespace Stormyy\B3\Policies;

use App\User;
use Stormyy\B3\Models\B3Server;

/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 9-5-2017
 * Time: 22:30
 */
class B3ServerPolicy implements B3ServerPolicyInterface
{
    public function before($user, $ability)
    {
        return null;
    }

    public function screenshot(User $user, B3Server $b3server)
    {
        return \Auth::check();
    }

}