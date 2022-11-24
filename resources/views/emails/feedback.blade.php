<x-mail::message>
    <h2>{{ $name }}</h2> пишет:<br>

{!! $body !!}
    <br>
    @if($tel)
        <br>
        Тел: {{ $tel }}
    @endif
    @if($email)
        <br>
        Email: <a href="mailto:{{ $email }}">{{ $email }}</a>
    @endif

</x-mail::message>
