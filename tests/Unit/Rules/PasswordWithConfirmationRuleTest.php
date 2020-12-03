<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordWithConfirmationRule;
use Tests\TestCase;

/** @see \App\Rules\PasswordWithConfirmationRule */
class PasswordWithConfirmationRuleTest extends TestCase
{
    /** @test */
    public function validation_passes()
    {
        $rule = new PasswordWithConfirmationRule('password');

        $this->assertTrue($rule->passes('password', 'password'));
    }

    /** @test */
    public function password_is_required()
    {
        $rule = new PasswordWithConfirmationRule('');

        $this->assertFalse($rule->passes('password', ''));
    }

    /** @test */
    public function password_must_be_at_least_8_char_in_length()
    {
        $rule = new PasswordWithConfirmationRule('');

        $this->assertFalse($rule->passes('password', '1234567'));
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $rule = new PasswordWithConfirmationRule('password-not-confirmed');

        $this->assertFalse($rule->passes('password', 'password'));
    }
}
