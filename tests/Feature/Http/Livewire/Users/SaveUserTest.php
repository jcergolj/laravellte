<?php

namespace Tests\Feature\Http\Livewire\Users;

use App\Http\Livewire\LivewireAuth;
use App\Http\Livewire\Users\SaveUser;
use App\Mail\InvitationMail;
use App\Models\User;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Users\SaveUser */
class SaveUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    protected $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();

        Mail::fake();
    }

    /** @test */
    public function assert_create_user_has_livewire_auth_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(SaveUser::class));
    }

    /** @test */
    public function user_create_page_contains_save_user_livewire_component()
    {
        $this->actingAs($this->admin)
            ->get(route('users.create'))
            ->assertSeeLivewire('users.save-user');
    }

    /** @test */
    public function user_edit_page_contains_save_user_livewire_component()
    {
        $this->actingAs($this->admin)
            ->get(route('users.edit', $this->admin))
            ->assertSeeLivewire('users.save-user');
    }

    /** @test */
    public function render_for_store()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveUser::class)
            ->assertSee('Save')
            ->assertSet('action', 'store')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function render_for_edit()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(SaveUser::class, ['user' => $user])
            ->assertSet('email', $user->email)
            ->assertSet('roleId', $user->role->id)
            ->assertSet('action', 'update')
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store_new_user()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveUser::class)
            ->set('roleId', $this->admin->role->id)
            ->set('email', 'joe@example.com')
            ->call('store')
            ->assertRedirect('users');

        $this->assertTrue(session()->has('flash'));

        $users = User::where('email', 'joe@example.com')
            ->where('role_id', $this->admin->role->id)
            ->whereNull('password')
            ->get();

        $this->assertCount(1, $users);
    }

    /** @test */
    public function invitation_email_is_sent_to_a_newly_create_user()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveUser::class)
            ->set('email', 'joe@example.com')
            ->set('roleId', $this->admin->role->id)
            ->call('store')
            ->assertRedirect('users');

        Mail::assertQueued(InvitationMail::class, 1);
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
            ->test(SaveUser::class, ['user' => $user])
            ->set('email', 'joe@example.com')
            ->set('roleId', $this->admin->role->id)
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
    public function test_store_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->admin)
            ->test(SaveUser::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('store')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(SaveUser::class, ['user' => $user])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email is valid' => ['email', 'not-an-email', 'email'],
            'Test email must be unique' => ['email', 'admin@admin.lte', 'unique'],
            'Test roleId is required' => ['roleId', '', 'required'],
            'Test roleId must exist' => ['roleId', 'invalid-role-id', 'exists'],
        ];
    }

    /** @test */
    public function unique_email_is_ignored_for_user_who_is_edited()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(SaveUser::class, ['user' => $user])
            ->set('roleId', $this->admin->role->id)
            ->call('update')
            ->assertHasNoErrors('roleId');

        $this->assertSame($this->admin->role->id, $user->fresh()->role_id);
    }

    /** @test */
    public function user_cannot_edit_himself()
    {
        Livewire::actingAs($this->admin)
            ->test(SaveUser::class, ['user' => $this->admin])
            ->call('update')
            ->assertForbidden();
    }
}
