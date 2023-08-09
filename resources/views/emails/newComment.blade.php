
@component('mail::message')
    You have new comment for the project: {{ $project->title }}

    {{$comment->text}}

    Checkout the link to see now https://interactrapp.com/share/{{ $hash }}

    Thanks,
    The Interactr Team
@endcomponent