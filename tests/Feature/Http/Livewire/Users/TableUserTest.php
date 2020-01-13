<?php

namespace Tests\Feature\Http\Livewire\Users;

use App\Http\Livewire\Table;
use App\Http\Livewire\Users\TableUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Users\TableUser
 */
class TableUserTest extends TestCase
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
        $this->assertInstanceOf(Table::class, new TableUser(1));
    }

    /** @test */
    public function render_per_page()
    {
        $jane = factory(User::class)->create([
            'email' => 'jane@example.com',
        ]);

        $joe = factory(User::class)->create([
            'email' => 'joe@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
            ->set('perPage', 2)
            ->assertSee($jane->email)
            ->assertSee($this->admin->email)
            ->assertDontSee($joe->email);
    }

    /** @test */
    public function render_search()
    {
        $jane = factory(User::class)->create([
            'email' => 'jane@example.com',
        ]);

        $joe = factory(User::class)->create([
            'email' => 'joe@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
            ->set('search', 'joe')
            ->assertSee($joe->email)
            ->assertDontSee($jane->email)
            ->assertDontSee($this->admin->email);
    }

    /** @test */
    public function render_sort_by()
    {
        $jane = factory(User::class)->create([
            'email' => 'jane@example.com',
        ]);

        $joe = factory(User::class)->create([
            'email' => 'joe@example.com',
        ]);

        Livewire::actingAs($this->admin)
            ->test(TableUser::class)
            ->set('perPage', 1)
            ->set('sortAsc', false)
            ->call('sortBy', 'email')
            ->assertSee($this->admin->email)
            ->set('sortAsc', true)
            ->call('sortBy', 'email')
            ->assertSee($joe->email);
    }

    /** @test */
    public function user_cannot_delete_himself()
    {
        Livewire::actingAs($this->admin)->test(TableUser::class)
            ->call('destroy', $this->admin->id)
            ->assertForbidden();
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = factory(User::class)->create();

        Livewire::actingAs($this->admin)->test(TableUser::class)
            ->call('destroy', $user->id);

        $this->assertNull(User::find($user->id));
    }

    /** @test */
    public function close_event_is_emitted_when_user_is_deleted()
    {
        $user = factory(User::class)->create();

        Livewire::actingAs($this->admin)->test(TableUser::class)
            ->call('destroy', $user->id)
            ->assertDispatchedBrowserEvent('close');
    }
}
