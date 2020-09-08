<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\User;
use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** @see \App\Models\User */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_id_is_casted()
    {
        $user = new User();
        $this->assertSame('integer', $user->getCasts()['id']);
    }

    /** @test */
    public function assert_email_verified_at_is_casted()
    {
        $user = new User();
        $this->assertSame('datetime', $user->getCasts()['email_verified_at']);
    }

    /** @test */
    public function assert_role_id_is_casted()
    {
        $user = new User();
        $this->assertSame('integer', $user->getCasts()['role_id']);
    }

    /** @test */
    public function get_image_file_attribute()
    {
        $user = UserFactory::new()->create([
            'image' => null,
        ]);

        $this->assertSame(url('images/default-user.png'), $user->imageFile);

        $user = UserFactory::new()->create([
            'image' => 'image.png',
        ]);

        $this->assertSame(url('storage/avatars/image.png'), $user->imageFile);
    }

    /** @test */
    public function save_user_password()
    {
        $user = UserFactory::new()->create(['password' => Hash::make('password')]);

        $user->savePassword('new-password');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /** @test */
    public function save_image()
    {
        $user = UserFactory::new()->create([
            'image' => null,
        ]);

        $user->saveImage('new-image-name.jpg');

        $this->assertSame('new-image-name.jpg', $user->fresh()->image);
    }

    /** @test */
    public function is_himself()
    {
        $jane = UserFactory::new()->create(['email' => 'jane@example.com']);
        $joe = UserFactory::new()->create(['email' => 'joe@example.com']);

        $this->assertTrue($jane->isHimself($jane));
        $this->assertFalse($jane->isHimself($joe));
    }

    /** @test */
    public function has_permission()
    {
        $role = RoleFactory::new()->create();
        $user = UserFactory::new()->create(['role_id' => $role]);
        $this->assertFalse($user->hasPermission('users.index'));

        $role->permissions()->save(new Permission([
            'group' => 'users',
            'name' =>'users.index',
            'description' => 'index',
        ]));

        $this->assertTrue($user->hasPermission('users.index'));
    }
}
