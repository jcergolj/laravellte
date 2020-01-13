<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @see \App\Models\User
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_image_file_attribute()
    {
        $user = factory(User::class)->create([
            'image' => null,
        ]);

        $this->assertSame('http://admin.test/images/default-user.png', $user->imageFile);

        $user = factory(User::class)->create([
            'image' => 'image.png',
        ]);

        $this->assertSame('/storage/users/images/image.png', $user->imageFile);
    }

    /** @test */
    public function save_user_password()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $user->savePassword('new-password');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /** @test */
    public function save_image()
    {
        $user = factory(User::class)->create([
            'image' => null,
        ]);

        $user->saveImage();

        $this->assertNotNull($user->fresh()->image);
    }

    /** @test */
    public function is_himself()
    {
        $jane = factory(User::class)->create(['email' => 'jane@example.com']);
        $joe = factory(User::class)->create(['email' => 'joe@example.com']);

        $this->assertTrue($jane->isHimself($jane));
        $this->assertFalse($jane->isHimself($joe));
    }

    /** @test */
    public function has_permission()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create(['role_id' => $role]);
        $this->assertFalse($user->hasPermission('users.index'));

        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.index',
            'description' => 'index',
        ]));

        $this->assertTrue($user->hasPermission('users.index'));
    }
}
