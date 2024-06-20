<?php

namespace Pearls\User\Transformers;

use Pearls\Base\Transformers\BaseTransformer;
use Pearls\User\Models\User;

class UserTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('user.models.user.resource_url');

        parent::__construct();
    }

    /**
     * @param User $user
     * @return array
     * @throws \Throwable
     */
    public function transform(User $user)
    {
        $actions = [];

        // prevent user from deleting him self
        if (isSuperUser($user) || user()->id == $user->id) {
            $actions['delete'] = '';
        }

        // prevent not superusers to update superusers
        if (!isSuperUser() && isSuperUser($user)) {
            $actions['edit'] = '';
        }

        return [
            'id' => $user->id,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'roles' => $this->formatRolesResponse($user->roles->pluck('name')),
            'status' => formatStatusResponse($user->status),
            'created_at' => format_date($user->created_at),
            'updated_at' => format_date($user->updated_at),
            'action' => $this->actions($user, $actions)
        ];
    }

    protected function formatRolesResponse($roles)
    {
        $response = '';

        foreach ($roles as $role) {
            $response .= '<span class="label label-success">' . $role . '</span>&nbsp;';
        }

        return $response;
    }
}
