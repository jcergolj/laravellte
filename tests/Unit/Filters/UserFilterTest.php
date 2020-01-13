<?php

namespace Tests\Unit\Filters;

use App\Filters\Filter;
use App\Filters\UserFilter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Filters\UserFilter
 */
class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_filter_is_instance_of_filter_class()
    {
        $this->assertInstanceOf(Filter::class, new UserFilter([]));
    }

    /** @test */
    public function filter_users_by_email()
    {
        $admin = factory(User::class)->create([
            'email' => 'admin@lte.com',
        ]);

        factory(User::class)->create([
            'email' => 'manager@lte.com',
        ]);

        $userFilter = new UserFilter([
            'search' => 'admin',
        ]);

        $result = User::filter($userFilter)->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($admin));
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

        $userFilter = new UserFilter([
            'orderBy' => ['email', true],
        ]);

        $result = User::filter($userFilter)->get();

        $this->assertTrue(collect([$admin->id, $manager->id]) == $result->pluck('id'));
    }
}
