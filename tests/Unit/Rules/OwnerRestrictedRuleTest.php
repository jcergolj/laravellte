<?php

namespace Tests\Unit\Rules;

use App\Rules\OwnerRestrictedRule;
use PHPUnit\Framework\TestCase;

/** @see \App\Rules\OwnerRestrictedRule */
class OwnerRestrictedRuleTest extends TestCase
{
    /** @test */
    public function owner_restricted_param_can_be_selected_only_if_permission_is_selected()
    {
        $permissions = [
            1 => [
                'allowed' => true,
            ],
        ];

        $rule = new OwnerRestrictedRule($permissions);

        $this->assertTrue($rule->passes('permissions.1.owner_restricted', true));
        $this->assertTrue($rule->passes('permissions.1.owner_restricted', false));

        $permissions = [
            1 => [
                'allowed' => false,
            ],
        ];

        $rule = new OwnerRestrictedRule($permissions);

        $this->assertTrue($rule->passes('permissions.1.owner_restricted', false));
    }

    /** @test */
    public function owner_restricted_param_cannot_be_selected_if_permission_is_not_selected()
    {
        $permissions = [
            1 => [
                'allowed' => false,
            ],
        ];

        $rule = new OwnerRestrictedRule($permissions);

        $this->assertFalse($rule->passes('permissions.1.owner_restricted', true));
    }
}
