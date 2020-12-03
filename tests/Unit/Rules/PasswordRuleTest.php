<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordRule;
use Tests\TestCase;

/** @see \App\Rules\PasswordRule */
class PasswordRuleTest extends TestCase
{
    /** @test */
    public function validation_passes()
    {
        $rule = new PasswordRule();

        $this->assertTrue($rule->passes('password', 'password'));
    }

    /** @test */
    public function password_is_required()
    {
        $rule = new PasswordRule();

        $this->assertFalse($rule->passes('password', ''));
    }

    /** @test */
    public function password_must_be_at_least_8_char_in_length()
    {
        $rule = new PasswordRule();

        $this->assertFalse($rule->passes('password', '1234567'));
    }
}
