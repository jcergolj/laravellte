<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_displays_view()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('users.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('users.index');
    }

    /** @test */
    public function create_displays_view()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('users.create'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('users.create');
    }

    /** @test */
    public function edit_displays_view()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs(create_admin())
            ->get(route('users.edit', $user));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id;
        });

        $response->assertViewIs('users.edit');
    }
}
