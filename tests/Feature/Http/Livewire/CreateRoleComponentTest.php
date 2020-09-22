<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\CreateRoleComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Role;
use Database\Factories\PermissionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\CreateRoleComponent */
class CreateRoleComponentTest extends TestCase
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
    public function assert_create_role_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(CreateRoleComponent::class));
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(CreateRoleComponent::class)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        $permissionCreateUser = PermissionFactory::new()->create([
            'description' => 'Create User',
        ]);

        $permissionEditUser = PermissionFactory::new()->create([
            'description' => 'Edit User',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CreateRoleComponent::class)
            ->set('role.name', 'manager')
            ->set('role.label', 'Manager')
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

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_store_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->admin)
            ->test(CreateRoleComponent::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('store')
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
}
