<?php

namespace Pearls\Modules\City\Models;

use Pearls\Base\Models\BaseModel;
use Pearls\Base\Transformers\PresentableTrait;
use Pearls\Modules\CMS\Models\Chronicle;

class City extends BaseModel
{
    use PresentableTrait;
    
    public $config = 'city.models.city';
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'lat',
        'lng',
    ];

    public function chronicles() {
        return $this->hasMany(Chronicle::class);
    }
}