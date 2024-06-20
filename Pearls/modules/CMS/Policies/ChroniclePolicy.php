<?php

namespace Pearls\Modules\CMS\Policies;

use Pearls\Modules\CMS\Models\Chronicle;
use Illuminate\Auth\Access\Response;
use Pearls\User\Models\User;

class ChroniclePolicy
{
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
    public function view(User $user): bool
    {
        if ($user->can('CMS::chronicle.view')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('CMS::chronicle.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chronicle $chronicle): bool
    {
        if ($user->can('CMS::chronicle.update')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Chronicle $chronicle): bool
    {
        if ($user->can('CMS::chronicle.delete')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chronicle $chronicle): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chronicle $chronicle): bool
    {
        //
    }
    
    public function before($user, $ability)
    {
        if (isSuperUser($user)) {
            return true;
        }

        return null;
    }
}
