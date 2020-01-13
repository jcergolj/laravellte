<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;

/**
 * @see \App\Http\Middleware\Authenticate;
 */
class AuthenticateTest extends TestCase
{
    /**
     * @test
     * @dataProvider routesProvider
     */
    public function authenticate_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('auth', $this->getMiddlewareFor($route));
    }

    public function routesProvider()
    {
        return [
            'Route home.index doesn\'t have authenticate middleware.' => ['home.index'],
            'Route profile.users.index doesn\'t have authenticate middleware.' => ['profile.users.index'],
            'Route profile.images.update doesn\'t have authenticate middleware.' => ['profile.images.update'],
        ];
    }
}
