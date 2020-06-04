<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\ResetPasswordController */
class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_reset_password_page()
    {
        $response = $this->get(route('password.reset', ['token' => 'token']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Password');
    }

    /** @test */
    public function user_with_valid_token_can_reset_his_password()
    {
        $user = factory(User::class)->create([
            'email' => 'joe@example.com',
        ]);

        $tokenRepository = $this->createTokenRepository();
        $token = $tokenRepository->create($user);

        $response = $this->post('password/reset', [
            'token' => $token,
            'email' => 'joe@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('home.index'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_with_invalid_token_cannot_reset_his_password()
    {
        $user = factory(User::class)->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from(route('password.reset', ['token' => 'invalid-token']))
            ->post('password/reset', $this->validParams($user, ['token' => 'invalid-token']));

        $response->assertRedirect(route('password.reset', ['token' => 'invalid-token']));
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_cannot_reset_password()
    {
        $user = factory(User::class)->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $validParams = $this->validParams($user);

        $response = $this->actingAs($user)
            ->get(route('password.reset', ['token' => $validParams['token']]));

        $response->assertRedirect(route('home.index'));

        $response = $this->actingAs($user)
            ->post('password/reset', $validParams);

        $response->assertRedirect(route('home.index'));
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue)
    {
        $user = factory(User::class)->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $validParams = $this->validParams($user, [$clientFormInput => $clientFormValue]);

        $response = $this->from(route('password.reset', ['token' => $validParams['token']]))
            ->post('password/reset', $validParams);

        $response->assertRedirect(route('password.reset', ['token' => $validParams['token']]))
            ->assertSessionHasErrors($clientFormInput);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));

        $this->assertGuest();
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', ''],
            'Test email is valid' => ['email', 'not-an-email'],
            'Test password is required' => ['password', ''],
            'Test password must be greater than 7' => ['password', '1234567'],
        ];
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $user = factory(User::class)->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $validParams = $this->validParams($user, [
            'password' => 'password',
            'password_confirmation' => 'non-matching-password',
        ]);

        $response = $this->from(route('password.reset', ['token' => $validParams['token']]))
            ->post('password/reset', $validParams);

        $response->assertRedirect(route('password.reset', ['token' => $validParams['token']]))
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertGuest();
    }

    private function createTokenRepository()
    {
        return new DatabaseTokenRepository(
            $this->app['db']->connection(null),
            $this->app['hash'],
            'password_resets',
            'key-string',
            '60'
        );
    }

    private function validParams($user, $overrides = [])
    {
        $tokenRepository = $this->createTokenRepository();
        $token = $tokenRepository->create($user);

        return array_merge([
            'token' => $token,
            'email' => 'joe@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ], $overrides);
    }
}
