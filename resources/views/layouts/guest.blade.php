<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ClothesShopping') }} — Admin Sign In</title>

    {{-- Fonts for the Punjabi fashion design --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Application CSS and JavaScript --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts used by the login page --}}
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            min-height: 0;
            margin: 0;
            overflow: hidden;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }

        .font-sans {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
    </style>
</head>

<body class="h-screen overflow-hidden antialiased">
    {{ $slot }}
</body>

</html>
