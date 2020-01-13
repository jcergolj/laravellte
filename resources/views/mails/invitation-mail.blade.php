@component('mail::message')
Hey, you have been invited to join.

@component('mail::button', ['url' => $signedUrl])
    Set up a new password and join
@endcomponent

Best Regards, {{ config('app.name') }}
@endcomponent