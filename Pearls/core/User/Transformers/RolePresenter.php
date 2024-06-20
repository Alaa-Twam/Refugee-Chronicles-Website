<?php

namespace Pearls\User\Transformers;

use Pearls\Base\Transformers\FractalPresenter;

class RolePresenter extends FractalPresenter
{

    /**
     * @return RoleTransformer
     */
    public function getTransformer()
    {
        return new RoleTransformer();
    }
}