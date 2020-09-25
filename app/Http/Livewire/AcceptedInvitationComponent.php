<?php

namespace App\Http\Livewire;

use App\Http\HasAcceptedInvitationAuth;
use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use Livewire\Component;

class AcceptedInvitationComponent extends Component
{
    use HasAcceptedInvitationAuth;

    /**
     * @var \App\Models\User
     */
    public $user;

    /** @var string */
    public $newPassword;

    /** @var string */
    public $newPasswordConfirmation;

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function mount(Request $request)
    {
        $this->user = User::find($request->user);
        $this->authorizeInvitation($request, $this->user);
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('accepted-invitations.create')
            ->extends('layouts.guest-app');
    }

    /**
     * Submit the form.
     *
     * @return void
     */
    public function submit()
    {
        $this->validate(['newPassword' => [new PasswordRule($this->newPasswordConfirmation)]]);
        $this->user->savePassword($this->newPassword);

        auth()->login($this->user);

        return redirect()->to('/home');
    }
}
