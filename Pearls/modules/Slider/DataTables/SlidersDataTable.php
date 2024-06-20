<?php

namespace Pearls\Modules\Slider\DataTables;

use Pearls\Base\DataTables\BaseDataTable;
use Pearls\Modules\Slider\Models\Slider;
use Pearls\Modules\Slider\Transformers\SliderTransformer;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SlidersDataTable extends BaseDataTable
{
    public function __construct()
    {
        $this->setResourceUrl(config('slider.models.slider.resource_url'));
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

        return $dataTable->setTransformer(new SliderTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Slider $model)
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
            'slider_image' => ['title' => 'Image'],
            'name' => ['title' => 'Name'],
            'status' => ['title' => 'Status'],
            'created_at' => ['title' => 'Created at'],
            'updated_at' => ['title' => 'Updated at'],
        ];
    }


    protected function getFilters()
    {
        return [
            'name' => ['title' => 'Name', 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
        ];
    }
}
