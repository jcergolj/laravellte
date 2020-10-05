<?php

namespace Tests\Unit\Services;

use App\Services\ForRouteGate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Services\ForRouteGate */
class ForRouteGateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_is_always_allowed_to_proceed()
    {
        $this->assertTrue((new ForRouteGate)(create_admin()));
    }

    /** @test */
    public function user_can_proceed_if_he_has_the_role()
    {
        $this->assertTrue((new ForRouteGate)(create_user(), ['manager']));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_he_does_not_have_role()
    {
        $this->assertFalse((new ForRouteGate)(create_user(), ['writer']));
    }
}
