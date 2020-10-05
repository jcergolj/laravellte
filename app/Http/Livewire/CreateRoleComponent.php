<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateRoleComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\Role */
    public $role;

    /** @var array */
    protected $allowedRoles = [];

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('roles.create')->extends('layouts.app');
    }

    /**
     * Store new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->role['name'],
            'label' => $this->role['label'],
        ]);

        msg_success('Role has been successfully created.');

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
                Rule::unique('roles', 'name'),
            ],
            'role.label' => [
                'required',
            ],
        ];
    }
}
