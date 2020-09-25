<?php

namespace Tests\Unit\Http;

use App\Http\HasAcceptedInvitationAuth;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/** @see \App\Http\HasAcceptedInvitationAuth */
class AcceptedInvitationAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorize_passes()
    {
        $user = UserFactory::new()->create([
            'password' => null,
        ]);

        $acceptedInvitation = new class {
            use HasAcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, $user->id);

        $url->setRequest($request);

        $this->assertNull($acceptedInvitation->authorizeInvitation($request, $user));
    }

    /** @test */
    public function authorize_fails_for_invalid_signature()
    {
        $this->expectException(HttpException::class);
        $this->expectErrorMessage('The link has already been used.');

        $user = create_user();
        $acceptedInvitation = new class {
            use HasAcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, $user->id.'invalid');

        $url->setRequest($request);

        $this->assertNull($acceptedInvitation->authorizeInvitation($request, $user));
    }

    /** @test */
    public function authorize_fails_if_user_does_not_exists()
    {
        $this->expectException(HttpException::class);
        $this->expectErrorMessage('User was not found.');

        $user = create_user();
        $acceptedInvitation = new class {
            use HasAcceptedInvitationAuth;
        };

        $url = app()->make(UrlGenerator::class);

        $request = $this->createRequestWithSignedRoute($url, '100');

        $url->setRequest($request);

        $acceptedInvitation->authorizeInvitation($request, null);
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
