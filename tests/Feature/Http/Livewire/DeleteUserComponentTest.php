<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\DeleteUserComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\DeleteUserComponent */
class DeleteUserComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function assert_delete_user_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(DeleteUserComponent::class));
    }

    /** @test */
    public function user_cannot_delete_himself()
    {
        Livewire::actingAs($this->admin)
            ->test(DeleteUserComponent::class, ['user' =>  $this->admin])
            ->call('destroy')
            ->assertForbidden();
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = UserFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(DeleteUserComponent::class, ['user' =>  $user])
            ->call('destroy')
            ->assertEmitted('entity-deleted')
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(User::find($user->id));
    }
}
