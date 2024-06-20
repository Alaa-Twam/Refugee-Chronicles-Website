<?php

namespace Pearls\Modules\Slider;

use Pearls\Modules\Slider\Providers\SliderAuthServiceProvider;
use Pearls\Modules\Slider\Providers\SliderRouteServiceProvider;
use Pearls\Modules\Slider\Facades\Sliders;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;

class SliderServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Slider');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Slider');

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
        $this->mergeConfigFrom(__DIR__ . '/config/slider.php', 'slider');

        $this->app->register(SliderRouteServiceProvider::class);
        $this->app->register(SliderAuthServiceProvider::class);

        //register aliases instead of adding it to config/app.php
        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Sliders', Sliders::class);
        });
    }
}