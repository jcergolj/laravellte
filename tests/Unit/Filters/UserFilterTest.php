<?php

namespace Tests\Unit\Filters;

use App\Models\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Filters\UserFilter */
class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function filter_users_by_email()
    {
        $admin = UserFactory::new()->create([
            'email' => 'admin@lte.com',
        ]);

        UserFactory::new()->create([
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
        $user = UserFactory::new()->forRole([
            'name' => 'writer',
        ])->create([
            'email' => 'admin@lte.com',
        ]);

        UserFactory::new()->forRole([
            'name' => 'manager',
        ])->create([
            'email' => 'manager@lte.com',
        ]);

        $result = User::filter([
            'roleId' => Role::where('name', 'writer')->first()->id,
        ])->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($user));
    }

    /** @test */
    public function order_users_by_field()
    {
        $manager = UserFactory::new()->create([
            'email' => 'manager@lte.com',
        ]);

        $admin = UserFactory::new()->create([
            'email' => 'admin@lte.com',
        ]);

        $result = User::filter([
            'orderByField' => ['email', 'asc'],
        ])->get();

        $this->assertTrue(collect([$admin->id, $manager->id]) == $result->pluck('id'));
    }
}
