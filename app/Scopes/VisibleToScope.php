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

        if ($user->isAdmin()) {
            return $builder;
        }

        if (! Schema::hasColumn($model->getTable(), AppServiceProvider::OWNER_FIELD)) {
            return $builder;
        }

        return $builder->where(AppServiceProvider::OWNER_FIELD, $user->id);
    }
}
