@component('mail::message')
#Sofra Reset Password<br>
Hello {{$user->name}} 

<p> Your reset code is : {{$user->pin_code}} </p>

@component('mail::button', ['url' => 'http://ipda3.com', 'color' => 'success'])
Reset

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
 