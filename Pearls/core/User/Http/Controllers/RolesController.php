<?php

namespace Pearls\User\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController; use Pearls\User\DataTables\RolesDataTable;
use Pearls\User\Http\Requests\RoleRequest;
use Pearls\User\Models\Permission;
use Pearls\User\Models\Role;

class RolesController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('user.models.role.resource_url');
        $this->title = 'Roles';
        $this->title_singular = 'Role';

        parent::__construct();
    }

    /**
     * @param RoleRequest $request
     * @param RolesDataTable $dataTable
     * @return mixed
     */
    public function index(RoleRequest $request, RolesDataTable $dataTable)
    {
        return $dataTable->render('User::roles.index');
    }

    /**
     * @param RoleRequest $request
     * @return $this
     */
    public function create(RoleRequest $request)
    {
        $role = new Role();

        $this->setViewSharedData(['title_singular' => 'Create ' . $this->title_singular]);


        $permissions = $this->getAvailablePermissions();

        return view('User::roles.create_edit')->with(compact('role', 'permissions'));
    }

    /**
     * @param Role $role
     * @return array
     */
    protected function getAvailablePermissions(Role $role = null)
    {
        $permissionsList = Permission::all();
        $permissions = [];
        $rolePermissions = collect([]);
        if ($role) {
            $rolePermissions = $role->permissions;
        }

        foreach ($permissionsList as $permission) {
            $permissions[$permission->id] = ['checked' => $rolePermissions->contains($permission->id)];

            if ($permission->location_level) {
                $rolePerm = $rolePermissions->where('id', $permission->id)->first();
                $permissions[$permission->id]['self'] = $rolePerm ? $rolePerm->pivot->self_location : 0;
                $permissions[$permission->id]['other'] = $rolePerm ? $rolePerm->pivot->other_location : 0;
            }
        }

        return $permissions;
    }

    /**
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoleRequest $request)
    {
        try {
            $data = $request->all();
            $permissions = $data['permissions'] ?? [];

            unset($data['permissions']);

            $role = Role::create($data);

            $role->permissions()->sync($permissions);

            flash(trans('Pearls::messages.success.created', ['item' => 'Role']))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Role::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    protected function preparePermissionsForSync($permissions)
    {
        $permissionsSync = [];

        foreach ($permissions as $id => $permission) {
            if (isset($permission['checked']) && $permission['checked'] == 1) {
                $permissionsSync[$id] = [
                    'self_location' => (isset($permission['self']) && $permission['self'] == 1) ? 1 : 0,
                    'other_location' => (isset($permission['other']) && $permission['other'] == 1) ? 1 : 0,
                ];
            }
        }

        return $permissionsSync;
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return $this
     */
    public function edit(RoleRequest $request, Role $role)
    {
        $this->setViewSharedData(['title_singular' => "Update [{$role->name}]"]);

        $permissions = $this->getAvailablePermissions($role);

        return view('User::roles.create_edit')->with(compact('role', 'permissions'));
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data = $request->all();

            $permissions = $data['permissions'] ?? [];

            unset($data['permissions']);

            $role->update($data);

            $role->permissions()->sync($permissions);

            flash(trans('Pearls::messages.success.updated', ['item' => 'Role']))->success();
        } catch (\Exception $exception) {
            logger($exception);
            //log_exception($exception, Role::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RoleRequest $request, Role $role)
    {
        try {
            $super_user_role = 1;

            if ($role->id == $super_user_role) {
                throw new \Exception('You can\'t delete super user role!!');
            }

            $role->delete();

            $message = ['level' => 'success', 'message' => trans('Pearls::messages.success.deleted', ['item' => 'Role'])];
        } catch (\Exception $exception) {
            log_exception($exception, Role::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}