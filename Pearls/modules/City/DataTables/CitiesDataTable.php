<?php

namespace Pearls\Modules\City\DataTables;

use Pearls\Base\DataTables\BaseDataTable;
use Pearls\Modules\City\Models\City;
use Pearls\Modules\City\Transformers\CityTransformer;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CitiesDataTable extends BaseDataTable
{
    public function __construct()
    {
        $this->setResourceUrl(config('city.models.city.resource_url'));
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

        return $dataTable->setTransformer(new CityTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(City $model)
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
            'name' => ['title' => 'Name'],
            'chronicles_count' => ['title' => 'Chronicles Count'],
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
