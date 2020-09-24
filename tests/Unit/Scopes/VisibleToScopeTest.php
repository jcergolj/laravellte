<?php

namespace Tests\Unit\Scopes;

use App\Providers\AppServiceProvider;
use App\Scopes\VisibleToScope;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** @see \App\Scopes\VisibleToScope */
class VisibleToScopeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
    }

    /** @test */
    public function filter_visible_to_owner_restricted_is_true()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS `teams` (`id` BIGINT AUTO_INCREMENT, `owner_id` BIGINT NOT NULL, PRIMARY KEY (`id`));');

        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'teams.index']),
                ['owner_restricted' => true]
            )
            ->create();

        $user = $role->users[0];

        $this->actingAs($user);

        $team1 = Team::create(['id' => 1, AppServiceProvider::OWNER_FIELD => $user->id]);
        Team::create(['id' => 2, AppServiceProvider::OWNER_FIELD => create_admin()->id]);

        $teams = Team::get();

        $this->assertCount(1, $teams);

        $this->assertTrue($teams->contains($team1));
    }

    /** @test */
    public function filter_visible_to_owner_restricted_is_false()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS `teams` (`id` BIGINT AUTO_INCREMENT, `owner_id` BIGINT NOT NULL, PRIMARY KEY (`id`));');

        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'teams.index']),
                ['owner_restricted' => false]
            )
            ->create();

        $user = $role->users[0];

        $this->actingAs($user);

        $team1 = Team::create(['id' => 1, AppServiceProvider::OWNER_FIELD => $user->id]);
        $team2 = Team::create(['id' => 2, AppServiceProvider::OWNER_FIELD => create_admin()->id]);

        $teams = Team::get();

        $this->assertCount(2, $teams);

        $this->assertTrue($teams->contains($team1));
        $this->assertTrue($teams->contains($team2));
    }

    /** @test */
    public function filter_visible_to_owner_if_field_does_not_exists()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS `teams` (`id` BIGINT AUTO_INCREMENT, PRIMARY KEY (`id`));');

        $role = RoleFactory::new()
            ->hasUsers(1)
            ->hasAttached(
                PermissionFactory::new(['name' => 'users.index']),
                ['owner_restricted' => true]
            )
            ->create();

        $user = $role->users[0];

        $team1 = Team::create(['id' => 1]);
        $team2 = Team::create(['id' => 2]);

        $teams = Team::get();

        $this->assertCount(2, $teams);

        $this->assertTrue($teams->contains($team1));
        $this->assertTrue($teams->contains($team2));
    }

    /** @test */
    public function filter_visible_to_for_admin()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS `teams` (`id` BIGINT AUTO_INCREMENT, `owner_id` BIGINT NOT NULL, PRIMARY KEY (`id`));');

        $admin = create_admin();
        $user = create_user();

        $this->actingAs($admin);

        $team1 = Team::create(['id' => 1, AppServiceProvider::OWNER_FIELD => $user->id]);
        $team2 = Team::create(['id' => 2, AppServiceProvider::OWNER_FIELD => $user->id]);

        $teams = Team::get();

        $this->assertCount(2, $teams);

        $this->assertTrue($teams->contains($team1));
        $this->assertTrue($teams->contains($team2));
    }
}

class Team extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VisibleToScope());
    }
}
