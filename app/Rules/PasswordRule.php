<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordRule implements ImplicitRule
{
    /** @var string */
    protected $confirmationValue;

    /** @var string */
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @param  string|null  $confirmationValue
     * @return void
     */
    public function __construct($confirmationValue = null)
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
        $rules[$attribute] = ['required', 'string', 'min:8'];
        $data = [$attribute => $value];

        if ($this->confirmationValue !== null) {
            array_push($rules[$attribute], 'confirmed');
            $data[$attribute.'_confirmation'] = $this->confirmationValue;
        }

        $validator = Validator::make($data, $rules);

        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            $this->message = $validator->getMessageBag()->first();

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
