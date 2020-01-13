<?php

namespace Tests\Unit\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * @see \App\Providers\AuthServiceProvider
 */
class AuthServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_is_not_authorized()
    {
        $this->assertFalse(Gate::allows('by-roles', ['admin']));
    }

    /**
     * @test
     */
    public function user_is_not_allowed_to_proceed_if_he_does_not_have_the_role()
    {
        $this->actingAs(create_admin());

        $this->assertFalse(Gate::allows('by-roles', [['random-roles']]));
    }

    /**
     * @test
     */
    public function user_can_proceed_if_he_has_the_role()
    {
        $this->actingAs(create_admin());

        $this->assertTrue(Gate::allows('by-roles', [['admin']]));
    }
}
