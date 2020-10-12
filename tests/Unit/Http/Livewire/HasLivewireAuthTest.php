<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\HasLivewireAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function user_without_allowed_permission_cannot_access_component()
    {
        $this->withoutExceptionHandling();

        $customClass = new class() {
            use HasLivewireAuth;
        };

        $customClass->permissionName = 'users.index';

        $this->expectException(AuthorizationException::class);

        $this->actingAs(create_user());

        $customClass->hydrate();
    }

    /** @test */
    public function route_name_is_extracted_from_component_name()
    {
        $customClass = new class() {
            use HasLivewireAuth;

            public static function getName()
            {
                return 'index-user-component';
            }
        };

        $this->actingAs(create_admin());

        $customClass->hydrate();

        $this->assertSame('users.index', $customClass->permissionName);
    }

    /** @test */
    public function set_model_method_is_called_if_exists()
    {
        $customClass = new class() {
            use HasLivewireAuth;

            /** @var bool */
            public $called = false;

            protected function setModel()
            {
                $this->called = true;
            }

            protected function authorize($string, $array)
            {
                return true;
            }
        };

        $customClass->permissionName = 'name';

        $this->actingAs(create_user());

        $customClass->hydrate();

        $this->assertTrue($customClass->called);
    }
}
