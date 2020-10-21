<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\HasLivewireAuth;
use App\Http\Livewire\HasTable;
use App\Http\Livewire\IndexUserComponent;
use App\Providers\AppServiceProvider;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\IndexUserComponent */
class IndexUserComponentTest extends TestCase
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
    public function assert_index_user_component_uses_table_trait()
    {
        $this->assertContains(HasTable::class, class_uses(IndexUserComponent::class));
    }

    /** @test */
    public function assert_index_user_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(IndexUserComponent::class));
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_search()
    {
        $joe = UserFactory::new()->create([
            'email' => 'joe@example.com',
        ]);

        $jane = UserFactory::new()->create([
            'email' => 'jane@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->set('perPage', 1)
            ->set('search', 'joe')
            ->assertSee('joe@example.com')
            ->assertDontSee('jane@example.com');
    }

    /** @test */
    public function render_paginate()
    {
        //the admin user exists too
        $joe = UserFactory::new()->create([
            'email' => 'joe@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->set('perPage', 1)
            ->assertSee('admin@admin.lte')
            ->assertDontSee('joe@example.com')
            ->set('perPage', 2)
            ->assertSee('admin@admin.lte')
            ->assertSee('joe@example.com');
    }

    /** @test */
    public function render_order_by()
    {
        //the admin role exists too form admin user

        $joe = UserFactory::new()->create([
            'email' => 'joe@example.com',
        ]);

        $jane = UserFactory::new()->create([
            'email' => 'jane@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->set('perPage', 1)
            ->set('sortField', 'email')
            ->call('sortBy', 'email')
            ->assertSee('joe@example.com')
            ->assertDontSee('jane@example.com')
            ->assertDontSee('admin@lte.com')
            ->call('sortBy', 'email')
            ->assertSee('admin@admin.lte')
            ->assertDontSee('joe@example.com')
            ->assertDontSee('jane@example.com');
    }

    /** @test */
    public function render_visible_to_non_admin_role()
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'users.index']),
                ['owner_restricted' => true]
            )
            ->create();

        UserFactory::new()
            ->create([
                'email' => 'joe@example.com',
                AppServiceProvider::OWNER_FIELD => $role->users[0]->id,
            ]);

        UserFactory::new()
            ->create([
                'email' => 'jane@example.com',
                AppServiceProvider::OWNER_FIELD => $this->admin->id,
            ]);

        Livewire::actingAs($role->users[0])
            ->test(IndexUserComponent::class)
            ->assertSee('joe@example.com')
            ->assertDontSee('jane@example.com');
    }

    /** @test */
    public function on_search_field_update_pagination_page_is_reset()
    {
        Livewire::actingAs($this->admin)->test(IndexUserComponent::class, ['page' => 2])
            ->set('search', 'something')
            ->assertSet('page', 1);
    }

    /** @test */
    public function on_role_id_field_update_pagination_page_is_reset()
    {
        Livewire::actingAs($this->admin)->test(IndexUserComponent::class, ['page' => 2])
            ->set('roleId', 'something')
            ->assertSet('page', 1);
    }

    /** @test  */
    public function index_user_page_contains_delete_user_livewire_component()
    {
        $this->actingAs($this->admin)->get('/users')->assertSeeLivewire('delete-user-component');
    }

    /** @test */
    public function entity_deleted_listener_exists()
    {
        $component = new IndexUserComponent;
        $this->assertContains('entity-deleted', $component->getEventsBeingListenedFor());
    }
}
