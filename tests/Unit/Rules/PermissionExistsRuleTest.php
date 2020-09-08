<?php

namespace Tests\Unit\Rules;

use App\Rules\PermissionExistsRule;
use Database\Factories\PermissionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Rules\PermissionExistsRule */
class PermissionExistsRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function permission_must_exists()
    {
        PermissionFactory::new()->create();

        $rule = new PermissionExistsRule();

        $this->assertTrue($rule->passes('permissions.1.owner_restricted', '1'));
        $this->assertTrue($rule->passes('permissions.1.owner_restricted', '5'));
    }

    /** @test */
    public function fails_if_permission_does_not_exist()
    {
        PermissionFactory::new()->create();

        $rule = new PermissionExistsRule();

        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '1'));
        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '2'));
        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '5'));
    }
}
