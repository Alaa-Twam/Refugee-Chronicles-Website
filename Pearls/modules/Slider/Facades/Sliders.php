<?php

namespace Pearls\Modules\Slider\Facades;

use Illuminate\Support\Facades\Facade;

class Sliders extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\Modules\Slider\Classes\Sliders::class;
    }
}