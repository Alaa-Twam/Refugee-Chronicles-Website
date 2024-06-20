<?php

namespace Pearls\Base\Contracts;


interface PearlsScope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @param $builder
     * @param array $extras
     * @return void
     */
    public function apply($builder, $extras = []);
}