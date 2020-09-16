<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;

class EditUserComponent extends Component
{
    use LivewireAuth;

    /** @var \App\Models\User */
    public User $user;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

    /** @var string */
    public $routeName = 'users.edit';

    /**
     * Component mount.
     *
     * @return void
     */
    public function mount()
    {
        if ($this->user->isHimself(auth()->user())) {
            throw new AuthorizationException();
        }
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->roles = Role::orderBy('name')->get();

        return view('users.edit')
            ->extends('layouts.app');
    }

    /**
     * Update existing user.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $this->validate($this->validationRules());

        $this->user->save();

        msg_success('User has been successfully updated.');

        return redirect()->route('users.index');
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function validationRules()
    {
        return [
            'user.email' => [
                'required',
                'email',
                'unique:users,email,'.$this->user->id,
            ],
            'user.role_id' => [
                'required',
                'exists:roles,id',
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
            'user.email' => [
                'required',
            ],
            'user.role_id' => [
                'required',
            ],
        ];
    }
}
