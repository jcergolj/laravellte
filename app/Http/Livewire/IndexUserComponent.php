<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;

class IndexUserComponent extends Component
{
    use Table, LivewireAuth;

    /** @var string */
    public $sortField = 'email';

    /** @var string */
    public $roleId = '';

    /** @var array */
    protected $queryString = [
        'perPage',
        'sortField',
        'sortDirection',
        'search',
        'roleId',
    ];

    /** @var array */
    protected $listeners = ['destroy' => 'destroy'];

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $users = User::with('role')
            ->filter([
                'orderByField' => [$this->sortField, $this->sortDirection],
                'search' => $this->search,
                'roleId' => $this->roleId,
            ])->paginate($this->perPage);

        $roles = Role::orderBy('name')->get();

        return view('users.index', ['users' => $users, 'roles' => $roles])
            ->extends('layouts.app');
    }

    /**
     * Delete user.
     *
     * @param  string  $userId
     * @return void
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        if (auth()->user()->isHimself($user)) {
            throw new AuthorizationException();
        }

        $this->dispatchFlashSuccessEvent('User has been successfully deleted.');

        $user->delete();
    }
}
