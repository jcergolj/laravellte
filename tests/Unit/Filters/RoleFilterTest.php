<?php

namespace Tests\Unit\Filters;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Filters\RoleFilter */
class RoleFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_roles_by_name()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $result = Role::filter([
                'search' => 'manager',
            ])->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($manager));
    }

    /** @test */
    public function order_roles_by_field()
    {
        $writer = factory(Role::class)->create([
            'name' => 'writer',
        ]);

        $manager = factory(Role::class)->create([
            'name' => 'manager',
        ]);

        $result = Role::filter([
                'orderByField' => ['name', 'asc'],
            ])->get();

        $this->assertTrue(collect([$manager->id, $writer->id]) == $result->pluck('id'));
    }
}
