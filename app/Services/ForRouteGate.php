<?php

namespace App\Services;

use App\Exceptions\MissingModel;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Str;

class ForRouteGate
{
    /**
     * Does user have permission for the route.
     *
     * @param  \App\Models\User $user
     * @param  string $permissionName
     * @param  mixed $model
     * @return bool
     */
    public function __invoke(User $user, $permissionName = '', $model = null)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($permissionName === '' || $permissionName === null) {
            return false;
        }

        if (! Str::contains($permissionName, ['show', 'edit', 'delete'])) {
            return $user->hasPermission($permissionName);
        }

        return $this->forOwner($user, $permissionName, $model);
    }

    /**
     * Get permission for non-admin role where value of owner restricted fields is important.
     *
     * @param  \App\Models\User $user
     * @param  string $permissionName
     * @param  mixed $model
     * @return bool
     */
    public function forOwner($user, $permissionName, $model)
    {
        if ($model === null) {
            throw new MissingModel();
        }

        $ownerField = AppServiceProvider::OWNER_FIELD;

        if ($model->$ownerField === null) {
            return $user->hasPermission($permissionName);
        }

        return $user->isModelOwner($permissionName, $model);
    }
}
