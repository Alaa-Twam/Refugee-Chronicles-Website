<?php

namespace Pearls\Modules\CMS\DataTables;

use Pearls\Base\DataTables\BaseDataTable;
use Pearls\Modules\CMS\Models\Chronicle;
use Pearls\Modules\CMS\Transformers\ChronicleTransformer;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ChroniclesDataTable extends BaseDataTable
{
    public function __construct()
    {
        $this->setResourceUrl(config('cms.models.chronicle.resource_url'));
        parent::__construct();
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ChronicleTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Chronicle $model)
    {
        $model = $model->newQuery();

        return $model;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'title' => ['title' => 'Title'],
            'city' => ['title' => 'City'],
            'status' => ['title' => 'Status'],
            'created_at' => ['title' => 'Created at'],
            'updated_at' => ['title' => 'Updated at'],
        ];
    }

    protected function getFilters()
    {
        return [
            'title' => ['title' => 'Title', 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
        ];
    }
}
