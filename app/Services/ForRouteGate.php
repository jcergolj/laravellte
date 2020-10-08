<?php

namespace App\Services;

use App\Models\User;
use App\Providers\AppServiceProvider;

class ForRouteGate
{
    /**
     * Does user have permission for the route.
     *
     * @param  \App\Models\User $user
     * @param  array $allowedRoles
     * @param  mixed $model
     * @return bool
     */
    public function __invoke(User $user, $allowedRoles = [], $model = null)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($allowedRoles === []) {
            return false;
        }

        return $this->forOwner($user, $allowedRoles, $model);
    }

    /**
     * Get permission for non-admin role where value of owner restricted fields is important.
     *
     * @param  \App\Models\User $user
     * @param  array $allowedRoles
     * @param  mixed $model
     * @return bool
     */
    public function forOwner($user, $allowedRoles, $model)
    {
        if (! in_array($user->role->name, $allowedRoles)) {
            return false;
        }

        if ($model === null) {
            return true;
        }

        $ownerField = AppServiceProvider::OWNER_FIELD;

        if ($model->$ownerField === null) {
            return true;
        }

        return $user->id === $model->$ownerField;
    }
}
