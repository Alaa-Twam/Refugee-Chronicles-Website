<?php

namespace Pearls\Modules\City\Facades;

use Illuminate\Support\Facades\Facade;

class Cities extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\Modules\City\Classes\Cities::class;
    }
}