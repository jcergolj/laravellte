<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Rules\OwnerRestrictedRule;
use App\Rules\PermissionExistsRule;
use App\ViewModels\SaveRoleViewModel;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateRoleComponent extends Component
{
    use LivewireAuth;

    /** @var string */
    public $email;

    /** @var string */
    public $roleId;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

    /** @var string */
    public $routeName = 'roles.create';

    /** @var \App\Models\Role */
    public $role;

    /** @var string */
    public $action;

    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $permissions;

    /**
     * Component mount.
     *
     * @return void
     */
    public function mount()
    {
        $this->permissions = SaveRoleViewModel::buildRolePermissions();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('roles.create', [
            'permissionGroups' => SaveRoleViewModel::groupPermissions($this->permissions),
        ])
        ->extends('layouts.app');
    }

    /**
     * Store new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->runValidation();

        $role = Role::create([
            'name' => $this->name,
            'label' => $this->label,
        ]);

        $role->createPermissions($this->permissions);

        msg_success('Role has been successfully created.');

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
