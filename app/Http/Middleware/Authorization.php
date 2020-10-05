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
     * @param  string  $rolesString
     * @return mixed
     */
    public function handle($request, $next, $rolesString)
    {
        Gate::authorize('for-route', [preg_split('/\|/', $rolesString)]);

        return $next($request);
    }
}
