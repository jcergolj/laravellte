<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class PasswordWithConfirmationRule extends PasswordRule implements ImplicitRule
{
    /** @var string */
    protected $confirmationValue;

    /**
     * Create a new rule instance.
     *
     * @param  string  $confirmationValue
     * @return void
     */
    public function __construct($confirmationValue)
    {
        $this->confirmationValue = $confirmationValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passes($attribute, $value)
    {
        $this->rules[] = 'confirmed';

        $attributes = [
            $attribute => $value,
            $attribute.'_confirmation' => $this->confirmationValue,
        ];

        return $this->validate($attribute, $attributes, $this->rules);
    }
}
