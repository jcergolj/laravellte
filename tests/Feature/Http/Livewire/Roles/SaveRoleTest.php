<?php

namespace Tests\Feature\Http\Livewire\Roles;

use App\Http\Livewire\Roles\SaveRole;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\LivewireAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Roles\SaveRole
 */
class SaveRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User;
     */
    private $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function assert_create_user_has_livewire_auth_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(SaveRole::class));
    }

    /** @test */
    public function role_create_page_contains_save_role_livewire_component()
    {
        $this->actingAs($this->admin)
            ->get(route('roles.create'))
            ->assertSeeLivewire('roles.save-role');
    }

    /** @test */
    public function role_edit_page_contains_save_role_livewire_component()
    {
        $role = factory(Role::class)->create();

        $this->actingAs($this->admin)
            ->get(route('roles.edit', $role))
            ->assertSeeLivewire('roles.save-role');
    }

    /** @test */
    public function render_for_store()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->assertSee('Save')
            ->assertSet('action', 'store')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_for_edit()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->assertSet('name', $role->name)
            ->assertSet('label', $role->label)
            ->assertSet('action', 'update')
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store_new_role()
    {
        $permissionCreateUser = factory(Permission::class)->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = factory(Permission::class)->create([
            'description' => 'Edit User',
        ]);

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->set('name', 'manager')
            ->set('label', 'Manager')
            ->set('permissions.1.allowed', true)
            ->set('permissions.1.owner_restricted', true)
            ->set('permissions.2.allowed', true)
            ->set('permissions.2.owner_restricted', false)
            ->call('store')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $roles = Role::where('name', 'manager')
            ->where('label', 'Manager')
            ->get();

        $this->assertCount(1, $roles);

        $permissions = $roles[0]->permissions;

        $this->assertSame($roles[0]->id, $permissions[0]->pivot->role_id);
        $this->assertSame($permissionCreateUser->id, $permissions[0]->pivot->permission_id);
        $this->assertTrue($permissions[0]->pivot->owner_restricted);

        $this->assertSame($roles[0]->id, $permissions[1]->pivot->role_id);
        $this->assertSame($permissionEditUser->id, $permissions[1]->pivot->permission_id);
        $this->assertFalse($permissions[1]->pivot->owner_restricted);
    }

    /** @test */
    public function update_existing_role()
    {
        $role = factory(Role::class)->create([
            'name' => 'manager',
            'label' => 'Manager',
        ]);

        $permissionCreateUser = factory(Permission::class)->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = factory(Permission::class)->create([
            'description' => 'Edit User',
        ]);

        $permissionCreateRole = factory(Permission::class)->create([
            'description' => 'Create Role',
        ]);

        $permissionEditRole = factory(Permission::class)->create([
            'description' => 'Edit Role',
        ]);

        $role->permissions()->attach($permissionCreateUser->id, ['owner_restricted' => true]);
        $role->permissions()->attach($permissionEditUser->id, ['owner_restricted' => false]);

        // one role is from creating admin user
        $this->assertCount(2, Role::all());

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set('name', 'writer')
            ->set('label', 'Writer')
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
    public function see_existing_role_data()
    {
        $role = factory(Role::class)->create([
            'name' => 'manager',
            'label' => 'Manager',
        ]);

        $permissionCreateUser = factory(Permission::class)->create([
            'description' => 'Create User',
        ]);

        $role->permissions()->attach($permissionCreateUser->id, ['owner_restricted' => true]);

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->assertSet('name', 'manager')
            ->assertSet('label', 'Manager');
    }

    /** @test */
    public function updated_admin_role_does_not_have_any_permission()
    {
        //admin role is created when admin user is created

        $permissionCreateUser = factory(Permission::class)->create([
             'description' => 'Create User',
         ]);

        $this->assertCount(1, Role::where('name', 'admin')->get());

        $role = Role::where('name', 'admin')->first();
        Livewire::actingAs($this->admin)
             ->test(SaveRole::class, ['role' => $role])
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
    public function test_store_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('store')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $role = factory(Role::class)->create();
        factory(Permission::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test name is required' => ['name', '', 'required'],
            'Test name must be unique' => ['name', 'admin', 'unique'],
            'Test label is required' => ['label', '', 'required'],
            'Test allowed must be a boolean' => ['permissions.1.allowed', 'string', 'boolean'],
            'Test owner restricted must be a boolean' => ['permissions.1.owner_restricted', 'string', 'boolean'],
        ];
    }

    /** @test*/
    public function permission_must_be_allowed_if_owner_restricted_is_checked()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set('permissions.1.allowed', false)
            ->set('permissions.1.owner_restricted', true)
            ->call('update')
            ->assertHasErrors(['permissions.1.owner_restricted' => 'illuminate\_validation\_closure_validation_rule']);
    }

    /** @test */
    public function unique_name_is_ignored_for_role_who_is_edited()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set('label', 'something')
            ->call('update')
            ->assertHasNoErrors('label');

        $this->assertSame('something', $role->fresh()->label);
    }
}
