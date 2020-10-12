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

        if (! isset($this->permissionName)) {
            $splitted = explode('-', self::getName());
            $this->permissionName = Str::plural($splitted[1]).'.'.$splitted[0];
        }

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

        foreach (get_object_vars($this) as $var) {
            if ($var instanceof Model) {
                return $var;
            }
        }
    }
}
