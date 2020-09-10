<?php

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\Table;
use App\Models\Role;
use App\Models\User;

class TableRole extends Table
{
    /** @var string */
    public $sortField = 'name';

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

        return view('livewire.roles.table-role', ['roles' => $roles]);
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
