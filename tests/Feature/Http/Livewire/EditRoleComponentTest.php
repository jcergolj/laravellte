<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\EditRoleComponent;
use App\Models\Role;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\EditUserComponent */
class EditRoleComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    protected $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function render()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->assertSet('role.name', $role->name)
            ->assertSet('role.label', $role->label)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update()
    {
        $role = RoleFactory::new()->create([
            'name' => 'manager',
            'label' => 'Manager',
        ]);

        $permissionCreateUser = PermissionFactory::new()->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = PermissionFactory::new()->create([
            'description' => 'Edit User',
        ]);

        $permissionCreateRole = PermissionFactory::new()->create([
            'description' => 'Create Role',
        ]);

        $permissionEditRole = PermissionFactory::new()->create([
            'description' => 'Edit Role',
        ]);

        $role->permissions()->attach($permissionCreateUser->id, ['owner_restricted' => true]);
        $role->permissions()->attach($permissionEditUser->id, ['owner_restricted' => false]);

        // one role is from creating admin user
        $this->assertCount(2, Role::all());

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('role.name', 'writer')
            ->set('role.label', 'Writer')
            ->set('permissions.1.allowed', true)
            ->set('permissions.1.owner_restricted', true)
            ->set('permissions.2.allowed', false)
            ->set('permissions.2.owner_restricted', false)
            ->set('permissions.3.allowed', false)
            ->set('permissions.3.owner_restricted', false)
            ->set('permissions.4.allowed', true)
            ->set('permissions.4.owner_restricted', false)
            ->call('update')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $roles = Role::where('name', 'writer')
                ->where('label', 'Writer')
                ->get();

        $this->assertCount(1, $roles);

        $this->assertCount(2, Role::all());

        $permissions = $roles[0]->permissions;

        $this->assertCount(2, $permissions);

        $this->assertSame($role->id, $permissions[0]->pivot->role_id);
        $this->assertSame($permissionCreateUser->id, $permissions[0]->pivot->permission_id);
        $this->assertTrue($permissions[0]->pivot->owner_restricted);

        $this->assertSame($roles[0]->id, $permissions[1]->pivot->role_id);
        $this->assertSame($permissionEditRole->id, $permissions[1]->pivot->permission_id);
        $this->assertFalse($permissions[1]->pivot->owner_restricted);
    }

    /** @test */
    public function updated_admin_role_does_not_have_any_permission()
    {
        //admin role is created when admin user is created

        $permissionCreateUser = PermissionFactory::new()->create([
            'description' => 'Create User',
        ]);

        $this->assertCount(1, Role::where('name', 'admin')->get());

        $role = Role::where('name', 'admin')->first();
        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('permissions.1.allowed', true)
            ->set('permissions.1.owner_restricted', true)
            ->call('update')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $this->assertCount(1, Role::all());

        $this->assertCount(0, $role->fresh()->permissions);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $role = RoleFactory::new()->create();
        PermissionFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test name is required' => ['role.name', '', 'required'],
            'Test name must be unique' => ['role.name', 'admin', 'unique'],
            'Test label is required' => ['role.label', '', 'required'],
            'Test allowed must be a boolean' => ['permissions.1.allowed', 'string', 'boolean'],
            'Test owner restricted must be a boolean' => ['permissions.1.owner_restricted', 'string', 'boolean'],
        ];
    }

    /** @test*/
    public function permission_must_be_allowed_if_owner_restricted_is_checked()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('permissions.1.allowed', false)
            ->set('permissions.1.owner_restricted', true)
            ->call('update')
            ->assertHasErrors(['permissions.1.owner_restricted' => 'app\_rules\_owner_restricted_rule']);
    }

    /** @test */
    public function unique_name_is_ignored_for_role_who_is_edited()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('role.label', 'something')
            ->call('update')
            ->assertHasNoErrors('label');

        $this->assertSame('something', $role->fresh()->label);
    }
}
