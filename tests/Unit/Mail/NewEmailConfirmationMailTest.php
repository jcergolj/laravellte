<?php

namespace Tests\Unit\Mail;

use App\Mail\NewEmailConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/** @see \App\Mail\NewEmailConfirmationMail */
class NewEmailConfirmationMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_contains_link()
    {
        $user = create_user();

        $validUntil = Carbon::tomorrow();
        $mail = new NewEmailConfirmationMail($user, $validUntil, 'joe@example.com');

        $signedUrl = URL::temporarySignedRoute(
            'confirmed-emails.store',
            $validUntil,
            ['user' => $user->id, 'new_email' => 'joe@example.com']
        );

        $mail->assertSeeInHtml(htmlspecialchars($signedUrl));
        $mail->assertSeeInText('joe@example.com');
    }

    /** @test */
    public function email_has_a_subject()
    {
        $mail = new NewEmailConfirmationMail(create_user(), Carbon::tomorrow(), 'joe@example.com');
        $this->assertNotNull($mail->build()->subject);
    }

    /** @test */
    public function email_has_a_sender()
    {
        $mail = new NewEmailConfirmationMail(create_user(), Carbon::tomorrow(), 'joe@example.com');
        $this->assertTrue($mail->build()->hasFrom('no-replay@laravellte.com', 'laravellte'));
    }
}
