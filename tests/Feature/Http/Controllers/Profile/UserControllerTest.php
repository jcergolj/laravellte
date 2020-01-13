<?php

namespace Tests\Feature\Http\Controllers\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\Profile\UserController */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_profile_page()
    {
        $response = $this->actingAs($user = factory(User::class)->create())
            ->get(route('profile.users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee($user->email)
            ->assertSee('Password')
            ->assertSee('Image');
    }
}
