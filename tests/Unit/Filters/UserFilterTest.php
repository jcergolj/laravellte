<?php

namespace Tests\Unit\Filters;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Filters\UserFilter */
class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function filter_users_by_email()
    {
        $admin = factory(User::class)->create([
            'email' => 'admin@lte.com',
        ]);

        factory(User::class)->create([
            'email' => 'manager@lte.com',
        ]);

        $result = User::filter([
                'search' => 'admin',
            ])->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($admin));
    }

    /** @test */
    public function filter_users_by_role_id()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $user = factory(User::class)->create([
            'role_id' => $writer->id,
            'email' => 'admin@lte.com',
        ]);

        factory(User::class)->create([
            'role_id' => $manager->id,
            'email' => 'manager@lte.com',
        ]);

        $result = User::filter([
            'roleId' => $writer->id,
        ])->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($user));
    }

    /** @test */
    public function order_users_by_field()
    {
        $manager = factory(User::class)->create([
            'email' => 'manager@lte.com',
        ]);

        $admin = factory(User::class)->create([
            'email' => 'admin@lte.com',
        ]);

        $result = User::filter([
                'orderByField' => ['email', 'asc'],
            ])->get();

        $this->assertTrue(collect([$admin->id, $manager->id]) == $result->pluck('id'));
    }
}
