<?php

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Table;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class TableUser extends Table
{
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

        return view('livewire.users.table-user', ['users' => $users, 'roles' => $roles]);
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
