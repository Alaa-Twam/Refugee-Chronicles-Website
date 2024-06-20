<?php

namespace Pearls\Modules\Slider\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Pearls\Modules\Slider\Models\Slider;
use Pearls\Modules\Slider\Policies\SliderPolicy;

class SliderAuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Slider::class => SliderPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}