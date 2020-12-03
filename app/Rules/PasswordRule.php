<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordRule implements ImplicitRule
{
    protected $rules = ['required', 'string', 'min:8'];

    /** @var string */
    private $message = '';

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
        return $this->validate($attribute, [$attribute => $value], $this->rules);
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

    /**
     * Make validator and perform validation.
     *
     * @param  string  $attribute
     * @param  array  $attributes
     * @param  array  $rules
     * @return bool
     */
    protected function validate($attribute, $attributes, $rules)
    {
        $validator = Validator::make($attributes, [
            $attribute => $rules,
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            $this->message = $validator->getMessageBag()->first();

            return false;
        }

        return true;
    }
}
