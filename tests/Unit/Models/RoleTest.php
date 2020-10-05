<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Models\Role */
class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_id_is_casted()
    {
        $role = new Role();
        $this->assertSame('integer', $role->getCasts()['id']);
    }
}
