<?php

namespace Pearls\Modules\CMS\Transformers;

use Pearls\Base\Transformers\BaseTransformer;
use Pearls\Modules\CMS\Models\Chronicle;

class ChronicleTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('cms.models.chronicle.resource_url');

        parent::__construct();
    }

    /**
     * @param User $user
     * @return array
     * @throws \Throwable
     */
    public function transform(Chronicle $chronicle)
    {
 
        return [
            'id' => $chronicle->id,
            'title' => \Str::limit($chronicle->title, $limit = 30, $end = '...'),
            'city' => $chronicle->city->name,
            'lat' => $chronicle->lat,
            'lng' => $chronicle->lng,
            'status' => formatStatusResponse($chronicle->status),
            'created_at' => format_date($chronicle->created_at),
            'updated_at' => format_date($chronicle->updated_at),
            'action' => $this->actions($chronicle)
        ];
    }
}