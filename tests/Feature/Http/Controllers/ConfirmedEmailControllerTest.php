<?php

namespace Tests\Feature\Http\Controllers;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/** @see \App\Http\Controllers\ConfirmedEmailController */
class ConfirmedEmailControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function abort_if_request_has_invalid_signature()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);

        $this->get($this->temporarySignedUri(create_user(), 'new.email@example.com').'invalid');
    }

    /** @test */
    public function abort_if_user_does_not_exists()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);

        //invalid user's id
        $this->get($this->temporarySignedUri('100', 'new.email@example.com'));
    }

    /** @test */
    public function confirm_user_email()
    {
        $user = create_user();

        $response = $this->get($this->temporarySignedUri($user, 'new.email@example.com'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('flash')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }

    /** @test */
    public function confirm_user_email_if_authenticated()
    {
        $user = create_user();

        $response = $this->actingAs(
            UserFactory::new()->create(['email' => 'jane@example.com'])
        )->get($this->temporarySignedUri($user, 'new.email@example.com'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('flash')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }

    private function temporarySignedUri($user, $email)
    {
        return URL::temporarySignedRoute(
            'confirmed-emails.store',
            Carbon::tomorrow(),
            ['user' => $user, 'new_email' => $email]
        );
    }
}
