<?php

namespace Pearls\Modules\CMS\Models;

use Pearls\Base\Models\BaseModel;
use Pearls\Base\Transformers\PresentableTrait;
use Pearls\Modules\City\Models\City;

class Chronicle extends BaseModel
{
    use PresentableTrait;
    
    public $config = 'cms.models.chronicle';

    protected $fillable = [
        'title',
        'description',
        'youtube_link',
        'city_id',
        'lat',
        'lng',
        'status',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}