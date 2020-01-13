<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RoleController
 */
class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_displays_view()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('roles.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('roles.index');
    }

    /** @test */
    public function create_displays_view()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('roles.create'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('roles.create');
    }

    /** @test */
    public function edit_displays_view()
    {
        $role = factory(Role::class)->create();

        $response = $this->actingAs(create_admin())
            ->get(route('roles.edit', $role));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertViewHas('role', function ($viewRole) use ($role) {
            return $viewRole->id === $role->id;
        });

        $response->assertViewIs('roles.edit');
    }
}
