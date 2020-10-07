<?php

namespace App\Http\Livewire;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasLivewireAuth
{
    use AuthorizesRequests;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

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

        $this->authorize('for-route', [$this->allowedRoles ?? [], $this->getModel()]);
    }

    /**
     * Get bind model.
     *
     * @return mixed
     */
    public function getModel()
    {
        if (method_exists($this, 'setModel')) {
            return $this->setModel();
        }

        foreach (get_object_vars($this) as $var) {
            if ($var instanceof Model) {
                return $var;
            }
        }
    }
}
