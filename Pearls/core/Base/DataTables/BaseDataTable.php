<?php

namespace Pearls\Base\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder;

abstract class BaseDataTable extends DataTable
{
    protected $resource_url;

    public function __construct()
    {
        $this->addScope(new PearlsScope($this->getFilters()));
    }

    public function renderAjaxAndActions()
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
        }

        if ($action = $this->request()->get('action') and in_array($action, $this->actions)) {
            if ($action == 'print') {
                return app()->call([$this, 'printPreview']);
            }

            return app()->call([$this, $action]);
        }
    }

    /**
     * @param $resource_url
     * @return $this
     */
    public function setResourceUrl($resource_url)
    {
        $this->resource_url = url($resource_url);
        return $this;
    }

    /**
     * Get DataTables Html Builder instance.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function builder(): Builder
    {
        return app(PearlsBuilder::class);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $tableId = $this->getTableId();
        return $this->builder()
            ->setFilters($this->getFilters())
            ->setBulkActions($this->getBulkActions())
            ->setRowDetailsTemplate($this->getRowDetailsTemplate())
            ->setOptions($this->getOptions())
            ->setTableId($tableId)
            ->columns($this->getColumns())
            ->minifiedAjax($this->resource_url,
                '$.each(filters("#' . $tableId . '"), function(name,value){
                        data[name] = value;
                    });')
            ->addCheckbox(['datatable_id' => $tableId], true)
            ->addAction(['width' => '80px'])
            ->addDetails(['width' => '20px'])
            ->parameters(array_merge([
                'order' => [[0, 'desc']],
                'pageLength' => 10,
                "dom" => "lBrtip",
                'buttons' => ['excel', 'csv', 'print', 'reload',
                    [
                        'attr' => [
                            'id' => $tableId . '_filtersCollapse-btn',
                            'data-toggle' => "collapse",
                            'class' => 'btn btn-sm btn-primary',
                        ],
                        'text' => '<i class="fa fa-filter"></i>',
                        'className' => 'buttons-filter',
                        'action' => 'function ( e, dt, node, config ) { $("#' . $tableId . '_filtersCollapse").collapse("toggle"); }'
                    ]],
                'rowReorder' => ['selector' => 'tr>td:not(:last-child)', // I allow all columns for dragdrop except the last
                    'dataSrc' => 'sortsequence',
                    'update' => false // this is key to prevent DT auto update
                ]
            ], $this->getBuilderParameters()));
    }

    protected function getFilters()
    {
        return [];
    }

    protected function getColumns()
    {
        return [];
    }


    protected function getBulkActions()
    {
        return [];
    }

    protected function getRowDetailsTemplate()
    {
        return "";
    }

    protected function getOptions()
    {
        return [];
    }

    protected function getTableId()
    {
        return class_basename($this);
    }

    protected function getBuilderParameters(): array
    {
        return [];
    }
}
