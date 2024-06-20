<?php

namespace Pearls\Modules\Slider\Policies;

use Pearls\Modules\Slider\Models\Slider;
use Illuminate\Auth\Access\Response;
use Pearls\User\Models\User;

class SliderPolicy
{
    public function view(User $user): bool
    {
        if ($user->can('Slider::slider.view')) {
            return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('Slider::slider.create');
    }

    public function update(User $user, Slider $slider): bool
    {
        if ($user->can('Slider::slider.update')) {
            return true;
        }
        return false;
    }

    public function destroy(User $user, Slider $slider): bool
    {
        if ($user->can('Slider::slider.delete')) {
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