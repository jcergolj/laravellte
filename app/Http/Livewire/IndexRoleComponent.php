<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class IndexRoleComponent extends Component
{
    use Table, LivewireAuth;

    /** @var string */
    public $sortField = 'name';

    /** @var string */
    public $routeName = 'roles.index';

    /** @var array */
    protected $queryString = ['perPage', 'sortField', 'sortDirection', 'search'];

    /** @var array */
    protected $listeners = ['destroy' => 'destroy'];

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $roles = Role::filter([
            'search' => $this->search,
            'orderByField' => [$this->sortField, $this->sortDirection],
        ])->paginate($this->perPage);

        return view('roles.index', ['roles' => $roles])
            ->extends('layouts.app');
    }

    /**
     * Delete role.
     *
     * @param  string  $roleId
     * @return void
     */
    public function destroy($roleId)
    {
        $role = Role::findOrFail($roleId);

        if ($role->isAdmin()) {
            $this->dispatchFlashDangerEvent('Admin role cannot be deleted.');

            return;
        }

        User::where('role_id', $roleId)->delete();

        $role->delete();

        $this->dispatchFlashSuccessEvent('Role has been successfully deleted.');
    }
}
