<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\EditRoleComponent;
use App\Models\Role;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\EditUserComponent */
class EditRoleComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    protected $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function render()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->assertSet('role.name', $role->name)
            ->assertSet('role.label', $role->label)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update()
    {
        $role = RoleFactory::new()->create([
            'name' => 'manager',
            'label' => 'Manager',
        ]);

        // one role is from creating admin user
        $this->assertCount(2, Role::all());

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('role.name', 'writer')
            ->set('role.label', 'Writer')
            ->call('update')
            ->assertRedirect('roles');

        $this->assertTrue(session()->has('flash'));

        $roles = Role::where('name', 'writer')
                ->where('label', 'Writer')
                ->get();

        $this->assertCount(1, $roles);

        $this->assertCount(2, Role::all());
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test name is required' => ['role.name', '', 'required'],
            'Test name must be unique' => ['role.name', 'admin', 'unique'],
            'Test label is required' => ['role.label', '', 'required'],
        ];
    }

    /** @test */
    public function unique_name_is_ignored_for_role_who_is_edited()
    {
        $role = RoleFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditRoleComponent::class, ['role' => $role])
            ->set('role.label', 'something')
            ->call('update')
            ->assertHasNoErrors('label');

        $this->assertSame('something', $role->fresh()->label);
    }
}
