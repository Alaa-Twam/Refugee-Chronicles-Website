<?php

namespace Pearls\Modules\City\Transformers;

use Pearls\Base\Transformers\BaseTransformer;
use Pearls\Modules\City\Models\City;

class CityTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('city.models.city.resource_url');

        parent::__construct();
    }

    public function transform(City $city)
    {
        $item_actions = [
            'edit' => [
                'icon' => 'fa fa-fw fa-pencil',
                'href' => url($this->resource_url . '/' . $city->id . '/edit'),
                'label' => 'Edit',
                'data' => [
                ]
            ],
            'delete' => [],
        ];


        return [
            'id' => $city->id,
            'name' => $city->name,
            'lat' => $city->lat,
            'lng' => $city->lng,
            'chronicles_count' => $city->chronicles->count(),
            'created_at' => format_date($city->created_at),
            'updated_at' => format_date($city->updated_at),
            'action' => $this->actions($city, $item_actions)
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
