@props([
'title'
])
{{-- При подключении аналитики проверь адреса Content-Security-Policy: script-src --}}
@php
    header('X-Frame-Options: sameorigin');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1;mode=block');
    //header('Content-Security-Policy: default-src \'self\' \'unsafe-inline\' \'unsafe-eval\'; img-src \'self\' data:; style-src \'self\' \'unsafe-inline\'; script-src \'self\' \'unsafe-inline\' *.google.com www.gstatic.com; frame-src *.google.com gstatic.com');
    header('Permissions-Policy: camera=(), display-capture=(), geolocation=(), microphone=()');
    header('Referrer-Policy: origin-when-cross-origin');
    header('Strict-Transport-Security: max-age=31536000');

@endphp
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/512x512.png')  }}"/>
    <meta id="_csrf_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="HandheldFriendly" content="true">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{ asset('spa/css/app.css')  }}" rel="stylesheet">
<!--    @vite([
    'resources/css/app.css',
    'resources/css/styles.css',
    ])-->
    <script src="https://www.google.com/recaptcha/api.js?render=6LftRl0aAAAAAHJDSCKdThCy1TaS9OwaGNPSgWyC"></script>
<body>
{{ $slot }}
<!--@vite([
'resources/js/app.js',
])-->
<script src="{{ asset('spa/js/chunk-vendors.js') }}"></script>
<script src="{{ asset('spa/js/app.js') }}"></script>
</body>
</html>
