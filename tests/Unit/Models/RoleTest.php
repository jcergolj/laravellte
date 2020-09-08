<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Models\Role */
class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_id_is_casted()
    {
        $role = new Role();
        $this->assertSame('integer', $role->getCasts()['id']);
    }

    /** @test */
    public function create_permissions()
    {
        $role = RoleFactory::new()->create();

        $permissionCreateUser = PermissionFactory::new()->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = PermissionFactory::new()->create([
            'description' => 'Edit User',
        ]);

        $permissions = [
            $permissionCreateUser->id => [
                'allowed' => true, 'owner_restricted' => false,
            ],
            $permissionEditUser->id => [
                'allowed' => false, 'owner_restricted' => false,
            ],
        ];

        $role->createPermissions($permissions);

        $this->assertCount(
            1,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionCreateUser->id)
                ->where('owner_restricted', false)
                ->get()
        );

        $this->assertCount(
            0,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionEditUser->id)
                ->where('owner_restricted', true)
                ->get()
        );
    }

    /** @test */
    public function update_permissions()
    {
        $permissionCreateRole = PermissionFactory::new()->create([
            'description' => 'Create Role',
        ]);

        $permissionEditRole = PermissionFactory::new()->create([
            'description' => 'Edit Role',
        ]);

        $role = RoleFactory::new()->create();

        $role->permissions()->attach($permissionCreateRole->id, ['owner_restricted' => true]);
        $role->permissions()->attach($permissionEditRole->id, ['owner_restricted' => false]);

        $permissionCreateUser = PermissionFactory::new()->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = PermissionFactory::new()->create([
            'description' => 'Edit User',
        ]);

        $permissions = [
            $permissionCreateRole->id => [
                'allowed' => true,
                'owner_restricted' => false,
            ],
            $permissionCreateUser->id => [
                'allowed' => true,
                'owner_restricted' => true,
            ],
            $permissionEditUser->id => [
                'allowed' => false,
                'owner_restricted' => false,
            ],
        ];

        $role->updatePermissions($permissions);

        $this->assertCount(
            0,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionEditRole->id)
                ->get()
        );

        $this->assertCount(
            1,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionCreateRole->id)
                ->where('owner_restricted', false)
                ->get()
        );

        $this->assertCount(
            1,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionCreateUser->id)
                ->where('owner_restricted', true)
                ->get()
        );

        $this->assertCount(
            0,
            PermissionRole::where('role_id', $role->id)
                ->where('permission_id', $permissionEditUser->id)
                ->get()
        );
    }

    /** @test */
    public function has_permission()
    {
        $role = RoleFactory::new()->create();
        $this->assertFalse($role->hasPermission('users.index'));

        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.index',
            'description' => 'index',
        ]));

        $this->assertTrue($role->hasPermission('users.index'));
    }
}
