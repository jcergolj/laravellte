@component('mail::message')
Hey, please confirm your new email by clicking the link below.

@component('mail::button', ['url' => $signedUrl])
    Confirm {{ $newEmail }} email address.
@endcomponent

Best Regards, {{ config('app.name') }}
@endcomponent