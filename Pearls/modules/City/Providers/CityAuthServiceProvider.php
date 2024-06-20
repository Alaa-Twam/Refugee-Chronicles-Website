<?php

namespace Pearls\Modules\City\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Pearls\Modules\City\Models\City;
use Pearls\Modules\City\Policies\CityPolicy;

class CityAuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        City::class => CityPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}