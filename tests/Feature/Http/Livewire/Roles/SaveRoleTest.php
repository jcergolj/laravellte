<?php

namespace Tests\Feature\Http\Livewire\Roles;

use App\Http\Livewire\Roles\SaveRole;
use App\Models\Role;
use App\Traits\LivewireAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Roles\SaveRole
 */
class SaveRoleTest extends TestCase
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
    public function assert_create_user_has_livewire_auth_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(SaveRole::class));
    }

    /** @test */
    public function render_for_store()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->assertSee('Save')
            ->assertSet('action', 'store')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_for_edit()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->assertSet('name', $role->name)
            ->assertSet('label', $role->label)
            ->assertSet('action', 'update')
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_store_validation_rules($clientFormInput, $clientFormValue)
    {
        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('store')
            ->assertHasErrors($clientFormInput);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue)
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors($clientFormInput);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test name is required' => ['name', ''],
            'Test name must be unique' => ['name', 'admin'],
            'Test label is required' => ['label', ''],
        ];
    }

    /** @test */
    public function unique_name_is_ignored_for_role_who_is_edited()
    {
        $role = factory(Role::class)->create();

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set('label', 'something')
            ->call('update')
            ->assertHasNoErrors('roleId');

        $this->assertSame('something', $role->fresh()->label);
    }

    /** @test */
    public function store_new_role()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveRole::class)
            ->set('name', 'manager')
            ->set('label', 'Manager')
            ->call('store')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $roles = Role::query()
            ->where('name', 'manager')
            ->where('label', 'Manager')
            ->get();

        $this->assertCount(1, $roles);
    }

    /** @test */
    public function update_existing_role()
    {
        $role = factory(Role::class)->create([
            'name' => 'manager',
            'label' => 'Manager',
        ]);

        // one role is from creating admin user
        $this->assertCount(2, Role::all());

        Livewire::actingAs($this->admin)
            ->test(SaveRole::class, ['role' => $role])
            ->set('name', 'writer')
            ->set('label', 'Writer')
            ->call('update')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $roles = Role::query()
            ->where('name', 'writer')
            ->where('label', 'Writer')
            ->get();

        $this->assertCount(1, $roles);

        $this->assertCount(2, Role::all());
    }
}
