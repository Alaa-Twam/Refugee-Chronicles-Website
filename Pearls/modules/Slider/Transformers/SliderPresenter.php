<?php

namespace Pearls\Modules\Slider\Transformers;

use Pearls\Base\Transformers\FractalPresenter;

class SliderPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new SliderTransformer();
    }
}