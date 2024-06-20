<?php

namespace Pearls\Modules;

use Pearls\Modules\CMS\CMSServiceProvider;
use Pearls\Modules\City\CityServiceProvider;
use Pearls\Modules\Slider\SliderServiceProvider;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(CMSServiceProvider::class);
        $this->app->register(CityServiceProvider::class);
        $this->app->register(SliderServiceProvider::class);
    }
}
                