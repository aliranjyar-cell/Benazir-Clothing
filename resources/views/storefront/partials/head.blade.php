<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="web_standard" name="shell-type" />
    <link rel="stylesheet" href="{{ asset('fontawesome-free-7.2.0-web/css/all.min.css') }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&amp;family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />

    <title>@yield('title', config('storefront.store_name', 'Benazir Clothes') . ' · ' . config('storefront.store_tagline', 'Punjabi Couture'))</title>

    @if (config('storefront.store_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('storefront.store_favicon')) }}" />
    @endif

    <style>
        @layer base {

            html,
            body {
                margin: 0;
                padding: 0;
            }

            body {
                overscroll-behavior: none;
            }

            main>:first-child {
                margin-top: 0 !important;
            }

            main>:last-child {
                margin-bottom: 0 !important;
            }
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 20;
        }

        @layer base {

            html,
            body {
                margin: 0;
                padding: 0;
            }

            body {
                overscroll-behavior: none;
            }

            main>:first-child {
                margin-top: 0 !important;
            }

            main>:last-child {
                margin-bottom: 0 !important;
            }
        }

        ::-webkit-scrollbar {
            display: none;
        }


        /* Initial hidden state */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition:
                opacity 700ms ease,
                transform 700ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        /* Animation directions */
        .reveal-left {
            transform: translateX(-40px);
        }

        .reveal-right {
            transform: translateX(40px);
        }

        .reveal-up {
            transform: translateY(32px);
        }

        /* Visible state */
        .reveal.is-visible {
            opacity: 1;
            transform: translate(0, 0);
        }

        /* Respect reduced-motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .reveal {
                opacity: 1;
                transform: none;
                transition: none;
                will-change: auto;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script id="tailwind-config">
        window.tailwind = window.tailwind || {};
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error-container": "#ffdad6",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed-variant": "#5a4311",
                        "secondary-fixed": "#ffdea5",
                        "surface-bright": "#fff8f6",
                        "surface": "#fff8f6",
                        "on-primary": "#ffffff",
                        "error": "#ba1a1a",
                        "on-error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#444932",
                        "on-secondary-container": "#795f2b",
                        "inverse-on-surface": "#ffede7",
                        "inverse-surface": "#392e2a",
                        "on-secondary-fixed": "#261900",
                        "outline": "#867273",
                        "on-background": "#231916",
                        "primary-container": "#722f37",
                        "on-tertiary-container": "#b0b597",
                        "secondary": "#745a27",
                        "surface-variant": "#f1dfd9",
                        "secondary-container": "#ffdb9b",
                        "tertiary-fixed": "#e1e5c6",
                        "on-surface-variant": "#534343",
                        "surface-container-high": "#f7e4de",
                        "primary-fixed": "#ffdadb",
                        "on-surface": "#231916",
                        "surface-container": "#fdeae4",
                        "secondary-fixed-dim": "#e4c284",
                        "surface-container-low": "#fff1ec",
                        "tertiary-fixed-dim": "#c4c9ab",
                        "on-tertiary-fixed": "#191d0a",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed": "#3c0610",
                        "on-tertiary": "#ffffff",
                        "background": "#fff8f6",
                        "surface-tint": "#92484f",
                        "primary-fixed-dim": "#ffb2b8",
                        "tertiary-container": "#424730",
                        "on-primary-fixed-variant": "#753139",
                        "surface-container-highest": "#f1dfd9",
                        "inverse-primary": "#ffb2b8",
                        "on-error": "#ffffff",
                        "primary": "#561922",
                        "outline-variant": "#d8c1c2",
                        "on-primary-container": "#f499a0",
                        "tertiary": "#2c311b",
                        "surface-dim": "#e8d6d0"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "space-xl": "2.5rem",
                        "space-sm": "0.5rem",
                        "gutter-lg": "2.5rem",
                        "gutter-sm": "1rem",
                        "space-xs": "0.25rem",
                        "margin": "1.5rem",
                        "space-lg": "1.5rem",
                        "space-3xl": "6rem",
                        "space-2xl": "4rem",
                        "margin-md": "3rem",
                        "margin-lg": "5rem",
                        "gutter": "1.5rem",
                        "space-md": "1rem"
                    },
                    "fontFamily": {
                        "display-hero": ["Playfair Display"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "headline-lg": ["Playfair Display"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "headline-sm": ["Playfair Display"],
                        "display-hero-mobile": ["Playfair Display"],
                        "headline-md": ["Playfair Display"],
                        "label-price": ["Plus Jakarta Sans"],
                        "title-editorial": ["Playfair Display"],
                        "label-uppercase": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "display-hero": ["3.5rem", {
                            "lineHeight": "1.15",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "400"
                        }],
                        "body-sm": ["0.8125rem", {
                            "lineHeight": "1.5",
                            "fontWeight": "400"
                        }],
                        "headline-lg": ["2.5rem", {
                            "lineHeight": "1.2",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "500"
                        }],
                        "headline-lg-mobile": ["1.75rem", {
                            "lineHeight": "1.25",
                            "fontWeight": "500"
                        }],
                        "body-lg": ["1.125rem", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }],
                        "headline-sm": ["1.25rem", {
                            "lineHeight": "1.4",
                            "fontWeight": "600"
                        }],
                        "display-hero-mobile": ["2.25rem", {
                            "lineHeight": "1.2",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["1.75rem", {
                            "lineHeight": "1.3",
                            "fontWeight": "500"
                        }],
                        "label-price": ["1rem", {
                            "lineHeight": "1.4",
                            "letterSpacing": "0.02em",
                            "fontWeight": "600"
                        }],
                        "title-editorial": ["1.125rem", {
                            "lineHeight": "1.5",
                            "fontWeight": "400"
                        }],
                        "label-uppercase": ["0.6875rem", {
                            "lineHeight": "1.2",
                            "letterSpacing": "0.14em",
                            "fontWeight": "600"
                        }],
                        "body-md": ["0.9375rem", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }]
                    }
                }
            }
        }
    </script>
</head>
