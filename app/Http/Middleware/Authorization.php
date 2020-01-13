<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('for-route', $request->route()->getName());

        return $next($request);
    }
}
