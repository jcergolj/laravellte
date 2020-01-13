<?php

namespace Tests\Unit\Mail;

use App\Mail\InvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/** @see \App\Mails\InvitationMail */
class InvitationMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_contains_link()
    {
        $user = create_user();

        $validUntil = Carbon::tomorrow();
        $mail = new InvitationMail($user, $validUntil);

        $signedUrl = URL::temporarySignedRoute(
            'accepted-invitations.create',
            $validUntil,
            ['user' => $user->id]
        );

        $rendered = $mail->render();

        $this->assertStringContainsString(htmlspecialchars($signedUrl), $rendered);
    }

    /** @test */
    public function email_has_a_subject()
    {
        $mail = new InvitationMail(create_user(), Carbon::tomorrow());
        $this->assertNotNull($mail->build()->subject);
    }
}
