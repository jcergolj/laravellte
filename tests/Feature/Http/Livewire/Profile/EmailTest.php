<?php

namespace Tests\Feature\Http\Livewire\Profile;

use App\Http\Livewire\Profile\Email;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Profile\Email
 */
class EmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User;
     */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        Queue::fake();
        Mail::fake();
        Event::fake();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function guest_cannot_access_component()
    {
        $this->expectException(AuthenticationException::class);

        Livewire::test(Email::class)
            ->call('submit');
    }

    /** @test */
    public function flash_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('current_password', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('flash');
    }

    /** @test */
    public function close_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('current_password', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close');
    }

    /** @test */
    public function email_confirmation_is_sent()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('current_password', 'password')
            ->call('submit');

        Mail::assertQueued(config('verify-new-email.mailable_for_new_email'), function ($mail) {
            return $mail->hasTo('new.user.email@example.com');
        });
    }

    /** @test */
    public function pending_email_is_saved()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('current_password', 'password')
            ->call('submit');

        $pendingEmail = DB::table('pending_user_emails')
            ->where('email', 'new.user.email@example.com')
            ->first();

        $this->assertNotNull($pendingEmail);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue)
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors($clientFormInput);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', ''],
            'Test email is valid' => ['email', 'not-an-email'],
            'Test current Password is required' => ['current_password', ''],
            'Test password must match auth user' => ['current_password', ''],
        ];
    }
}
