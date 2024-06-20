<?php

namespace Pearls\User\Transformers;

use Pearls\Base\Transformers\BaseTransformer;
use Pearls\User\Models\Role;

class RoleTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('user.models.role.resource_url');

        parent::__construct();
    }

    /**
     * @param Role $role
     * @return array
     */
    public function transform(Role $role)
    {
        $super_user_role = 1;

        $actions = [];

        if ($role->id == $super_user_role) {
            $actions['delete'] = '';
            $actions['edit'] = '';
        }

        return [
            'id' => $role->id,
            'name' => $role->name,
            'users_count' => $role->users_count,
            'created_at' => format_date($role->created_at),
            'updated_at' => format_date($role->updated_at),
            'action' => $this->actions($role, $actions)
        ];
    }
}