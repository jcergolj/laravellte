<?php

namespace Tests\Unit\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/** @see \App\Providers\AuthServiceProvider */
class AuthServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_not_authorized()
    {
        $this->assertFalse(Gate::allows('for-route', ['admin']));
    }

    /** @test */
    public function admin_is_allowed()
    {
        $this->actingAs(create_admin());

        $this->assertTrue(Gate::allows('for-route', []));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_he_does_not_have_role()
    {
        $this->actingAs(factory(User::class)->create());

        $this->assertFalse(Gate::allows('for-route', ['users.index']));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_his_role_does_not_have_permission()
    {
        $this->actingAs(create_user());
        $this->assertFalse(Gate::allows('for-route', ['users.index']));
    }

    /** @test */
    public function user_can_proceed_if_his_role_has_permissions()
    {
        $user = create_user();
        $role = Role::find($user->role_id);
        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.index',
            'description' => 'index',
        ]));

        $this->actingAs($user);

        $this->assertTrue(Gate::allows('for-route', ['users.index']));
    }

    /** @test */
    public function create_is_replaced_with_store_route_name()
    {
        $user = create_user();
        $role = Role::find($user->role_id);
        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.store',
            'description' => 'store',
        ]));

        $this->actingAs($user);

        $this->assertTrue(Gate::allows('for-route', ['users.create']));
    }

    /** @test */
    public function edit_is_replaced_with_update_route_name()
    {
        $user = create_user();
        $role = Role::find($user->role_id);
        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.update',
            'description' => 'update',
        ]));

        $this->actingAs($user);

        $this->assertTrue(Gate::allows('for-route', ['users.edit']));
    }
}
