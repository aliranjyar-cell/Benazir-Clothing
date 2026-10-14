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

        /* Custom cursor */
        #heritage-cursor {
            position: fixed;
            top: 0;
            left: 0;
            width: 26px;
            height: 26px;
            z-index: 999999;
            pointer-events: none;
            display: none;
            transform: translate(-4px, -4px);
        }

        #heritage-cursor svg {
            display: block;
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 1px 2px rgba(63, 15, 29, 0.25));
        }

        @media (hover: hover) and (pointer: fine) {
            #heritage-cursor {
                display: block;
            }

            body,
            a,
            button,
            input,
            textarea,
            select,
            label {
                cursor: none !important;
            }
        }

        @media (hover: none),
        (pointer: coarse) {
            #heritage-cursor {
                display: none !important;
            }

            body,
            a,
            button,
            input,
            textarea,
            select,
            label {
                cursor: auto !important;
            }
        }
    </style>
</head>

<body class="h-screen overflow-hidden antialiased">
    <div id="heritage-cursor" aria-hidden="true">
        <svg viewBox="0 0 40 40" fill="none">
            <path d="M8 4V29L14 23L19 34L23 32L18 21H27L8 4Z" fill="#C6A15B" stroke="#F8F3E9" stroke-width="1.4"
                stroke-linejoin="round" />
            <path d="M30 3L32 9L38 11L32 13L30 19L28 13L22 11L28 9L30 3Z" fill="#C6A15B" />
        </svg>
    </div>
    {{ $slot }}
</body>

<script>
    (() => {
        const cursor = document.getElementById('heritage-cursor');

        if (!cursor || !window.matchMedia(
                '(hover: hover) and (pointer: fine)'
            ).matches) {
            return;
        }

        document.addEventListener('mousemove', (event) => {
            cursor.style.left = `${event.clientX}px`;
            cursor.style.top = `${event.clientY}px`;
        });

        document.addEventListener('mouseleave', () => {
            cursor.style.visibility = 'hidden';
        });

        document.addEventListener('mouseenter', () => {
            cursor.style.visibility = 'visible';
        });
    })();
</script>

</html>
