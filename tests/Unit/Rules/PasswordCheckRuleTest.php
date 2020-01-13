<?php

namespace Tests\Unit\Rules;

use App\Models\User;
use App\Rules\PasswordCheckRule;
use Tests\TestCase;

/** @see \App\Rules\PasswordCheckRule */
class PasswordCheckRuleTest extends TestCase
{
    /** @test */
    public function provided_password_must_match_auth_user_password()
    {
        $rule = new PasswordCheckRule(new User(['password' => bcrypt('password')]));

        $this->assertTrue($rule->passes('password', 'password'));
        $this->assertFalse($rule->passes('password', 'invalid-password'));
    }
}
