<?php

namespace App\Http\Livewire;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait LivewireAuth
{
    use AuthorizesRequests;

    /** @var string|null */
    public $routeName = null;

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

        $this->authorize('for-route', [$this->routeName]);
    }
}
