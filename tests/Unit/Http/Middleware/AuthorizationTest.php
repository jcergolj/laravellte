<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Authorization;
use Database\Factories\RoleFactory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
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
            ->with('for-route', [['manager', 'writer'], null])
            ->once()
            ->andThrow(AuthorizationException::class);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () use ($request) {
            $route = new Route('GET', 'users', [
                'as' => 'users.index',
                'controller' => new LivewireComponent(),
            ]);

            $route->bind($request);

            return $route;
        });

        $middleware->handle($request, function ($request) {
        }, 'manager|writer');
    }

    /** @test */
    public function user_without_role_is_not_authorized()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        Gate::shouldReceive('authorize')
            ->with('for-route', [['writer'], null])
            ->once()
            ->andThrow(AuthorizationException::class);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () use ($request) {
            $route = new Route('GET', 'users', [
                'as' => 'users.index',
                'controller' => new LivewireComponent(),
            ]);

            $route->bind($request);

            return $route;
        });

        $this->actingAs(create_user());

        $middleware->handle($request, function ($request) {
        }, 'writer');
    }

    /** @test */
    public function user_with_role_is_authorized()
    {
        Gate::shouldReceive('authorize')
            ->with('for-route', [['manager'], null])
            ->once()
            ->andReturn(true);

        $middleware = new Authorization;

        $request = new Request;

        $request->setRouteResolver(function () use ($request) {
            $route = new Route('GET', 'users', [
                'as' => 'users.index',
                'controller' => new LivewireComponent(),
            ]);

            $route->bind($request);

            return $route;
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

        $role = RoleFactory::new(['name' => 'manager'])
            ->hasUsers(1)
            ->create();

        $this->actingAs($role->users[0]);
        $response = $middleware->handle($request, $next, 'manager');

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

class LivewireComponent extends Component
{
}
