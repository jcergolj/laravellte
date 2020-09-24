<?php

namespace Tests\Unit\Models;

use App\Models\PermissionRole;
use PHPUnit\Framework\TestCase;

/** @see \App\Models\PermissionRole */
class PermissionRoleTest extends TestCase
{
    /** @test */
    public function assert_id_is_casted()
    {
        $permissionRole = new PermissionRole();
        $this->assertSame('integer', $permissionRole->getCasts()['id']);
    }

    /** @test */
    public function assert_owner_restricted_is_casted()
    {
        $permissionRole = new PermissionRole();
        $this->assertSame('boolean', $permissionRole->getCasts()['owner_restricted']);
    }

    /** @test */
    public function assert_role_id_is_casted()
    {
        $permissionRole = new PermissionRole();
        $this->assertSame('integer', $permissionRole->getCasts()['role_id']);
    }

    /** @test */
    public function assert_permission_id_is_casted()
    {
        $permissionRole = new PermissionRole();
        $this->assertSame('integer', $permissionRole->getCasts()['permission_id']);
    }
}
