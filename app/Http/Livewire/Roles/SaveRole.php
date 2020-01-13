<?php

namespace App\Http\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\LivewireAuth;
use App\ViewModels\SaveRoleViewModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaveRole extends Component
{
    use LivewireAuth;

    /**
     * @var \App\Models\Role;
     */
    public $role;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $permissions;

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Role  $role
     * @return void
     */
    public function mount(Request $request, Role $role = null)
    {
        $this->routeName = $request->route()->getName();

        $this->permissions = SaveRoleViewModel::buildRolePermissions($role->id);

        if ($role->id === null) {
            $this->action = 'store';

            return;
        }

        $this->action = 'update';
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
        return view('livewire.roles.save', [
            'permissionGroups' => SaveRoleViewModel::groupPermissions($this->permissions),
        ]);
    }

    /**
     * Store new role.
     *
     * @return void
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
     * @return void
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
                function ($attribute, $value, $fail) {
                    $splitted = preg_split('/\./', $attribute);

                    if (Permission::find($splitted[1]) === null) {
                        $fail('The selected :attribute is invalid.');
                    }
                },
            ],
            'permissions.*.owner_restricted' => [
                'boolean',
                function ($attribute, $value, $fail) {
                    $splitted = preg_split('/\./', $attribute);
                    $index = $splitted[1];

                    if ($value === true && $this->permissions[$index]['allowed'] === false) {
                        $fail('The :attribute is allowed only when corresponding permission is selected.');
                    }
                },
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
