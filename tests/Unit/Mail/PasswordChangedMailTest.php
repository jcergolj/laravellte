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

        $rendered = $mail->render();

        $this->assertStringContainsString('your password has been changed', $rendered);
    }

    /** @test */
    public function email_has_a_subject()
    {
        $this->assertEquals('Security notification regarding your password', (new PasswordChangedMail())->build()->subject);
    }
}
