<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\AcceptedInvitation;
use App\Models\User;
use App\Traits\AcceptedInvitationAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

/** @see \App\Http\Livewire\AcceptedInvitation */
class AcceptedInvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'password' => null,
        ]);
    }

    /** @test */
    public function assert_component_uses_accepted_invitation_auth_trait()
    {
        $this->assertContains(AcceptedInvitationAuth::class, class_uses(AcceptedInvitation::class));
    }

    /** @test */
    public function assert_authorize_invitation_is_called()
    {
        $livewireComponent = $this->getMockBuilder(AcceptedInvitation::class)
            ->disableOriginalConstructor()
            ->setMethods(['authorizeInvitation'])
            ->getMock();

        $livewireComponent->expects($this->once())
            ->method('authorizeInvitation');

        $livewireComponent->mount(new Request, $this->user);
    }

    /** @test */
    public function user_can_set_up_new_password()
    {
        $request = $this->buildRequest($this->user);
        Livewire::test(AcceptedInvitation::class, ['request' => $request, 'user' => $this->user])
            ->set('newPassword', 'new-password')
            ->set('newPasswordConfirmation', 'new-password')
            ->call('submit')
            ->assertRedirect(route('home.index'));

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $request = $this->buildRequest($this->user);
        Livewire::test(AcceptedInvitation::class, ['request' => $request, 'user' => $this->user])
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors([$clientFormInput => $rule]);

        $this->assertNull($this->user->fresh()->password);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test new password is required' => ['newPassword', '', 'app\_rules\_password_rule'],
            'Test password must be greater than 7' => ['newPassword', '1234567', 'app\_rules\_password_rule'],
        ];
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $request = $this->buildRequest($this->user);
        Livewire::test(AcceptedInvitation::class, ['request' => $request, 'user' => $this->user])
            ->set('newPassword', 'new-password')
            ->set('newPasswordConfirmation', 'invalid-password')
            ->call('submit')
            ->assertHasErrors(['newPassword' => 'app\_rules\_password_rule']);

        $this->assertNull($this->user->fresh()->password);
    }

    /** @test */
    public function abort_if_users_password_not_null()
    {
        $user = factory(User::class)->create(['password' => 'not-null']);

        $request = $this->buildRequest($user);
        Livewire::test(AcceptedInvitation::class, ['request' => $request, 'user' => $user])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Build request.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Request
     */
    protected function buildRequest($user)
    {
        $url = app()->make(UrlGenerator::class);
        $temporarySignedUri = $url->temporarySignedRoute(
            'accepted-invitations.create',
            Carbon::tomorrow(),
            ['user' => $user]
        );

        $url_components = parse_url($temporarySignedUri);
        parse_str($url_components['query'], $params);

        return Request::createFromBase(
            SymfonyRequest::create(
                url('accepted-invitations/create'),
                'GET',
                ['expires' => $params['expires'], 'user' => $user->id, 'signature' => $params['signature']]
            )
        );
    }
}
