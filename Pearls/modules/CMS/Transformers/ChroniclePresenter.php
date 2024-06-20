<?php

namespace Pearls\Modules\CMS\Transformers;

use Pearls\Base\Transformers\FractalPresenter;

class ChroniclePresenter extends FractalPresenter
{

    /**
     * @return UserTransformer
     */
    public function getTransformer()
    {
        return new ChronicleTransformer();
    }
}