<?php

namespace Pearls\User\Facades;

use Illuminate\Support\Facades\Facade;

class Users extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Pearls\User\Classes\Users::class;
    }
}