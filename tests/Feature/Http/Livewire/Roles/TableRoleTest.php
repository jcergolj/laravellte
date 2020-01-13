<?php

namespace Tests\Feature\Http\Livewire\Roles;

use App\Http\Livewire\Roles\TableRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Roles\TableRole */
class TableRoleTest extends TestCase
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
    public function roles_index_page_contains_table_role_livewire_component()
    {
        $this->actingAs($this->admin)
            ->get(route('roles.index'))
            ->assertSeeLivewire('roles.table-role');
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(TableRole::class)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_search()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(TableRole::class)
            ->set('perPage', 1)
            ->set('search', 'writer')
            ->assertSee('writer')
            ->assertDontSee('manager');
    }

    /** @test */
    public function render_paginate()
    {
        //the admin role exists too form admin user

        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(TableRole::class)
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
        //the admin role exists too form admin user

        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)->test(TableRole::class)
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
        $role = factory(Role::class)->create();
        factory(User::class)->create(['role_id' => $role->id]);

        Livewire::actingAs($this->admin)->test(TableRole::class)
            ->call('destroy', $role->id);

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_can_delete_role()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)->test(TableRole::class)
            ->call('destroy', $role->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(Role::find($role->id));
    }

    /** @test */
    public function admin_cannot_delete_admin_role()
    {
        $role = factory(Role::class)->create([
            'name' => 'admin',
        ]);

        Livewire::actingAs($this->admin)->test(TableRole::class)
            ->call('destroy', $role->id)
            ->assertDispatchedBrowserEvent('flash');

        $this->count(1, Role::where('name', 'admin')->get());
    }

    /** @test */
    public function close_event_is_emitted_when_user_is_deleted()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)->test(TableRole::class)
            ->call('destroy', $role->id)
            ->assertDispatchedBrowserEvent('close');
    }
}
