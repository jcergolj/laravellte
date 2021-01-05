<?php

namespace Tests\Unit\Mail;

use App\Mail\PasswordChangedMail;
use Tests\TestCase;

/** @see \App\Mail\PasswordChangedMail */
class PasswordChangedMailTest extends TestCase
{
    /** @test */
    public function email_contains_password_changed_text()
    {
        $mail = new PasswordChangedMail();
        $mail->assertSeeInText('your password has been changed');
    }

    /** @test */
    public function email_has_a_subject()
    {
        $mail = new PasswordChangedMail();
        $this->assertEquals('Security notification regarding your password', $mail->build()->subject);
    }

    /** @test */
    public function email_has_a_sender()
    {
        $mail = new PasswordChangedMail();
        $this->assertTrue($mail->build()->hasFrom('no-replay@laravellte.com', 'laravellte'));
    }
}
