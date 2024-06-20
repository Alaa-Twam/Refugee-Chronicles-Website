<?php

namespace Pearls\Modules\City\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class CityRouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Pearls\Modules\City\Http\Controllers';


    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        parent::boot();

        Route::model('city', \Pearls\Modules\City\Models\City::class, function ($value) {
            return \Pearls\Modules\City\Models\City::where('id', $value)->first() ?? abort(404);
        });
        if (class_exists('Breadcrumbs')) {
            require __DIR__ . '/../routes/city_breadcrumbs.php';
        }
        
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/api.php');

            Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/web.php');
        });
    }
}