<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordRule;
use Tests\TestCase;

class PasswordRuleTest extends TestCase
{
    /** @test */
    public function validation_passes()
    {
        $rule = new PasswordRule('password');

        $this->assertTrue($rule->passes('password', 'password'));
    }

    /** @test */
    public function password_is_required()
    {
        $rule = new PasswordRule('');

        $this->assertFalse($rule->passes('password', ''));
    }

    /** @test */
    public function password_must_be_at_least_8_char_in_length()
    {
        $rule = new PasswordRule('1234567');

        $this->assertFalse($rule->passes('password', '1234567'));
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $rule = new PasswordRule('password-not-confirmed');

        $this->assertFalse($rule->passes('password', 'password'));
    }
}
