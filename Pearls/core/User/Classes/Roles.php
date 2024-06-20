<?php

namespace Pearls\User\Classes;

use Pearls\User\Models\Permission;
use Pearls\User\Models\Role;

class Roles
{
    /**
     * Roles constructor.
     */
    function __construct()
    {
    }

    public function getRolesList($params = [])
    {

        if (isset($params['admin_roles']) && $params['admin_roles'] == true) {
            $roles = Role::where('type', 'admin')->where('id', '!=', 1);
        } elseif (isset($params['all']) && $params['all'] == true) {
            $roles = Role::all();
        }

        $roles = $roles->pluck('name', 'id');

        return $roles;
    }

    public function getPermissionsTree()
    {
        $tree = [];

        $permissions = Permission::get();

        foreach ($permissions as $permission) {
            list($package, $model) = explode('::', $permission->name);

            list($model, $action) = explode('.', $model);

            if (!isset($tree[$package])) {
                $tree[$package] = [];
            }
            if (!isset($tree[$package][$model])) {
                $tree[$package][$model] = [];
            }
            $tree[$package][$model][$permission->id] = $action;
        }

        return $tree;
    }
}