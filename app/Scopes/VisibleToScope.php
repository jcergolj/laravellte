<?php

namespace App\Scopes;

use App\Providers\AppServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class VisibleToScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (! auth()->hasUser()) {
            return $builder;
        }

        $user = auth()->user();

        if ($this->returnEarly($user)) {
            return $builder;
        }

        if ($this->returnEarlyPermission($user, $model)) {
            return $builder;
        }

        return $builder->where(AppServiceProvider::OWNER_FIELD, $user->id);
    }

    /**
     * Should we return early form global scope base on permission and owner field.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function returnEarlyPermission($user, $model)
    {
        $permission = $user->getPermission($model->getTable().'.index');

        if (! $permission->pivot->owner_restricted === true) {
            return true;
        }

        if (! Schema::hasColumn($model->getTable(), AppServiceProvider::OWNER_FIELD)) {
            return true;
        }

        return false;
    }

    /**
     * Should we return early form global scope.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function returnEarly($user)
    {
        if ($user === null) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
