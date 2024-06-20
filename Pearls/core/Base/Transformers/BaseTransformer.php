<?php

namespace Pearls\Base\Transformers;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected $resource_url;

    public function __construct()
    {
    }

    /**
     * @param $model
     * @param array $actions
     * @return string
     * @throws \Throwable
     */
    protected function actions($model, array $actions = [])
    {
        $actions = array_merge($model->getActions(true), $actions);

        $actions = collect($actions)->filter(function ($action) {
            return !empty($action);
        });

        return view('admin.components.item_actions', ['actions' => $actions->toArray()])->render();
    }
}
