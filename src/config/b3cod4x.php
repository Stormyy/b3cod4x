<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions configuration
    |--------------------------------------------------------------------------
    |
    | Overwrite the default policy by making a class that extends B3ServerPolicy
    | Or set the group levels of the different permissions
    |
    */

    'policy' => \Stormyy\B3\Policies\B3ServerPolicy::class,

    /**
     * Permission groups:
     * Superadmin = 128
     * Senioradmin = 64
     * Fulladmin = 32
     * Admin = 16
     * Moderator = 8
     * Regular = 2
     * User = 1
     * Guest = 0
     */
    'permissions' => [
        'screenshot' => 8, //Ability to take a screenshot
        'remove' => 128, //Ability to remove server
        'unban' => 32,  //Ability to uban
        'setrank' => 64, //Ability to set rank
        'ban' => 32, //Ability to ban
        'chat' => 32 //Ability to chat
    ],


    'usertable' => 'users',
    'usermodel' => \App\User::class,






];