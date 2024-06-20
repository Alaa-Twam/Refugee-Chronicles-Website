<?php

namespace Pearls\User\Transformers;

use Pearls\Base\Transformers\FractalPresenter;

class UserPresenter extends FractalPresenter
{

    /**
     * @return UserTransformer
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}