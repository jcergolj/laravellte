<?php

namespace Tests\Unit\Services;

use App\Providers\AppServiceProvider;
use App\Services\ForRouteGate;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** @see \App\Services\ForRouteGate */
class ForRouteGateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_is_always_allowed_to_proceed()
    {
        $this->assertTrue((new ForRouteGate)(create_admin()));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_allowed_roles_is_empty()
    {
        $this->assertFalse((new ForRouteGate)(create_user()));
    }

    /** @test */
    public function user_is_not_allowed_to_proceed_if_he_does_not_have_role()
    {
        $this->assertFalse((new ForRouteGate)(create_user(), ['writer']));
    }

    /** @test */
    public function user_can_proceed_if_he_has_role_and_model_is_null()
    {
        $role = RoleFactory::new(['name' => 'manager'])
            ->hasUsers(1)
            ->create();

        $this->assertTrue((new ForRouteGate)($role->users[0], ['manager']));
    }

    /** @test */
    public function user_can_proceed_if_he_has_role_and_he_is_model_owner()
    {
        $role = RoleFactory::new(['name' => 'manager'])
            ->hasUsers(1)
            ->create();

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(AppServiceProvider::OWNER_FIELD);
        });

        $team = Team::create(['id' => 1, AppServiceProvider::OWNER_FIELD => $role->users[0]->id]);

        $this->assertTrue((new ForRouteGate)($role->users[0], ['manager'], $team));
    }

    /** @test */
    public function user_can_proceed_if_he_has_role_and_model_does_not_have_owner_field()
    {
        $role = RoleFactory::new(['name' => 'manager'])
            ->hasUsers(1)
            ->create();

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
        });

        $team = Team::create(['id' => 1]);

        $this->assertTrue((new ForRouteGate)($role->users[0], ['manager'], $team));
    }

    /** @test */
    public function user_cannot_proceed_if_he_has_role_and_he_is_not_model_owner()
    {
        $role = RoleFactory::new(['name' => 'manager'])
            ->hasUsers(1)
            ->create();

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(AppServiceProvider::OWNER_FIELD);
        });

        $team = Team::create(['id' => 1, AppServiceProvider::OWNER_FIELD => 3]);

        $this->assertFalse((new ForRouteGate)($role->users[0], ['manager'], $team));
    }
}

class Team extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        AppServiceProvider::OWNER_FIELD => 'integer',
    ];

    public $timestamps = false;
}
