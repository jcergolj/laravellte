<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\DeleteRoleComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Role;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\DeleteRoleComponent */
class DeleteRoleComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function assert_delete_role_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(DeleteRoleComponent::class));
    }

    /** @test */
    public function admin_can_delete_role_with_attached_user()
    {
        $role = RoleFactory::new()->hasUsers()->create();

        Livewire::actingAs($this->admin)
            ->test(DeleteRoleComponent::class, ['role' => $role])
            ->call('destroy');

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_can_delete_role()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(DeleteRoleComponent::class, ['role' => $role])
            ->call('destroy')
            ->assertEmitted('entity-deleted')
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_cannot_delete_admin_role()
    {
        $role = RoleFactory::new()->create([
            'name' => 'admin',
        ]);

        Livewire::actingAs($this->admin)
            ->test(DeleteRoleComponent::class, ['role' => $role])
            ->call('destroy')
            ->assertDispatchedBrowserEvent('flash');

        $this->count(1, Role::where('name', 'admin')->get());
    }
}
