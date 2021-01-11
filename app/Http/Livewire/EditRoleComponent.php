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
    use HasLivewireAuth;

    /** @var \App\Models\Role */
    public Role $role;

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
        $this->validate($this->rules(), [], $this->attributes());

        $this->role->save();

        if (! $this->role->isAdmin()) {
            $this->role->updatePermissions($this->permissions);
        }

        msg_success('Role has been successfully updated.');

        return redirect()->route('roles.index');
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'role.name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->role->id),
                'unique:roles,id,'.$this->role->id,
            ],
            'role.label' => [
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
        ];
    }

    /**
     * Rename attributes.
     *
     * @return array
     */
    protected function attributes()
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
