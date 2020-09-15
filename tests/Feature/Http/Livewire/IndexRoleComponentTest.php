<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\IndexRoleComponent;
use App\Http\Livewire\LivewireAuth;
use App\Http\Livewire\Table;
use App\Models\Role;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\IndexRoleComponent */
class IndexRoleComponentTest extends TestCase
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
    public function assert_index_role_component_uses_table_trait()
    {
        $this->assertContains(Table::class, class_uses(IndexRoleComponent::class));
    }

    /** @test */
    public function assert_index_role_component_uses_livewire_auth_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(IndexRoleComponent::class));
    }

    /** @test */
    public function user_can_view_index_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('roles.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(IndexRoleComponent::class)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_search()
    {
        $writer = RoleFactory::new()->create([
            'name' => 'writer',
        ]);

        $manager = RoleFactory::new()->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->set('perPage', 1)
            ->set('search', 'writer')
            ->assertSee('writer')
            ->assertDontSee('manager');
    }

    /** @test */
    public function render_paginate()
    {
        $writer = RoleFactory::new()->create([
            'name' => 'writer',
        ]);

        $manager = RoleFactory::new()->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->set('perPage', 1)
            ->assertSee('admin')
            ->assertDontSee('writer')
            ->assertDontSee('manager')
            ->set('perPage', 3)
            ->assertSee('admin')
            ->assertSee('writer')
            ->assertSee('manager');
    }

    /** @test */
    public function render_order_by()
    {
        $writer = RoleFactory::new()->create([
            'name' => 'writer',
        ]);

        $manager = RoleFactory::new()->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->set('perPage', 1)
            ->set('sortField', 'name')
            ->call('sortBy', 'name')
            ->assertSee('writer')
            ->assertDontSee('manager')
            ->call('sortBy', 'name')
            ->assertSee('admin')
            ->assertDontSee('writer')
            ->assertDontSee('manager');
    }

    /** @test */
    public function admin_can_delete_role_with_attached_user()
    {
        $role = RoleFactory::new()->create();
        UserFactory::new()->create(['role_id' => $role->id]);

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->call('destroy', $role->id);

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_can_delete_role()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->call('destroy', $role->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_cannot_delete_admin_role()
    {
        $role = RoleFactory::new()->create([
            'name' => 'admin',
        ]);

        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class)
            ->call('destroy', $role->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->count(1, Role::where('name', 'admin')->get());
    }
}
