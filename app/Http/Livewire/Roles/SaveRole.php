<?php

namespace App\Http\Livewire\Roles;

use App\Models\Role;
use App\Traits\LivewireAuth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaveRole extends Component
{
    use LivewireAuth;

    public $role;

    public $action;

    public $name;

    public $label;

    public $roles;

    /**
     * Component mount.
     *
     * @param \App\Models\Role  $role
     * @return void
     */
    public function mount(Role $role = null)
    {
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
     * Store new role.
     *
     * @return void
     */
    public function store()
    {
        $this->runValidation();

        Role::create([
            'name' => $this->name,
            'label' => $this->label,
        ]);

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

        msg_success('Role has been successfully updated.');

        return redirect()->route('roles.index');
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.roles.save');
    }

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
        ]);
    }
}
