<?php

namespace App\Http\Livewire\Profile;

use App\Http\Livewire\Flashable;
use App\Mail\NewEmailConfirmationMail;
use App\Rules\PasswordCheckRule;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Email extends Component
{
    use Flashable;

    /** @var \App\Models\User */
    public $user;

    /** @var string */
    public $email;

    /** @var string */
    public $currentPassword;

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
        $this->validate($this->validationRules());

        Mail::to($this->email)
            ->send(new NewEmailConfirmationMail($this->user, Carbon::tomorrow(), $this->email));

        $this->dispatchFlashSuccessEvent(
            "Confirmation email was sent to {$this->email}. Please verify your new email address."
        );

        $this->dispatchBrowserEvent('close');

        $this->clearInput();
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

    /**
     * Get the validation rules.
     *
     * @return array
     */
    private function validationRules()
    {
        return [
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'currentPassword' => [
                'required',
                new PasswordCheckRule($this->user),
            ],
        ];
    }

    /**
     * Reset public properties back to empty string.
     *
     * @return void
     */
    private function clearInput()
    {
        $this->email = '';
        $this->currentPassword = '';
    }
}
