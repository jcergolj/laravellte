<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class DeleteRoleComponent extends Component
{
    use CanFlash, HasLivewireAuth;

    /** @var \App\Models\Role */
    public $role;

    public function render()
    {
        return view('roles.delete');
    }

    /**
     * Delete role.
     *
     * @return void
     */
    public function destroy()
    {
        if ($this->role->isAdmin()) {
            $this->dispatchFlashDangerEvent('Admin role cannot be deleted.');

            return;
        }

        User::where('role_id', $this->role)->delete();

        $this->role->delete();

        $this->emit('entity-deleted');

        $this->dispatchFlashSuccessEvent('Role has been successfully deleted.');
    }
}
