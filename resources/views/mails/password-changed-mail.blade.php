@component('mail::message')
Hey, 
<br/>
Just want to let you know, that your password has been changed. 
<br/>
<br/>
<b>If you haven't changed it please contact us.</b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
