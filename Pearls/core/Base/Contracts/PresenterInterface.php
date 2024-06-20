<?php

namespace Pearls\Base\Contracts;

/**
 * Interface PresenterInterface
 * @package Pearls\Foundation\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
