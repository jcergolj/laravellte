<?php

namespace App\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Gate;
use Livewire\ImplicitRouteBinding;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $next
     * @param  array  $roleString
     * @return mixed
     */
    public function handle($request, $next, $rolesString = '')
    {
        $component = $request->route()->action['controller'];

        $model = (new ImplicitRouteBinding(new Container()))->resolveComponentProps(
            $request->route(), new $component()
        );

        Gate::authorize('for-route', [preg_split('/\|/', $rolesString), $model->first() ?? null]);

        return $next($request);
    }
}
