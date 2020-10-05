<?php

namespace App\Services;

use App\Models\User;

class ForRouteGate
{
    /**
     * Does user have permission for the route.
     *
     * @param  \App\Models\User $user
     * @param  array $roles
     * @return bool
     */
    public function __invoke(User $user, $roles = [])
    {
        if ($user->isAdmin()) {
            return true;
        }

        return in_array($user->role->name, $roles);
    }
}
