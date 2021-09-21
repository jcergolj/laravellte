<?php

namespace Tests\Feature\Http\Livewire\Profile;

use App\Http\Livewire\Profile\UpdatePassword;
use App\Mail\PasswordChangedMail;
use Database\Factories\UserFactory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Profile\UpdatePassword */
class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $user;

    /** @var string */
    private $password;

    public function setUp() : void
    {
        parent::setUp();

        Mail::fake();

        $this->user = UserFactory::new()->create();

        $this->password = password_generator();
    }

    /** @test */
    public function profile_page_contains_password_livewire_component()
    {
        $this->actingAs(create_user())
            ->get(route('profile.users.index'))
            ->assertSeeLivewire('profile.update-password');
    }

    /** @test */
    public function guest_cannot_access_component()
    {
        $this->expectException(AuthenticationException::class);

        Livewire::test(UpdatePassword::class)
            ->call('submit');
    }

    /** @test */
    public function flash_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set('newPassword', $this->password)
            ->set('newPasswordConfirmation', $this->password)
            ->set('currentPassword', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('flash');
    }

    /** @test */
    public function close_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set('newPassword', $this->password)
            ->set('newPasswordConfirmation', $this->password)
            ->set('currentPassword', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close');
    }

    /** @test */
    public function email_notification_is_sent()
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set('newPassword', $this->password)
            ->set('newPasswordConfirmation', $this->password)
            ->set('currentPassword', 'password')
            ->call('submit');

        Mail::assertQueued(PasswordChangedMail::class, function ($mail) {
            $this->assertTrue($mail->hasTo($this->user->email), 'Unexpected to');

            return true;
        });
    }

    /** @test */
    public function users_password_is_changed()
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set('newPassword', $this->password)
            ->set('newPasswordConfirmation', $this->password)
            ->set('currentPassword', 'password')
            ->call('submit');

        $this->assertTrue(Hash::check($this->password, $this->user->fresh()->password));
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors([$clientFormInput => $rule]);

        //password remains unchanged
        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test new password is required' => ['newPassword', '', 'app\_rules\_password_rule'],
            'Test password must be greater than 7' => ['newPassword', too_short_password(), 'app\_rules\_password_rule'],
            'Test current Password is required' => ['currentPassword', '', 'required'],
            'Test current Password must match auth user' => ['currentPassword', 'invalid-password', 'app\_rules\_password_check_rule'],
        ];
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        Livewire::actingAs($this->user)
            ->test(UpdatePassword::class)
            ->set('newPassword', $this->password)
            ->set('newPasswordConfirmation', $this->password.'invalid-password')
            ->call('submit');

        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    /** @test */
    public function password_must_be_uncompromised()
    {
        Livewire::actingAs($this->user)
             ->test(UpdatePassword::class)
             ->set('newPassword', 'new-password')
             ->set('newPasswordConfirmation', 'new-password')
             ->call('submit');

        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }
}
