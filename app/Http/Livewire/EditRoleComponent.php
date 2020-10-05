<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditRoleComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\Role */
    public Role $role;

    /**
     * Component mount.
     *
     * @return void
     */
    public function mount()
    {
        $this->model = $this->role;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('roles.edit')->extends('layouts.app');
    }

    /**
     * Update existing role.
     *
     * @return void
     */
    public function update()
    {
        $this->validate($this->validationRules());

        $this->role->save();

        msg_success('Role has been successfully updated.');

        return redirect()->route('roles.index');
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function validationRules()
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
        ];
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
            ],
            'role.label' => [
                'required',
            ],
        ];
    }
}
