<?php

namespace Tests\Feature\Http\Controllers;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\UserController */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    private $routePrefix = 'users';

    /** @test */
    public function admin_can_view_edit_page()
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAs(create_admin())
            ->get(route("{$this->routePrefix}.edit", $user));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id;
        });

        $response->assertViewIs("{$this->routePrefix}.edit");
    }
}
