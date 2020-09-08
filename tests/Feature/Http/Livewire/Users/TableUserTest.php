<?php

namespace Tests\Feature\Http\Livewire\Users;

use App\Http\Livewire\Users\TableUser;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Users\TableUser */
class TableUserTest extends TestCase
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
    public function users_index_page_contains_table_user_livewire_component()
    {
        $this->actingAs($this->admin)
            ->get(route('users.index'))
            ->assertSeeLivewire('users.table-user');
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
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
            ->test(TableUser::class)
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
            ->test(TableUser::class)
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
            ->test(TableUser::class)
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
            ->test(TableUser::class)
            ->call('destroy', $this->admin->id)
            ->assertForbidden();
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
            ->call('destroy', $user->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(User::find($user->id));
    }

    /** @test */
    public function close_event_is_emitted_when_user_is_deleted()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
            ->call('destroy', $user->id)
            ->assertDispatchedBrowserEvent('close');
    }
}
