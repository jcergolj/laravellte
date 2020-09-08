<?php

namespace Tests\Feature\Http\Controllers;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\HomeController */
class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_home_page()
    {
        $response = $this->actingAs(UserFactory::new()->create())
            ->get(route('home.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Sign Out');
    }
}
