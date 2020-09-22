<?php

namespace Tests\Unit\Providers;

use App\Providers\AuthServiceProvider;
use App\Services\ForRouteGate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/** @see \App\Providers\AuthServiceProvider */
class AuthServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function for_route_gate_are_called()
    {
        Gate::shouldReceive('define')
            ->with('for-route', ForRouteGate::class)
            ->once();

        $authServiceProvider = new AuthServiceProvider(app());

        $authServiceProvider->boot();
    }
}
