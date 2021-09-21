<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\LoginController */
class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_login_form()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Sign In');
    }

    /** @test */
    public function user_can_login()
    {
        $user = UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('login', ['email' => 'joe@example.com', 'password' => 'password']);

        $this->assertAuthenticatedAs($user);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('home.index'));
    }

    /** @test */
    public function unregistered_user_cannot_be_authenticated()
    {
        $this->assertGuest();

        $response = $this->from('/')
            ->post('login', [
                'email' => 'non.existing.user@example.com',
                'password' => 'password',
            ]);

        $response->assertRedirect('/')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertInvalid();

        $this->assertGuest();
    }

    /** @test */
    public function user_with_wrong_credentials_cannot_be_authenticated()
    {
        $user = UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('login')
            ->post('login', [
                'email' => 'non.existing.user@example.com',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect('login')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertInvalid();

        $this->assertGuest();
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue)
    {
        UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('login')
            ->post('login', $this->validParams([$clientFormInput => $clientFormValue]));

        $response->assertRedirect('login')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertInvalid($clientFormInput);

        $this->assertGuest();
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test email is required' => ['email', ''],
            'Test email is valid' => ['email', 'not-an-email'],
            'Test password is required' => ['password', ''],
        ];
    }

    /** @test */
    public function authenticated_user_cannot_login_again()
    {
        $user = UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)
            ->get(route('login'));

        $response->assertRedirect(route('home.index'));

        $response = $this->actingAs($user)
            ->post('login', $this->validParams());

        $response->assertRedirect(route('home.index'));
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = UserFactory::new()->create([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->post('logout');
        $response->assertRedirect('/');

        $this->assertGuest();
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ], $overrides);
    }
}
