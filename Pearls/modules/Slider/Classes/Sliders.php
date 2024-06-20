<?php

namespace Pearls\Modules\Slider\Classes;
use Pearls\Modules\Slider\Models\Slider;

class Sliders
{
    function __construct() {}

    public function getActiveSliders()
    {
        $sliders = Slider::where('status', 'active')->orderBy('id', 'desc')->get();

        return $sliders;
    }
}