<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OwnerRestrictedRule implements Rule
{
    /** @var array */
    protected $permissions;

    /**
     * Create a new rule instance.
     *
     * @param  array $permissions
     * @return void
     */
    public function __construct($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $splitted = preg_split('/\./', $attribute);
        $index = $splitted[1];

        return $value !== true || $this->permissions[$index]['allowed'] !== false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is allowed only when corresponding permission is selected.';
    }
}
