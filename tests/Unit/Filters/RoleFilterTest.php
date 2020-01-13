<?php

namespace Tests\Unit\Filters;

use App\Filters\Filter;
use App\Filters\RoleFilter;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Filters\UserFilter
 */
class RoleFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_filter_is_instance_of_filter_class()
    {
        $this->assertInstanceOf(Filter::class, new RoleFilter([]));
    }

    /** @test */
    public function filter_users_by_name()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $roleFilter = new RoleFilter([
            'search' => 'manager',
        ]);

        $result = Role::filter($roleFilter)->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($manager));
    }

    /** @test */
    public function order_users_by_field()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $roleFilter = new RoleFilter([
            'orderBy' => ['name', true],
        ]);

        $result = Role::filter($roleFilter)->get();

        $this->assertTrue(collect([$manager->id, $writer->id]) == $result->pluck('id'));
    }
}
