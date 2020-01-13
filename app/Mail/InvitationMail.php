<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class InvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var \App\Models\User */
    public $user;

    /** @var \Illuminate\Support\Carbon */
    public $validUntil;

    /** @var string */
    protected $signedUrl;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Support\Carbon  $validUntil
     * @return void
     */
    public function __construct(User $user, Carbon $validUntil)
    {
        $this->user = $user;
        $this->validUntil = $validUntil;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Welcome')
            ->markdown('mails.invitation-mail', ['signedUrl' => $this->createTemporarySignedRoute()]);
    }

    private function createTemporarySignedRoute()
    {
        return URL::temporarySignedRoute(
            'accepted-invitations.create',
            $this->validUntil,
            ['user' => $this->user->id]
        );
    }
}
