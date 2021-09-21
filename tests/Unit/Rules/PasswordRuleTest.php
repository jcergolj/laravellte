<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordRule;
use Tests\TestCase;

/** @see \App\Rules\PasswordRule */
class PasswordRuleTest extends TestCase
{
    /** @var string */
    private $password;

    public function setUp() : void
    {
        parent::setUp();
        $this->password = password_generator();
    }

    /** @test */
    public function validation_passes()
    {
        $rule = new PasswordRule($this->password);

        $this->assertTrue($rule->passes('password', $this->password));
    }

    /** @test */
    public function password_is_required()
    {
        $rule = new PasswordRule('');

        $this->assertFalse($rule->passes('password', ''));
    }

    /** @test */
    public function password_must_be_at_least_x_char_in_length()
    {
        $password = too_short_password();
        $rule = new PasswordRule($password);

        $this->assertFalse($rule->passes('password', $password));
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $rule = new PasswordRule($this->password.'-not-confirmed');

        $this->assertFalse($rule->passes('password', $this->password));
    }

    /** @test */
    public function password_must_be_uncompromised()
    {
        $rule = new PasswordRule('password');

        $this->assertFalse($rule->passes('password', 'password'));
    }
}
