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
     * @return mixed
     */
    public function handle($request, $next)
    {
        $component = $request->route()->action['controller'];

        $model = (new ImplicitRouteBinding(new Container()))->resolveComponentProps(
            $request->route(), new $component()
        );

        Gate::authorize('for-route', [$request->route()->getName(), $model->first() ?? null]);

        return $next($request);
    }
}
