<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Authorization;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/** @see \App\Http\Middleware\Authenticate; */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_not_authorized()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        Gate::shouldReceive('authorize')
            ->with('for-route', 'users.index')
            ->once()
            ->andThrow(AuthorizationException::class);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () {
            return new Route('GET', 'users', [
                'as' => 'users.index',
            ]);
        });

        $middleware->handle($request, function ($request) {
        });
    }

    /** @test */
    public function user_without_permission_is_not_authorized()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        Gate::shouldReceive('authorize')
            ->with('for-route', 'users.index')
            ->once()
            ->andThrow(AuthorizationException::class);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () {
            return new Route('GET', 'users', [
                'as' => 'users.index',
            ]);
        });

        $this->be(create_user());

        $middleware->handle($request, function ($request) {
        });
    }

    /** @test */
    public function user_with_permission_is_authorized()
    {
        Gate::shouldReceive('authorize')
            ->with('for-route', 'users.index')
            ->once()
            ->andReturn(true);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () {
            return new Route('GET', 'users', [
                'as' => 'users.index',
            ]);
        });

        $next = new class {
            /** @var bool */
            public $called = false;

            public function __invoke($request)
            {
                $this->called = true;

                return $request;
            }
        };

        $user = create_user();
        $role = Role::find($user->role_id);
        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.index',
            'description' => 'index',
        ]));

        $this->be($user->fresh());
        $response = $middleware->handle($request, $next);

        $this->assertTrue($next->called);
        $this->assertSame($response, $request);
    }

    /**
     * @test
     * @dataProvider usersRoutesProvider
     * @dataProvider rolesRoutesProvider
     */
    public function authenticate_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('authorization', $this->getMiddlewareFor($route));
    }

    public function usersRoutesProvider()
    {
        return [
            'Route users.index doesn\'t have authenticate middleware.' => ['users.index'],
            'Route users.create doesn\'t have authenticate middleware.' => ['users.create'],
            'Route users.edit doesn\'t have authenticate middleware.' => ['users.edit'],
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
