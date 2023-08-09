@component('mail::message')
    # Welcome To Interactr Evolution

    Your new Interactr Evolution Account Has Been Created, Please See The Login Details Below:
    Email: {{ $user->email }}
    Password: {{ $password }}

    Login Now @ http://interactrapp.com

    Thanks,
    The Interactr Evolution Team
@endcomponent
