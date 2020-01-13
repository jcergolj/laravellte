<?php

namespace App\Http\Livewire\Users;

use App\Filters\UserFilter;
use App\Http\Livewire\Table;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class TableUser extends Table
{
    /**
     * @var string
     */
    public $sortField = 'email';

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $filter = new UserFilter([
            'search' => $this->search,
            'orderBy' => [$this->sortField, $this->sortAsc],
        ]);

        $users = User::with('role')->filter($filter)->paginate($this->perPage);

        return view('livewire.users.table-user', compact('users'));
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

        $user->delete();

        $this->dispatchBrowserEvent('close');
    }
}
