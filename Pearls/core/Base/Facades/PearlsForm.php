<?php

namespace Pearls\Base\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class PearlsForm extends IlluminateFacade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\Base\Classes\PearlsForm::class;
    }
}
