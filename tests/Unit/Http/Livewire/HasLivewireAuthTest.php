<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\HasLivewireAuth;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** @see \App\Http\Livewire\HasLivewireAuth */
class HasLivewireAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_component()
    {
        $customClass = new class() {
            use HasLivewireAuth;
        };

        $this->expectException(AuthenticationException::class);

        $customClass->hydrate();
    }

    /** @test */
    public function user_with_allowed_role_can_access_component()
    {
        $customClass = new class() {
            use HasLivewireAuth;
        };

        $customClass->allowedRoles = ['manager'];

        $this->actingAs(create_user());

        $result = $customClass->hydrate();

        $this->assertNull($result);
    }

    /** @test */
    public function user_without_allowed_role_cannot_access_component()
    {
        $this->withoutExceptionHandling();

        $customClass = new class() {
            use HasLivewireAuth;
        };

        $customClass->allowedRoles = ['writer'];

        $this->expectException(AuthorizationException::class);

        $this->actingAs(create_user());

        $customClass->hydrate();
    }

    /** @test */
    public function user_with_allowed_role_and_owned_bind_model_can_access_component()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(AppServiceProvider::OWNER_FIELD);
        });

        $user = create_user();
        $customClass = new class(new Team(['id' => 1, 'owner_id' => $user->id])) {
            use HasLivewireAuth;

            public $team;

            public function __construct(Team $team)
            {
                $this->team = $team;
            }
        };

        $customClass->allowedRoles = ['manager'];

        $this->actingAs($user);

        $result = $customClass->hydrate();

        $this->assertNull($result);
    }

    /** @test */
    public function user_with_allowed_role_and_not_owned_bind_model_can_not_access_component()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(AppServiceProvider::OWNER_FIELD);
        });

        $user = create_user();
        $customClass = new class(new Team(['id' => 1, 'owner_id' => create_admin()->id])) {
            use HasLivewireAuth;

            public $team;

            public function __construct(Team $team)
            {
                $this->team = $team;
            }
        };

        $customClass->allowedRoles = ['manager'];

        $this->actingAs($user);

        $result = $customClass->hydrate();
    }

    /** @test */
    public function set_model_method_is_called_if_exists()
    {
        $customClass = new class() {
            /** @var bool */
            public $called = false;

            use HasLivewireAuth;

            protected function setModel()
            {
                $this->called = true;
            }
        };

        $customClass->allowedRoles = ['manager'];

        $this->actingAs(create_user());

        $customClass->hydrate();

        $this->assertTrue($customClass->called);
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
