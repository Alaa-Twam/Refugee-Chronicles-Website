<?php

namespace Pearls\Modules\CMS\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Pearls\Modules\CMS\Models\Chronicle;
use Pearls\Modules\CMS\Policies\ChroniclePolicy;

class CMSAuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Chronicle::class => ChroniclePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}