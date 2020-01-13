<?php

namespace Tests\Unit\ViewModels;

use App\Models\Permission;
use App\Models\Role;
use App\ViewModels\SaveRoleViewModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveRoleViewModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function build_role_permissions_with_role()
    {
        $permissionCreateUser = factory(Permission::class)->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = factory(Permission::class)->create([
            'description' => 'Edit User',
        ]);

        $role = factory(Role::class)->create();

        $role->permissions()->attach($permissionCreateUser->id, ['owner_restricted' => true]);

        $assignedPermissions = SaveRoleViewModel::buildRolePermissions($role->id);

        $this->assertSame('Create User', $assignedPermissions[$permissionCreateUser->id]['description']);
        $this->assertTrue($assignedPermissions[$permissionCreateUser->id]['allowed']);
        $this->assertTrue($assignedPermissions[$permissionCreateUser->id]['owner_restricted']);

        $this->assertSame('Edit User', $assignedPermissions[$permissionEditUser->id]['description']);
        $this->assertFalse($assignedPermissions[$permissionEditUser->id]['allowed']);
        $this->assertFalse($assignedPermissions[$permissionEditUser->id]['owner_restricted']);
    }

    /** @test */
    public function build_role_permissions_without_role()
    {
        $permissionCreateUser = factory(Permission::class)->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = factory(Permission::class)->create([
            'description' => 'Edit User',
        ]);

        $assignedPermissions = SaveRoleViewModel::buildRolePermissions();

        $this->assertSame('Create User', $assignedPermissions[$permissionCreateUser->id]['description']);
        $this->assertFalse($assignedPermissions[$permissionCreateUser->id]['allowed']);
        $this->assertFalse($assignedPermissions[$permissionCreateUser->id]['owner_restricted']);

        $this->assertSame('Edit User', $assignedPermissions[$permissionEditUser->id]['description']);
        $this->assertFalse($assignedPermissions[$permissionEditUser->id]['allowed']);
        $this->assertFalse($assignedPermissions[$permissionEditUser->id]['owner_restricted']);
    }

    /** @test */
    public function group_permissions_by_group_name_and_sort()
    {
        $permissionCreateUser = factory(Permission::class)->create([
            'group' => 'users',
            'description' => 'Create User',
        ]);

        $permissionEditUser = factory(Permission::class)->create([
            'group' => 'users',
            'description' => 'Edit User',
        ]);

        $permissionCreateRole = factory(Permission::class)->create([
            'group' => 'roles',
            'description' => 'Create Role',
        ]);

        $permissionEditRole = factory(Permission::class)->create([
            'group' => 'roles',
            'description' => 'Edit Role',
        ]);

        $groupedPermissions = SaveRoleViewModel::groupPermissions(SaveRoleViewModel::buildRolePermissions());

        $this->assertSame('Create User', $groupedPermissions['users'][$permissionCreateUser->id]['description']);
        $this->assertFalse($groupedPermissions['users'][$permissionCreateUser->id]['allowed']);
        $this->assertFalse($groupedPermissions['users'][$permissionCreateUser->id]['owner_restricted']);

        $this->assertSame('Edit User', $groupedPermissions['users'][$permissionEditUser->id]['description']);
        $this->assertFalse($groupedPermissions['users'][$permissionEditUser->id]['allowed']);
        $this->assertFalse($groupedPermissions['users'][$permissionEditUser->id]['owner_restricted']);

        $this->assertSame('Create Role', $groupedPermissions['roles'][$permissionCreateRole->id]['description']);
        $this->assertFalse($groupedPermissions['roles'][$permissionCreateRole->id]['allowed']);
        $this->assertFalse($groupedPermissions['roles'][$permissionCreateRole->id]['owner_restricted']);

        $this->assertSame('Edit Role', $groupedPermissions['roles'][$permissionEditRole->id]['description']);
        $this->assertFalse($groupedPermissions['roles'][$permissionEditRole->id]['allowed']);
        $this->assertFalse($groupedPermissions['roles'][$permissionEditRole->id]['owner_restricted']);

        $this->assertSame(['roles', 'users'], array_keys($groupedPermissions));
    }
}
