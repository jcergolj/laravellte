<?php

namespace Tests\Feature\Http\Livewire\Auth;

use App\Http\Livewire\Auth\RegisterNewUserComponent;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Auth\RegisterNewUserComponent */
class RegisterNewUserComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\Role */
    private $role;

    public function setUp() : void
    {
        parent::setUp();

        Event::fake();

        $this->role = RoleFactory::new()->create(['name' => 'manager']);
    }

    /** @test */
    public function render()
    {
        Livewire::test(RegisterNewUserComponent::class)
            ->assertSee('Register')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function register()
    {
        Livewire::test(RegisterNewUserComponent::class)
            ->set('email', 'joe@example.com')
            ->set('password', 'password')
            ->call('register');

        $user = User::first();
        $this->assertSame('joe@example.com', $user->email);
        $this->assertSame($this->role->id, $user->role_id);
        $this->assertNull($user->owner_id);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    /** @test */
    public function register_event_is_fired()
    {
        Event::assertNotDispatched(Registered::class);
        Livewire::test(RegisterNewUserComponent::class)
            ->set('email', 'joe@example.com')
            ->set('password', 'password')
            ->call('register');

        Event::assertDispatched(Registered::class, 1);

        Event::assertDispatched(Registered::class, function (Registered $registered) {
            return $registered->user->is(User::where('email', 'joe@example.com')->first());
        });
    }

    /** @test */
    public function flash_browser_event_is_dispatched()
    {
        Livewire::test(RegisterNewUserComponent::class)
            ->set('email', 'joe@example.com')
            ->set('password', 'password')
            ->call('register')
            ->assertDispatchedBrowserEvent('flash');
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_register_new_user_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::test(RegisterNewUserComponent::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('register')
            ->assertHasErrors([$clientFormInput => $rule]);

        $this->assertCount(0, User::get());
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email is valid' => ['email', 'not-an-email', 'email'],
            'Test new password is required' => ['password', '', 'app\_rules\_password_rule'],
        ];
    }

    /** @test */
    public function email_must_be_unique()
    {
        create_user('joe@example.com');

        Livewire::test(RegisterNewUserComponent::class)
            ->set('email', 'joe@example.com')
            ->call('register')
            ->assertHasErrors(['email' => 'unique']);

        $this->assertCount(1, User::get());
    }
}
