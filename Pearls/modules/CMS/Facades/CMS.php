<?php

namespace Pearls\Modules\CMS\Facades;

use Illuminate\Support\Facades\Facade;

class CMS extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\Modules\CMS\Classes\CMS::class;
    }
}