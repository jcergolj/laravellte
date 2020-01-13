<?php

namespace Tests\Unit\Rules;

use App\Models\Permission;
use App\Rules\PermissionExistsRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Rules\PermissionExistsRule */
class PermissionExistsRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function permission_must_exists()
    {
        factory(Permission::class)->create();

        $rule = new PermissionExistsRule();

        $this->assertTrue($rule->passes('permissions.1.owner_restricted', '1'));
        $this->assertTrue($rule->passes('permissions.1.owner_restricted', '5'));
    }

    /** @test */
    public function fails_if_permission_does_not_exist()
    {
        factory(Permission::class)->create();

        $rule = new PermissionExistsRule();

        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '1'));
        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '2'));
        $this->assertFalse($rule->passes('permissions.2.owner_restricted', '5'));
    }
}
