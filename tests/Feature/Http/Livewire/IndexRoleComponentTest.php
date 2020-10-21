<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\HasLivewireAuth;
use App\Http\Livewire\HasTable;
use App\Http\Livewire\IndexRoleComponent;
use Database\Factories\RoleFactory;
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
        $this->assertContains(HasTable::class, class_uses(IndexRoleComponent::class));
    }

    /** @test */
    public function assert_index_role_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(IndexRoleComponent::class));
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
    public function on_search_field_update_pagination_page_is_reset()
    {
        Livewire::actingAs($this->admin)->test(IndexRoleComponent::class, ['page' => 2])
            ->set('search', 'something')
            ->assertSet('page', 1);
    }

    /** @test  */
    public function index_role_page_contains_livewire_delete_role_component()
    {
        $this->actingAs($this->admin)
            ->get('roles')
            ->assertSeeLivewire('delete-role-component');
    }

    /** @test */
    public function entity_deleted_listener_exists()
    {
        $component = new IndexRoleComponent;
        $this->assertContains('entity-deleted', $component->getEventsBeingListenedFor());
    }
}
