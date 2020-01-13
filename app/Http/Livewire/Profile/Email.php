<?php

namespace App\Http\Livewire\Profile;

use App\Rules\PasswordCheckRule;
use Illuminate\Auth\AuthenticationException;
use Livewire\Component;

class Email extends Component
{
    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $email;

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
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'current_password' => [
                'required',
                new PasswordCheckRule($this->user),
            ],
        ]);

        $this->user->newEmail($this->email);

        $this->dispatchBrowserEvent('flash', [
            'level' => 'alert-success',
            'message' => "Confirmation email was sent to {$this->email}. Please verify your new email address.",
        ]);

        $this->dispatchBrowserEvent('close');
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.email');
    }
}
