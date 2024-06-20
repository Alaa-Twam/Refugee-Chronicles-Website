<?php

namespace Pearls\Modules\City\Transformers;

use Pearls\Base\Transformers\FractalPresenter;

class CityPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new CityTransformer();
    }
}