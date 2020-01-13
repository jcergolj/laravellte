<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Rules\PasswordRule;
use App\Traits\AcceptedInvitationAuth;
use Illuminate\Http\Request;
use Livewire\Component;

class AcceptedInvitation extends Component
{
    use AcceptedInvitationAuth;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $new_password;

    /**
     * @var string
     */
    public $new_password_confirmation;

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\User  $user
     * @return void
     */
    public function mount(Request $request, User $user)
    {
        $this->user = User::find($request->user);
        $this->authorizeInvitation($request, $this->user);
    }

    /**
     * Submit the form.
     *
     * @return void
     */
    public function submit()
    {
        $this->validate(['new_password' => [new PasswordRule($this->new_password_confirmation)]]);
        $this->user->savePassword($this->new_password);

        auth()->login($this->user);

        return redirect()->to('/home');
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.accepted-invitation');
    }
}
