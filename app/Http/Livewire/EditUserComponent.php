<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditUserComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\User */
    public User $user;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

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
        $this->validate($this->rules());

        $this->user->save();

        msg_success('User has been successfully updated.');

        return redirect()->route('users.index');
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
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'user.role_id' => [
                'required',
                'exists:roles,id',
            ],
        ];
    }
}
