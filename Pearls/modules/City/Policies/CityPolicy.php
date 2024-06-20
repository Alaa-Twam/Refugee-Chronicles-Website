<?php

namespace Pearls\Modules\City\Policies;

use Pearls\Modules\City\Models\City;
use Illuminate\Auth\Access\Response;
use Pearls\User\Models\User;

class CityPolicy
{
    public function view(User $user): bool
    {
        if ($user->can('City::city.view')) {
            return true;
        }
        return false;
    }

    public function update(User $user, City $city): bool
    {
        if ($user->can('City::city.update')) {
            return true;
        }
        return false;
    }

    public function before($user, $ability)
    {
        if (isSuperUser($user)) {
            return true;
        }

        return null;
    }
}