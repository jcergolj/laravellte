<?php

namespace Tests\Unit\Services;

use App\Exceptions\MissingModel;
use App\Providers\AppServiceProvider;
use App\Services\ForRouteGate;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
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
    public function user_is_not_allowed_to_proceed_if_route_is_empty()
    {
        $this->assertFalse((new ForRouteGate)(create_user()));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_he_does_not_have_role()
    {
        $this->assertFalse((new ForRouteGate)(create_user(), 'users.index'));
    }

    /** @test */
    public function user_can_proceed_if_his_role_has_permissions()
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'users.index']),
            )
            ->create();

        $this->assertTrue((new ForRouteGate)($role->users[0], 'users.index'));
    }

    /**
     * @test
     * @dataProvider permissionRoutesProvider
     */
    public function user_can_proceed_if_his_role_has_permissions_and_he_is_the_owner($route)
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => $route]),
                ['owner_restricted' => true]
            )
            ->create();

        $user = $role->users[0];

        $joe = UserFactory::new()->create([
            AppServiceProvider::OWNER_FIELD => $user,
        ]);

        $this->assertTrue((new ForRouteGate)($user, $route, $joe));
    }

    /**
     * @test
     * @dataProvider permissionRoutesProvider
     */
    public function user_can_proceed_if_his_role_has_permissions_and_owner_restricted_is_false($route)
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => $route]),
                ['owner_restricted' => false]
            )
            ->create();

        $user = $role->users[0];

        $user = $role->users[0];

        $joe = UserFactory::new()->create([
            AppServiceProvider::OWNER_FIELD => $user,
        ]);

        $this->assertTrue((new ForRouteGate)($user, $route, $joe));
    }

    /**
     * @test
     * @dataProvider permissionRoutesProvider
     */
    public function user_cannot_proceed_if_his_role_has_permissions_and_he_is_not_the_owner($route)
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => $route]),
                ['owner_restricted' => true]
            )
            ->create();

        $user = $role->users[0];

        $joe = UserFactory::new()->create([
            AppServiceProvider::OWNER_FIELD => create_user(),
        ]);

        $this->assertFalse((new ForRouteGate)($role->users[0], $route, $joe));
    }

    /**
     * @test
     * @dataProvider permissionRoutesProvider
     */
    public function exception_is_thrown_if_model_is_missing($route)
    {
        $this->expectException(MissingModel::class);

        (new ForRouteGate)(create_user(), $route);
    }

    public function permissionRoutesProvider()
    {
        return [
            'Test for show route failed.' => ['show.user'],
            'Test for edit route failed.' => ['edit.user'],
            'Test for delete route failed.' => ['delete.user'],
        ];
    }
}
