<?php

namespace App\Http\Livewire\Auth;

use App\Http\Livewire\CanFlash;
use App\Models\Role;
use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RegisterNewUserComponent extends Component
{
    use CanFlash;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.auth.register-new-user')
            ->extends('layouts.guest-app');
    }

    /**
     * Store new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        $this->validate();

        $user = User::create([
            'email' => $this->email,
            'role_id' => Role::where('name', config('laravellte.role_name'))->first()->id,
        ]);

        $user->savePassword($this->password);

        $this->dispatchFlashSuccessEvent("Your account has been created. Email is waiting for you in {$this->email} inbox.");

        event(new Registered($user));
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                new PasswordRule(),
            ],
        ];
    }
}
