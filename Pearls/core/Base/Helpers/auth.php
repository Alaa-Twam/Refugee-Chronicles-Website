<?php

if (!function_exists('user')) {

    /**
     * @return Pearls\User\Models\User
     */
    function user()
    {
        return \Auth::user();
    }
}

if (!function_exists('isSuperUser')) {
    function isSuperUser(\Pearls\User\Models\User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        if (!$user) {
            return false;
        }

        $superuser_id = 1;
        $superuser_role_id = 1;

        return $user->id == $superuser_id || $user->roles->contains('id', $superuser_role_id);
    }
}