<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\HasLivewireAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Http\Livewire\HasLivewireAuth */
class HasLivewireAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_component()
    {
        $customClass = new class() {
            use HasLivewireAuth;
        };

        $this->expectException(AuthenticationException::class);

        $customClass->hydrate();
    }

    /** @test */
    public function user_with_allowed_role_can_access_component()
    {
        $customClass = new class() {
            use HasLivewireAuth;
        };

        $customClass->allowedRoles = ['manager'];

        $this->actingAs(create_user());

        $result = $customClass->hydrate();

        $this->assertNull($result);
    }

    /** @test */
    public function user_without_allowed_role_cannot_access_component()
    {
        $this->withoutExceptionHandling();

        $customClass = new class() {
            use HasLivewireAuth;
        };

        $customClass->allowedRoles = ['writer'];

        $this->expectException(AuthorizationException::class);

        $this->actingAs(create_user());

        $customClass->hydrate();
    }
}
