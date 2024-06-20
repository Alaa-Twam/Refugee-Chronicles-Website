<?php

namespace Pearls;

use Pearls\User\Facades\Roles;
use Pearls\User\Facades\Users;
use Pearls\Base\Facades\PearlsForm;
use Pearls\User\UserServiceProvider;
use Pearls\Media\MediaServiceProvider;
use Pearls\Modules\ModulesServiceProvider;
use Hashids\Hashids;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class PearlsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('frontend/partials/custom-frontend-pagination');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/Base/resources/lang', 'Pearls');

        // Load view
        $this->loadViewsFrom(__DIR__ . '/Base/resources/views', 'Pearls');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/Base/database/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        \File::requireOnce(__DIR__ . '/Base/Helpers/auth.php');
        \File::requireOnce(__DIR__ . '/Base/Helpers/helpers.php');

        $this->app->register(UserServiceProvider::class);
        $this->app->register(MediaServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);

        //register Settings alias instead of adding it to config/app.php
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Users', Users::class);
            $loader->alias('PearlsForm', PearlsForm::class);
            $loader->alias('Roles', Roles::class);
            $loader->alias('Users', Users::class);
        });

        // Bind 'hashids' shared component to the IoC container
        $this->app->singleton('hashids', function ($app) {

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

            $salt = hash('sha256', 'Pearls');

            return new Hashids($salt, 10, $alphabet);
        });
    }
}
                