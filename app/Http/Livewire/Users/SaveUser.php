<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use App\Traits\LivewireAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaveUser extends Component
{
    use LivewireAuth;

    public $user;

    public $action;

    public $email;

    public $roleId;

    public $roles;

    /**
     * Component mount.
     *
     * @param \App\Models\User  $user
     * @return void
     */
    public function mount(User $user = null)
    {
        if ($user->id === null) {
            $this->action = 'store';

            return;
        }

        $this->action = 'update';
        $this->user = $user;
        $this->email = $user->email;
        $this->roleId = $user->role->id;
    }

    /**
     * Store new user.
     *
     * @return void
     */
    public function store()
    {
        $this->runValidation();

        User::create([
            'email' => $this->email,
            'password' => Hash::make(rand(100, 10000).time()),
            'role_id' => $this->roleId,
        ]);

        msg_success('User has been successfully created.');

        return redirect()->route('users.index');
    }

    /**
     * Update existing user.
     *
     * @return void
     */
    public function update()
    {
        if ($this->user->isHimself(auth()->user())) {
            throw new AuthorizationException();
        }

        $this->runValidation();

        $this->user->update([
            'email' => $this->email,
            'role_id' => $this->roleId,
        ]);

        msg_success('User has been successfully updated.');

        return redirect()->route('users.index');
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->roles = Role::orderBy('name')->get();

        return view('livewire.users.save');
    }

    private function runValidation()
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id ?? ''),
            ],
           'roleId' => [
               'required',
               'exists:roles,id',
           ],
        ]);
    }
}
