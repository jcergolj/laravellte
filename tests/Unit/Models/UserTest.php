<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\User;
use Database\Factories\PermissionFactory;
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

    /** @test */
    public function get_permission()
    {
        $role = RoleFactory::new()->create();
        $user = UserFactory::new()->create(['role_id' => $role]);
        $this->assertFalse($user->hasPermission('users.index'));

        $role->permissions()->save(new Permission([
             'group' => 'users',
             'name' =>'users.index',
             'description' => 'index',
         ]));

        $permission = $user->getPermission('users.index');

        $this->assertSame('users.index', $permission->name);
    }

    /** @test */
    public function is_model_owner_permission_does_not_exists()
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->create();

        $this->assertFalse(
            $role->users[0]->isModelOwner('edit.roles', new Team(create_user('yet-another-user@gmail.com')->id))
        );

        $this->assertFalse(
            $role->users[0]->isModelOwner('edit.roles', new Team($role->users[0]->id))
        );
    }

    /** @test */
    public function is_model_owner_owner_restricted()
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'show.users']),
                ['owner_restricted' => true]
            )
            ->create();

        $this->assertTrue(
            $role->users[0]->isModelOwner('show.users', new Team($role->users[0]->id))
        );

        $this->assertFalse(
            $role->users[0]->isModelOwner('show.users', new Team(create_user('another-user@gmail.com')->id))
        );
    }

    /** @test */
    public function is_model_owner_owner_restricted_is_false()
    {
        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'show.users']),
                ['owner_restricted' => false]
            )
            ->create();

        $this->assertTrue(
            $role->users[0]->isModelOwner('show.users', new Team($role->users[0]->id))
        );

        $this->assertTrue(
            $role->users[0]->isModelOwner('show.users', new Team(create_user('another-user@gmail.com')->id))
        );
    }
}

class Team
{
    public $owner_id;

    public function __construct($ownerId)
    {
        $this->owner_id = $ownerId;
    }
}
