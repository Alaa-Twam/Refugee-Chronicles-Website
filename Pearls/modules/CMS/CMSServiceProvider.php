<?php

namespace Pearls\Modules\CMS;

use Pearls\Modules\CMS\Providers\CMSAuthServiceProvider;
use Pearls\Modules\CMS\Providers\CMSRouteServiceProvider;
use Pearls\Modules\CMS\Facades\CMS;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;

class CMSServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'CMS');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'CMS');

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
        $this->mergeConfigFrom(__DIR__ . '/config/cms.php', 'cms');

        $this->app->register(CMSRouteServiceProvider::class);
        $this->app->register(CMSAuthServiceProvider::class);

        //register aliases instead of adding it to config/app.php
        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('CMS', CMS::class);
        });
    }
}
