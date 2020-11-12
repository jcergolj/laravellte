<?php

namespace App\Http\Livewire;

use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;

class DeleteUserComponent extends Component
{
    use CanFlash, HasLivewireAuth;

    /** @var \App\Models\User */
    public $user;

    public function render()
    {
        return view('users.delete');
    }

    /**
     * Delete user.
     *
     * @return void
     */
    public function destroy()
    {
        if (auth()->user()->isHimself($this->user)) {
            throw new AuthorizationException();
        }

        $this->dispatchFlashSuccessEvent('User has been successfully deleted.');

        $this->user->delete();

        $this->emit('entity-deleted');
    }
}
