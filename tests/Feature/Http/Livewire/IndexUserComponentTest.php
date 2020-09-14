<?php

namespace Tests\Feature\Http\Livewire\Users;

use App\Http\Livewire\IndexUserComponent;
use App\Http\Livewire\LivewireAuth;
use App\Http\Livewire\Table;
use App\Models\User;
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
        $this->assertContains(Table::class, class_uses(IndexUserComponent::class));
    }

    /** @test */
    public function assert_index_user_component_uses_livewire_auth_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(IndexUserComponent::class));
    }

    /** @test */
    public function user_can_view_index_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('users.index'));

        $response->assertStatus(Response::HTTP_OK);
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
    public function user_cannot_delete_himself()
    {
        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->call('destroy', $this->admin->id)
            ->assertForbidden();
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(IndexUserComponent::class)
            ->call('destroy', $user->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(User::find($user->id));
    }
}
