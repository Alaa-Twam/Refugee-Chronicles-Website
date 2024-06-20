<?php

namespace Pearls\Modules\Slider\Transformers;

use Pearls\Base\Transformers\BaseTransformer;
use Pearls\Modules\Slider\Models\Slider;

class SliderTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('slider.models.slider.resource_url');

        parent::__construct();
    }

    public function transform(Slider $slider)
    {
        $sliderImage = '<img src="'.asset('media/default.png').'" style="width: 60px; height: 40px;" />';
        if($slider->hasMedia('slider-media')) {
            $sliderImage = '<img src="'.$slider->getFirstMediaUrl('slider-media').'" style="width: 60px; height: 40px;" />';
        }

        return [
            'id' => $slider->id,
            'slider_image' => $sliderImage,
            'name' => $slider->name,
            'lat' => $slider->lat,
            'lng' => $slider->lng,
            'status' => formatStatusResponse($slider->status),
            'created_at' => format_date($slider->created_at),
            'updated_at' => format_date($slider->updated_at),
            'action' => $this->actions($slider)
        ];
    }

    protected function formatRolesResponse($roles)
    {
        $response = '';

        foreach ($roles as $role) {
            $response .= '<span class="label label-success">' . $role . '</span>&nbsp;';
        }

        return $response;
    }
}
