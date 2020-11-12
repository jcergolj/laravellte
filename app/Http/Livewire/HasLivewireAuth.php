<?php

namespace App\Http\Livewire;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

trait HasLivewireAuth
{
    use AuthorizesRequests;

    /** @var string */
    public $permissionName;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model = null;

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

        $this->getPermissionName();

        $this->authorize('for-route', [$this->permissionName, $this->getModel()]);
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

        return collect(get_object_vars($this))
            ->filter(function ($variable) {
                return $variable instanceof Model;
            })->first();
    }

    /**
     * Get permission name.
     *
     * @return void
     */
    public function getPermissionName()
    {
        if (isset($this->permissionName)) {
            return;
        }

        $splitted = explode('-', self::getName());
        $this->permissionName = Str::plural($splitted[1]).'.'.$splitted[0];
    }
}
