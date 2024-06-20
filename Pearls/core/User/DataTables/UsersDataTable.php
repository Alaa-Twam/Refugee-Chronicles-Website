<?php

namespace Pearls\User\DataTables;

use Pearls\Base\DataTables\BaseDataTable;
use Pearls\User\Models\User;
use Pearls\User\Transformers\UserTransformer;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UsersDataTable extends BaseDataTable
{
    public function __construct()
    {
        $this->setResourceUrl(config('user.models.user.resource_url'));
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

        return $dataTable->setTransformer(new UserTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(User $model)
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
            'username' => ['title' => 'Username'],
            'first_name' => ['title' => 'First Name'],
            'last_name' => ['title' => 'Last Name'],
            'email' => ['title' => 'Email'],
            'roles' => ['title' => 'Roles', 'orderable' => false, 'searchable' => false],
            'status' => ['title' => 'Status'],
            'created_at' => ['title' => 'Created at'],
            'updated_at' => ['title' => 'Updated at'],
        ];
    }

    protected function getFilters()
    {
        return [
            'first_name' => ['title' => 'First Name', 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'last_name' => ['title' => 'Last Name', 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
        ];
    }
}
