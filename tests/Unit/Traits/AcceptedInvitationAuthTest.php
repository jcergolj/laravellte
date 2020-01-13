<?php

namespace Tests\Unit\Traits;

use App\Models\User;
use App\Traits\AcceptedInvitationAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/**
 * @see \App\Traits\AcceptedInvitationAuth
 */
class AcceptedInvitationAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorize_passes()
    {
        $user = factory(User::class)->create([
            'password' => null,
        ]);

        $acceptedInvitation = new class {
            use AcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, $user->id);

        $url->setRequest($request);

        $this->assertNull($acceptedInvitation->authorizeInvitation($request, $user));
    }

    /** @test */
    public function authorize_fails_invalid_signature()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);
        $this->expectErrorMessage('The link has already been used.');

        $user = create_user();
        $acceptedInvitation = new class {
            use AcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, $user->id.'invalid');

        $url->setRequest($request);

        $this->assertNull($acceptedInvitation->authorizeInvitation($request, $user));
    }

    /** @test */
    public function authorize_fails_user_does_not_exists()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);
        $this->expectErrorMessage('User was not found.');

        $user = create_user();
        $acceptedInvitation = new class {
            use AcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, '100');

        $url->setRequest($request);

        $acceptedInvitation->authorizeInvitation($request, null);
    }

    /** @test */
    public function authorize_fails_user_password_is_set()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);
        $this->expectErrorMessage('The link has already been used.');

        $user = create_user();
        $acceptedInvitation = new class {
            use AcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, $user->id);

        $url->setRequest($request);

        $acceptedInvitation->authorizeInvitation($request, $user);
    }

    private function createRequestWithSignedRoute($url, $userId)
    {
        $temporarySignedUri = $url->temporarySignedRoute(
            'accepted-invitations.create',
            Carbon::tomorrow(),
            ['user' => $userId]
        );

        $url_components = parse_url($temporarySignedUri);
        parse_str($url_components['query'], $params);

        return Request::createFromBase(
            SymfonyRequest::create(
                url('accepted-invitations/create'),
                'GET',
                ['expires' => $params['expires'], 'user' => $userId, 'signature' => $params['signature']]
            )
        );
    }
}
