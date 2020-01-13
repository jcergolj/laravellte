<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class NewEmailConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var \App\Models\User */
    public $user;

    /** @var \Illuminate\Support\Carbon */
    public $validUntil;

    /** @var string */
    protected $signedUrl;

    /** @var string */
    protected $newEmail;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Support\Carbon  $validUntil
     * @param  string  $email
     * @return void
     */
    public function __construct(User $user, Carbon $validUntil, $newEmail)
    {
        $this->user = $user;
        $this->validUntil = $validUntil;
        $this->newEmail = $newEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Please confirm your new email address.')
            ->markdown('mails.new-email-confirmation-mail', [
                'signedUrl' => $this->createTemporarySignedRoute(),
                'newEmail' => $this->newEmail,
            ]);
    }

    private function createTemporarySignedRoute()
    {
        return URL::temporarySignedRoute(
            'confirmed-emails.store',
            $this->validUntil,
            ['user' => $this->user->id, 'new_email' => $this->newEmail]
        );
    }
}
