<?php

namespace Pearls\Modules\Slider\Models;

use Pearls\Base\Models\BaseModel;
use Pearls\Base\Transformers\PresentableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slider extends BaseModel implements HasMedia
{
    use PresentableTrait, InteractsWithMedia;
    
    public $config = 'slider.models.slider';

    protected $fillable = [
        'name',
        'caption',
        'status',
    ];
}