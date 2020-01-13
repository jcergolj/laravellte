<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle($request, $next, $roles)
    {
        $roles = explode('|', $roles);

        abort_if(auth()->user() === null, Response::HTTP_UNAUTHORIZED);

        abort_if(! in_array(auth()->user()->role->name, $roles), Response::HTTP_UNAUTHORIZED);

        return $next($request);
    }
}
