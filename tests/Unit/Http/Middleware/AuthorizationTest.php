<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Authorization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @see \App\Http\Middleware\Authorization;
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_continue()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $middleware = new Authorization();
        $request = new Request();

        $middleware->handle($request, function ($request) {
            $this->fail('Next middleware was called when it should not have been.');
        }, 'admin');
    }

    /** @test */
    public function if_auth_user_does_not_have_role_he_cannot_continue()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $middleware = new Authorization('admin');
        $request = new Request();

        $this->actingAs(factory(User::class)->create());

        $middleware->handle($request, function ($request) {
            $this->fail('Next middleware was called when it should not have been.');
        }, 'admin');
    }

    /** @test */
    public function auth_user_must_have_required_role_in_oder_to_be_able_to_continue()
    {
        $request = new Request();

        $next = new class {
            /**
             * @var bool
             */
            public $called = false;

            public function __invoke($request)
            {
                $this->called = true;

                return $request;
            }
        };

        $user = factory(User::class)->create([
            'role_id' => factory(Role::class)->create([
                'name' => 'manager',
            ]),
        ]);

        $this->actingAs($user);

        $middleware = new Authorization();

        $response = $middleware->handle($request, $next, 'admin|manager');

        $this->assertTrue($next->called);
        $this->assertSame($response, $request);
    }

    /**
     * @test
     * @dataProvider userRoutesProvider
     * @dataProvider roleRoutesProvider
     */
    public function authorization_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('authorization', $this->getMiddlewareFor($route));
    }

    public function userRoutesProvider()
    {
        return [
            sprintf('Route %s doesn\'t have %s middleware.', 'users.index', 'authorization') => ['users.index'],
            sprintf('Route %s doesn\'t have %s middleware.', 'users.create', 'authorization') => ['users.create'],
            sprintf('Route %s doesn\'t have %s middleware.', 'users.edit', 'authorization') => ['users.edit'],
        ];
    }

    public function roleRoutesProvider()
    {
        return [
            sprintf('Route %s doesn\'t have %s middleware.', 'roles.index', 'authorization') => ['roles.index'],
            sprintf('Route %s doesn\'t have %s middleware.', 'roles.create', 'authorization') => ['roles.create'],
            sprintf('Route %s doesn\'t have %s middleware.', 'roles.edit', 'authorization') => ['roles.edit'],
        ];
    }
}
