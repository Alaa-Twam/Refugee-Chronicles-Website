<?php

namespace Pearls\Base\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class ModelActionsHandler extends IlluminateFacade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\Base\Classes\ModelActionsHandler::class;
    }
}
