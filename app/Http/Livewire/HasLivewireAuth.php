<?php

namespace App\Http\Livewire;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasLivewireAuth
{
    use AuthorizesRequests;

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

        // set models for edit, show, delete
        // if admin allow
        // if model has owner_id field auth user should be owner
        // else allow to anyone

        $this->authorize('for-route', [$this->allowedRoles ?? []]);
    }
}
