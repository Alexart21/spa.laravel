@props([
'title'
])
{{-- При подключении аналитики проверь адреса Content-Security-Policy: script-src --}}
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/512x512.png')  }}"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="HandheldFriendly" content="true">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{ asset('css/test/bootstrap.min.css')  }}" rel="stylesheet">
    <link href="{{ asset('css/datalist.css')  }}" rel="stylesheet">
<body>
<!-- начало основной контент -->
<div class="inc-out">
    <main id="inc" class="container">
        {{ $slot }}
    </main>
</div>
<!-- конец основной контент -->
<script src="{{ asset('js/jquery3.6.0.min.js') }}"></script>
<script src="{{ asset('js/datalist.js') }}"></script>
</body>
</html>
