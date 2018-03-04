<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 02:52
 */

namespace Stormyy\B3;


use Illuminate\Support\ServiceProvider;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Policies\B3ServerPolicy;
use Torann\GeoIP\GeoIPServiceProvider;

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

        $this->publishes([
            __DIR__.'/../config/b3cod4x.php' => config_path('b3cod4x.php'),
            __DIR__.'/../config/geoip.php' => config_path('geoip.php'),
        ]);

        \Gate::policy(B3Server::class, \Config::get('b3cod4x.policy'));
    }

    public function register()
    {
        $this->app->register(GeoIPServiceProvider::class);
    }
}
