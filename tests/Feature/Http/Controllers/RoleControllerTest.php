<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\RoleController */
class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    private $routePrefix = 'roles';

    /** @test */
    public function user_can_view_index_page()
    {
        $response = $this->actingAs(create_admin())
            ->get(route("{$this->routePrefix}.index"));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs("{$this->routePrefix}.index");
    }

    /** @test */
    public function user_can_view_create_page()
    {
        $response = $this->actingAs(create_admin())
            ->get(route("{$this->routePrefix}.create"));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs("{$this->routePrefix}.create");
    }

    /** @test */
    public function user_can_view_edit_page()
    {
        $role = factory(Role::class)->create();

        $response = $this->actingAs(create_admin())
            ->get(route("{$this->routePrefix}.edit", $role));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('role', function ($viewRole) use ($role) {
                return $viewRole->id === $role->id;
            })->assertViewIs("{$this->routePrefix}.edit");
    }
}
