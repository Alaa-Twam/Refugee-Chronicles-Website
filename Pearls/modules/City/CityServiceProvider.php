<?php

namespace Pearls\Modules\City;

use Pearls\Modules\City\Providers\CityAuthServiceProvider;
use Pearls\Modules\City\Providers\CityRouteServiceProvider;
use Pearls\Modules\City\Facades\Cities;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;

class CityServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'City');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'City');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/city.php', 'city');

        $this->app->register(CityRouteServiceProvider::class);
        $this->app->register(CityAuthServiceProvider::class);

        //register aliases instead of adding it to config/app.php
        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Cities', Cities::class);
        });
    }
}
