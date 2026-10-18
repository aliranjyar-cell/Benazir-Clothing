<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Back soon · {{ $storeSettings['store_name'] }}</title>

    @if (!empty($storeSettings['store_favicon']))
        <link rel="icon" href="{{ asset('storage/' . $storeSettings['store_favicon']) }}" />
    @endif

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&amp;family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />

    <style>
        :root {
            --primary: #561922;
            --secondary: #745a27;
            --surface: #faf7f2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: var(--surface);
            color: var(--primary);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        main {
            max-width: 34rem;
            text-align: center;
        }

        .logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
            margin: 0 auto 1.5rem;
            display: block;
        }

        .eyebrow {
            font-size: 0.7rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--secondary);
            font-weight: 600;
            margin: 0 0 1rem;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 500;
            line-height: 1.15;
            margin: 0 0 1rem;
        }

        h1 em {
            color: var(--secondary);
            font-weight: 400;
        }

        p.message {
            color: rgba(86, 25, 34, 0.7);
            line-height: 1.7;
            margin: 0 auto 2rem;
            max-width: 28rem;
        }

        .contact {
            border-top: 1px solid rgba(116, 90, 39, 0.25);
            padding-top: 1.5rem;
            font-size: 0.85rem;
            color: rgba(86, 25, 34, 0.6);
            line-height: 1.9;
        }

        .contact a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <main>
        @if (!empty($storeSettings['store_logo']))
            <img class="logo" src="{{ asset('storage/' . $storeSettings['store_logo']) }}"
                alt="{{ $storeSettings['store_name'] }}" />
        @endif

        <p class="eyebrow">{{ $storeSettings['store_name'] }}</p>

        <h1>We are preparing something <em>beautiful</em>.</h1>

        <p class="message">
            Our online store is currently closed for a brief period of maintenance.
            Please check back shortly — thank you for your patience.
        </p>

        @if (!empty($storeSettings['contact_email']) || !empty($storeSettings['contact_phone']))
            <div class="contact">
                For enquiries:
                @if (!empty($storeSettings['contact_email']))
                    <a href="mailto:{{ $storeSettings['contact_email'] }}">{{ $storeSettings['contact_email'] }}</a>
                @endif
                @if (!empty($storeSettings['contact_email']) && !empty($storeSettings['contact_phone']))
                    ·
                @endif
                @if (!empty($storeSettings['contact_phone']))
                    <a href="tel:{{ $storeSettings['contact_phone'] }}">{{ $storeSettings['contact_phone'] }}</a>
                @endif
            </div>
        @endif
    </main>
</body>

</html>