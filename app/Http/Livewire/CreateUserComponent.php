<?php

namespace App\Http\Livewire;

use App\Mail\InvitationMail;
use App\Models\Role;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateUserComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\User */
    public $user;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

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
        $this->validate();

        $user = User::create([
            'email' => $this->user['email'],
            'role_id' => $this->user['role_id'],
            AppServiceProvider::OWNER_FIELD => auth()->id(),
        ]);

        msg_success('User has been successfully created.');

        Mail::to($user)
            ->queue(new InvitationMail($user, Carbon::tomorrow()));

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
                Rule::unique('users', 'email'),
            ],
            'user.role_id' => [
                'required',
                Rule::exists('roles', 'id'),
            ],
        ];
    }
}
