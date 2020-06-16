<?php

namespace Tests\Feature\Http\Livewire\Profile;

use App\Http\Livewire\Profile\Email;
use App\Mail\NewEmailConfirmationMail;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Profile\Email */
class EmailTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
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
    public function profile_page_contains_email_livewire_component()
    {
        $this->actingAs(create_user())
            ->get(route('profile.users.index'))
            ->assertSeeLivewire('profile.email');
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
            ->set('currentPassword', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('flash');
    }

    /** @test */
    public function close_browser_event_is_emitted()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('currentPassword', 'password')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close');
    }

    /** @test */
    public function email_confirmation_is_sent()
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set('email', 'new.user.email@example.com')
            ->set('currentPassword', 'password')
            ->call('submit');

        Mail::assertQueued(NewEmailConfirmationMail::class, function ($mail) {
            return $mail->hasTo('new.user.email@example.com');
        });
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->user)
            ->test(Email::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email is valid' => ['email', 'not-an-email', 'email'],
            'Test current Password is required' => ['currentPassword', '', 'required'],
            'Test password must match auth user' => ['currentPassword', '', 'required'],
        ];
    }
}
