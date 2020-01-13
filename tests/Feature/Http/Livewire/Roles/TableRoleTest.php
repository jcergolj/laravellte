<?php

namespace Tests\Feature\Http\Livewire\Roles;

use App\Http\Livewire\Roles\TableRole;
use App\Http\Livewire\Table;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Roles\TableRole
 */
class TableRoleTest extends TestCase
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
    public function user_table_is_instance_of_table_class()
    {
        $this->assertInstanceOf(Table::class, new TableRole(1));
    }

    /** @test */
    public function render_per_page()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)
            ->test(TableRole::class)
            ->set('perPage', 2)
            ->assertSee($manager->name)
            ->assertDontSee($writer->name);
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

        Livewire::actingAs($this->admin)
            ->test(TableRole::class)
            ->set('search', 'writer')
            ->assertSee($writer->name)
            ->assertDontSee($manager->name);
    }

    /** @test */
    public function render_sort_by()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        Livewire::actingAs($this->admin)
            ->test(TableRole::class)
            ->set('perPage', 1)
            ->set('sortAsc', false)
            ->call('sortBy', 'name')
            ->assertSee($this->admin->role->name)
            ->set('sortAsc', true)
            ->call('sortBy', 'name')
            ->assertSee($writer->name);
    }

    /** @test */
    public function admin_can_delete_role_with_user()
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
            ->call('destroy', $role->id);

        $this->assertNull(Role::find($role->id));
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
