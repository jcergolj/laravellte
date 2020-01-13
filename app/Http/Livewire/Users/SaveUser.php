<?php

namespace App\Http\Livewire\Users;

use App\Mail\InvitationMail;
use App\Models\Role;
use App\Models\User;
use App\Traits\LivewireAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaveUser extends Component
{
    use LivewireAuth;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $role_id;

    /**
     * \Illuminate\Database\Eloquent\Collection.
     */
    public $roles;

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\User|nullable  $user
     * @return void
     */
    public function mount(Request $request, User $user = null)
    {
        $this->routeName = $request->route()->getName();

        if ($user->id === null) {
            $this->action = 'store';

            return;
        }

        $this->action = 'update';
        $this->user = $user;
        $this->email = $user->email;
        $this->role_id = $user->role->id;
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

    /**
     * Store new user.
     *
     * @return void
     */
    public function store()
    {
        $attributes = $this->runValidation();

        $user = User::create($attributes);

        msg_success('User has been successfully created.');

        Mail::to($user)
            ->queue(new InvitationMail($user, Carbon::tomorrow()));

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
            'role_id' => $this->role_id,
        ]);

        msg_success('User has been successfully updated.');

        return redirect()->route('users.index');
    }

    private function runValidation()
    {
        return $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id ?? ''),
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ]);
    }
}
