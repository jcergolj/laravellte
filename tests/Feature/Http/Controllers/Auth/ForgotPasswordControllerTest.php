<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\ForgotPasswordController */
class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function user_can_view_forgot_password_form()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Send');
    }

    /** @test */
    public function user_can_request_an_email_with_password_reset_link()
    {
        $user = UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'joe@example.com']);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('password.request'));

        Notification::assertSentTo(
            [$user],
            ResetPassword::class,
            function ($notification, $channels) {
                return ! empty($notification->token);
            }
        );
    }

    /** @test */
    public function authenticated_user_is_redirected_to_tokens()
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAs($user)
            ->get(route('password.request'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('home.index'));

        $response = $this->actingAs(UserFactory::new()->create())
            ->post(route('password.email'), ['email' => 'joe@example.com']);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('home.index'));

        Notification::assertNotSentTo(
            [$user],
            ResetPassword::class
        );
    }

    /** @test */
    public function email_is_required()
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => '']);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('password.request'))
            ->assertInvalid('email');
    }

    /** @test */
    public function email_must_be_valid_email_address()
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'non-valid-email-address']);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('password.request'))
            ->assertInvalid('email');
    }
}
