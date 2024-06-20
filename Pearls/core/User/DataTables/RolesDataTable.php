<?php

namespace Pearls\User\DataTables;

use Pearls\Base\DataTables\BaseDataTable;
use Pearls\User\Models\Role;
use Pearls\User\Transformers\RoleTransformer;
use Yajra\DataTables\EloquentDataTable;

class RolesDataTable extends BaseDataTable
{
    public function __construct()
    {
        $this->setResourceUrl(config('user.models.role.resource_url'));
        parent::__construct();
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new RoleTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Role $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Role $model)
    {
        return $model->withCount('users');
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
            'users_count' => ['title' => 'Users Count', 'searchable' => false],
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
