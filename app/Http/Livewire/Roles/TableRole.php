<?php

namespace App\Http\Livewire\Roles;

use App\Filters\RoleFilter;
use App\Http\Livewire\Table;
use App\Models\Role;
use App\Models\User;

class TableRole extends Table
{
    /**
     * @var string
     */
    public $sortField = 'name';

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $filter = new RoleFilter([
            'search' => $this->search,
            'orderBy' => [$this->sortField, $this->sortAsc],
        ]);

        $roles = Role::filter($filter)->paginate($this->perPage);

        return view('livewire.roles.table-role', compact('roles'));
    }

    /**
     * Delete role.
     *
     * @param  string  $roleId
     * @return void
     */
    public function destroy($roleId)
    {
        User::where('role_id', $roleId)->delete();
        Role::findOrFail($roleId)->delete();

        $this->dispatchBrowserEvent('close');
    }
}
