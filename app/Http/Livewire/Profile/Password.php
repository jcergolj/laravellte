<?php

namespace App\Http\Livewire\Profile;

use App\Mails\PasswordChangedMail;
use App\Rules\PasswordCheckRule;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Password extends Component
{
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
     * @var string
     */
    public $current_password;

    /**
     * Throws auth exception if user is not authenticated.
     *
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function hydrate()
    {
        if (auth()->guest()) {
            throw new AuthenticationException();
        }
    }

    /**
     * @return void
     */
    public function mount()
    {
        $this->user = auth()->user();
    }

    /**
     * Submit the form.
     *
     * @return void
     */
    public function submit()
    {
        $this->validate([
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
            ],
            'current_password' => [
                'required',
                new PasswordCheckRule($this->user),
            ],
        ]);

        $this->user->savePassword($this->new_password);

        Mail::to($this->user->email)
            ->send(new PasswordChangedMail());

        $this->dispatchBrowserEvent('close');
        $this->dispatchBrowserEvent('flash', [
            'level' => 'alert-success',
            'message' => 'You password has been successfully updated.',
        ]);
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.password');
    }
}
