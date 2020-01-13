<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait LivewireAuth
{
    use AuthorizesRequests;

    /**
     * @var array
     */
    public $allowedRoles = ['admin'];

    /**
     * Throws auth exception if user is not authenticated.
     *
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function hydrate()
    {
        if (auth()->guest()) {
            throw new AuthenticationException();
        }

        $this->authorize('by-roles', [$this->allowedRoles]);

        if (method_exists($this, 'extraHydrate')) {
            $this->extraHydrate();
        }
    }
}
