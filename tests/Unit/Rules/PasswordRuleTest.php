<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordRule;
use Tests\HasPwnedMock;
use Tests\TestCase;

/** @see \App\Rules\PasswordRule */
class PasswordRuleTest extends TestCase
{
    use HasPwnedMock;

    public function setUp() : void
    {
        parent::setUp();

        $this->mockPwned();
    }

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

    /** @test */
    public function password_must_not_be_pwned()
    {
        $this->mockPwned(false);

        $rule = new PasswordRule('password-not-confirmed');

        $this->assertFalse($rule->passes('password', 'password'));
    }
}
