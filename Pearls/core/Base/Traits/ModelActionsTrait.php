<?php

namespace Pearls\Base\Traits;

use Pearls\Base\Facades\ModelActionsHandler;

trait ModelActionsTrait
{
    public function getCommonActions()
    {
        return [
            'edit' => [
                'icon' => 'fa fa-fw fa-pencil',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getEditURL();']],
                'class' => 'btn btn-primary btn-sm',
                'label' => 'Edit',
                'policies' => ['update'],
                'permissions' => [],
                'data' => []
            ],
            'delete' => [
                'datatable_only' => true,
                'icon' => 'fa fa-fw fa-remove',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL();']],
                'class' => 'btn btn-danger btn-sm',
                'label' => 'Delete',
                'policies' => ['destroy'],
                'permissions' => [],
                'data' => [
                    'action' => 'delete',
                    'table' => '.dataTableBuilder'
                ]
            ],
        ];
    }

    /**
     * @param bool $isDatatable
     * @param null $view
     * @return array|string
     */
    public function getActions($isDatatable = false, $view = null)
    {
        if ($this->archived ?? false) {
            return '';
        }

        $actions = $this->getConfig('actions');

        if (!$actions || !is_array($actions)) {
            $actions = [];
        }

        $actions = array_merge($this->getCommonActions(), $actions);

        foreach ($actions as $index => $action) {
            if (!ModelActionsHandler::isActionVisible($action, $this)) {
                $actions[$index] = null;
                continue;
            }

            $actions[$index] = ModelActionsHandler::solveActionPatterns($action, $this);
        }

        if (!$isDatatable) {
            $actions = array_filter($actions, function ($action) {
                if (isset($action['datatable_only'])) {
                    return false;
                }

                return true;
            });

            $actions = ModelActionsHandler::renderActions($actions, $view);
        }

        return $actions;
    }
}
