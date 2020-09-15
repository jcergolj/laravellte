<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Rules\OwnerRestrictedRule;
use App\Rules\PermissionExistsRule;
use App\ViewModels\SaveRoleViewModel;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditRoleComponent extends Component
{
    use LivewireAuth;

    /** @var \App\Models\Role */
    public $role;

    /** @var string */
    public $routeName = 'roles.edit';

    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $permissions;

    /**
     * Component mount.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function mount(Role $role)
    {
        $this->permissions = SaveRoleViewModel::buildRolePermissions($role->id);

        $this->role = $role;
        $this->name = $role->name;
        $this->label = $role->label;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('roles.edit', [
            'role' => $this->role,
            'permissionGroups' => SaveRoleViewModel::groupPermissions($this->permissions),
        ])->extends('layouts.app');
    }

    /**
     * Update existing role.
     *
     * @return void
     */
    public function update()
    {
        $this->runValidation();

        $this->role->update([
            'name' => $this->name,
            'label' => $this->label,
        ]);

        if (! $this->role->isAdmin()) {
            $this->role->updatePermissions($this->permissions);
        }

        msg_success('Role has been successfully updated.');

        return redirect()->route('roles.index');
    }

    /**
     * Run validation.
     *
     * @return \Illuminate\Http\Response
     */
    private function runValidation()
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('roles')->ignore($this->role->id ?? ''),
            ],
            'label' => [
                'required',
            ],
            'permissions.*.allowed' => [
                'boolean',
                new PermissionExistsRule(),
            ],
            'permissions.*.owner_restricted' => [
                'boolean',
                new OwnerRestrictedRule($this->permissions),
            ],
        ], [], $this->attributes());
    }

    /**
     * Rename attributes.
     *
     * @return array
     */
    private function attributes()
    {
        $attributes = [];
        $iteration = 1;

        foreach ($this->permissions as $id => $permission) {
            $attributes["permissions.$id.allowed"] = "Permission in row $iteration";
            $attributes["permissions.$id.owner_restricted"] = "Owner Restricted in row $iteration";
            $iteration++;
        }

        return $attributes;
    }
}
