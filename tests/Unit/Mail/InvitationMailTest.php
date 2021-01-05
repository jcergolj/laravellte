<?php

namespace Tests\Unit\Mail;

use App\Mail\InvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/** @see \App\Mail\InvitationMail */
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

        $mail->assertSeeInHtml(htmlspecialchars($signedUrl));
    }

    /** @test */
    public function email_has_a_subject()
    {
        $mail = new InvitationMail(create_user(), Carbon::tomorrow());
        $this->assertNotNull($mail->build()->subject);
    }

    /** @test */
    public function email_has_a_sender()
    {
        $mail = new InvitationMail(create_user(), Carbon::tomorrow());
        $this->assertTrue($mail->build()->hasFrom('no-replay@laravellte.com', 'laravellte'));
    }
}
