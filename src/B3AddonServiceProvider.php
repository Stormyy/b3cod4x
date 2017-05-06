<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 02:52
 */

namespace Stormyy\B3;


use Illuminate\Support\ServiceProvider;

class B3AddonServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'b3');

        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/stormyy/b3cod4x'),
        ], 'public');
    }

    public function register()
    {

    }
}
