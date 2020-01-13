<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\AcceptedInvitationController;
use App\Models\User;
use App\Traits\AcceptedInvitationAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AcceptedInvitationController
 */
class AcceptedInvitationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_controller_uses_accepted_invitation_auth_trait()
    {
        $this->assertContains(AcceptedInvitationAuth::class, class_uses(AcceptedInvitationController::class));
    }

    /** @test */
    public function assert_authorize_invitation_is_called()
    {
        $controller = $this->getMockBuilder(AcceptedInvitationController::class)
            ->setMethods(['authorizeInvitation'])
            ->getMock();

        $controller->expects($this->once())
            ->method('authorizeInvitation');

        $controller->create(new Request);
    }

    /** @test */
    public function user_can_view_create_page()
    {
        $user = factory(User::class)->create([
            'password' => null,
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'accepted-invitations.create',
            Carbon::tomorrow(),
            ['user' => $user->id]
        );

        $this->get($signedUrl)
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('accepted-invitations.create')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }
}
