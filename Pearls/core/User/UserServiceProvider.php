<?php

namespace Pearls\User;

use Pearls\User\Models\User;
use Pearls\User\Providers\UserAuthServiceProvider;
use Pearls\User\Providers\UserRouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class UserServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'User');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'User');

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
        $this->mergeConfigFrom(__DIR__ . '/config/user.php', 'user');

        $this->app->register(UserRouteServiceProvider::class);
        $this->app->register(UserAuthServiceProvider::class);
    }
}
