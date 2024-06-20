<?php

namespace Pearls\User\Policies;

use Illuminate\Auth\Access\Response;
use Pearls\User\Models\User;

class UserPolicy
{
    public function __construct()
    {
        $type = request()->is('*employees*') ? 'employee' : 'admin';

        $this->type = $type;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasPermissionTo("User::{$this->type}.view")) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("User::{$this->type}.create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($this->type == 'admin' && $user->hasPermissionTo("User::{$this->type}.update")) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, User $model): bool
    {
        if ($user->hasPermissionTo("User::{$this->type}.delete")) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }

    /**
     * @param $authUser
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {

        if (isSuperUser($user)) {
            return true;
        }

        return null;
    }
}
