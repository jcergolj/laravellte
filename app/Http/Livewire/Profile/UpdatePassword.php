<?php

namespace App\Http\Livewire\Profile;

use App\Http\Livewire\CanFlash;
use App\Mail\PasswordChangedMail;
use App\Rules\PasswordCheckRule;
use App\Rules\PasswordRule;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class UpdatePassword extends Component
{
    use CanFlash;

    /** @var \App\Models\User */
    public $user;

    /** @var string */
    public $newPassword;

    /** @var string */
    public $newPasswordConfirmation;

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
     * Component mount.
     *
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

        $this->user->savePassword($this->newPassword);

        Mail::to($this->user->email)
            ->send(new PasswordChangedMail());

        $this->dispatchBrowserEvent('close');

        $this->dispatchFlashSuccessEvent('You password has been successfully updated.');

        $this->clearInput();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.update-password');
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    private function validationRules()
    {
        return [
            'newPassword' => [
                new PasswordRule($this->newPasswordConfirmation),
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
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->currentPassword = '';
    }
}
