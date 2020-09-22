<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\EditUserComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\User;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\EditUserComponent */
class EditUserComponentTest extends TestCase
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
    public function assert_edit_user_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(EditUserComponent::class));
    }

    /** @test */
    public function render()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $user])
            ->assertSet('user.email', $user->email)
            ->assertSet('user.role_id', $user->role->id)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update_existing_user()
    {
        $user = UserFactory::new()->create([
            'email' => 'jane@example.com',
            'role_id' => RoleFactory::new()->create(),
        ]);

        $this->assertCount(2, User::all());

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $user])
            ->set('user.email', 'joe@example.com')
            ->set('user.role_id', $this->admin->role->id)
            ->call('update')
            ->assertRedirect('users');

        $this->assertTrue(session()->has('flash'));

        $users = User::where('email', 'joe@example.com')
            ->where('role_id', $this->admin->role->id)
            ->get();

        $this->assertCount(1, $users);

        $this->assertCount(2, User::all());
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $user])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['user.email', '', 'required'],
            'Test email is valid' => ['user.email', 'not-an-email', 'email'],
            'Test email must be unique' => ['user.email', 'admin@admin.lte', 'unique'],
            'Test role_id is required' => ['user.role_id', null, 'required'],
            'Test role_id must exist' => ['user.role_id', 'invalid-role-id', 'exists'],
        ];
    }

    /** @test */
    public function unique_email_is_ignored_for_user_who_is_edited()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $user])
            ->set('user.role_id', $this->admin->role->id)
            ->call('update')
            ->assertHasNoErrors('roleId');

        $this->assertSame($this->admin->role->id, $user->fresh()->role_id);
    }

    /** @test */
    public function user_cannot_edit_himself()
    {
        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $this->admin])
            ->assertForbidden();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['user' => $this->admin])
            //->call('update')
            ->assertForbidden();
    }
}
