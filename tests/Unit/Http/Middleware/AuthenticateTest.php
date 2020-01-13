<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;

/** @see \App\Http\Middleware\Authenticate; */
class AuthenticateTest extends TestCase
{
    /**
     * @test
     * @dataProvider homeRoutesProvider
     * @dataProvider usersRoutesProvider
     * @dataProvider profileRoutesProvider
     * @dataProvider rolesRoutesProvider
     */
    public function authenticate_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('auth', $this->getMiddlewareFor($route));
    }

    public function homeRoutesProvider()
    {
        return [
            'Route home.index doesn\'t have authenticate middleware.' => ['home.index'],
        ];
    }

    public function usersRoutesProvider()
    {
        return [
            'Route users.index doesn\'t have authenticate middleware.' => ['users.index'],
            'Route users.create doesn\'t have authenticate middleware.' => ['users.create'],
            'Route users.edit doesn\'t have authenticate middleware.' => ['users.edit'],
        ];
    }

    public function profileRoutesProvider()
    {
        return [
            'Route profile.users.index doesn\'t have authenticate middleware.' => ['profile.users.index'],
        ];
    }

    public function rolesRoutesProvider()
    {
        return [
            'Route roles.index doesn\'t have authenticate middleware.' => ['roles.index'],
            'Route roles.create doesn\'t have authenticate middleware.' => ['roles.create'],
            'Route roles.edit doesn\'t have authenticate middleware.' => ['roles.edit'],
        ];
    }
}
