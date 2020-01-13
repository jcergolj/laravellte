<?php

namespace Tests\Unit\Traits;

use App\Models\Role;
use App\Models\User;
use App\Traits\LivewireAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Traits\LivewireAuth
 */
class LivewireAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_component()
    {
        $customClass = new class() {
            use LivewireAuth;
        };

        $this->expectException(AuthenticationException::class);

        $customClass->hydrate();
    }

    /** @test */
    public function user_without_allowed_role_cannot_component()
    {
        $this->withoutExceptionHandling();

        $customClass = new class() {
            use LivewireAuth;
        };

        $role = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $manager = factory(User::class)->create(['role_id' => $role->id]);

        $this->expectException(AuthorizationException::class);

        $this->actingAs($manager);

        $customClass->hydrate();
    }
}
