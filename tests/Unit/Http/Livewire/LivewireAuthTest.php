<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\LivewireAuth;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Http\Livewire\LivewireAuth */
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
    public function user_without_allowed_permission_cannot_access_component()
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
