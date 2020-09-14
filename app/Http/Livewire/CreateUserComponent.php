<?php

namespace App\Http\Livewire;

use App\Mail\InvitationMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateUserComponent extends Component
{
    use LivewireAuth;

    /** @var string */
    public $email;

    /** @var string */
    public $roleId;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function mount(Request $request)
    {
        $this->routeName = $request->route()->getName();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->roles = Role::orderBy('name')->get();

        return view('users.create')
            ->extends('layouts.app');
    }

    /**
     * Store new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->runValidation();

        $user = User::create([
            'email' => $this->email,
            'role_id' => $this->roleId,
        ]);

        msg_success('User has been successfully created.');

        Mail::to($user)
            ->queue(new InvitationMail($user, Carbon::tomorrow()));

        return redirect()->route('users.index');
    }

    private function runValidation()
    {
        return $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
            'roleId' => [
                'required',
                'exists:roles,id',
            ],
        ]);
    }
}
