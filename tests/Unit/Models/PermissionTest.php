<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use PHPUnit\Framework\TestCase;

/** @see \App\Models\Permission */
class PermissionTest extends TestCase
{
    /** @test */
    public function assert_id_is_casted()
    {
        $permission = new Permission();
        $this->assertSame('integer', $permission->getCasts()['id']);
    }
}
