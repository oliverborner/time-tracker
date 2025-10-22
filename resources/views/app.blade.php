<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'TimeTracker') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div id="app"></div>

    {{-- Optional: CSRF-Token für Axios --}}
{{--     <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script> --}}

</body>
</html>
