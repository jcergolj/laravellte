<?php

namespace Tests\Feature\Http\Livewire\Profile;

use App\Http\Livewire\Profile\Password;
use App\Mails\PasswordChangedMail;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Profile\Password
 */
class PasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User;
     */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        Mail::fake();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function profile_page_contains_password_livewire_component()
    {
        $this->actingAs(create_user())
            ->get(route('profile.users.index'))
            ->assertSeeLivewire('profile.password');
    }

    /** @test */
    public function guest_cannot_access_component()
    {
        $this->expectException(AuthenticationException::class);

        Livewire::test(Password::class)
            ->call('submit');
    }

    /** @test */
    public function flash_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set('new_password', 'new-password')
            ->set('new_password_confirmation', 'new-password')
            ->set('current_password', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('flash');
    }

    /** @test */
    public function close_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set('new_password', 'new-password')
            ->set('new_password_confirmation', 'new-password')
            ->set('current_password', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close');
    }

    /** @test */
    public function email_notification_is_sent()
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set('new_password', 'new-password')
            ->set('new_password_confirmation', 'new-password')
            ->set('current_password', 'password')
            ->call('submit');

        Mail::assertQueued(PasswordChangedMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    }

    /** @test */
    public function users_password_is_changed()
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set('new_password', 'new-password')
            ->set('new_password_confirmation', 'new-password')
            ->set('current_password', 'password')
            ->call('submit');

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors([$clientFormInput => $rule]);

        //password remains unchanged
        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test new password is required' => ['new_password', '', 'app\_rules\_password_rule'],
            'Test password must be greater than 7' => ['new_password', '1234567', 'app\_rules\_password_rule'],
            'Test current Password is required' => ['current_password', '', 'required'],
            'Test current Password must match auth user' => ['current_password', 'invalid-password', 'app\_rules\_password_check_rule'],
        ];
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        Livewire::actingAs($this->user)
            ->test(Password::class)
            ->set('new_password', 'new-password')
            ->set('new_password_confirmation', 'invalid-password')
            ->call('submit');

        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }
}
