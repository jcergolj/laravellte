<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user, $ability) {
            return $user->isAdmin() ? true : null;
        });

        Gate::define('for-route', function ($user, $routeName) {
            $routeName = str_replace(
                ['create', 'edit'],
                ['store', 'update'],
                $routeName
            );

            return $user->hasPermission($routeName);
        });

        $this->registerPolicies();
    }
}
