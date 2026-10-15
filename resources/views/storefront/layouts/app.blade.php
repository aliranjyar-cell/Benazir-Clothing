
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AURELLE')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f3ed] font-sans text-[#30251f] antialiased">

    <div class="bg-[#35251f] px-3 py-2.5 text-center text-[9px] uppercase tracking-[0.15em] text-[#f9eee3] sm:text-[10px] sm:tracking-[0.18em]">
        A considered wardrobe, delivered to your door · Pakistan
    </div>

    <header class="absolute left-0 top-[34px] z-50 w-full border-b border-white/25 text-white">
        <nav class="mx-auto flex max-w-[1500px] items-center justify-between gap-4 px-5 py-5 sm:px-8 lg:px-16">

            <button id="menuToggle"
                    type="button"
                    aria-label="Open navigation"
                    aria-expanded="false"
                    class="inline-flex items-center text-2xl md:hidden">
                ☰
            </button>

            <div id="navLinks"
                 class="absolute left-0 top-full hidden w-full flex-col bg-[#fffdf9] px-6 py-5 text-[#35251f] shadow-xl md:static md:flex md:w-auto md:flex-row md:items-center md:gap-7 md:bg-transparent md:p-0 md:text-white md:shadow-none">

                <a href="{{ route('store.shop') }}" class="border-b border-[#35251f]/10 py-3 text-xs uppercase tracking-widest md:border-0 md:py-0">Collections</a>
                <a href="{{ route('store.shop') }}" class="border-b border-[#35251f]/10 py-3 text-xs uppercase tracking-widest md:border-0 md:py-0">Shop</a>
                <a href="{{ route('store.about') }}" class="border-b border-[#35251f]/10 py-3 text-xs uppercase tracking-widest md:border-0 md:py-0">Our Story</a>
                <a href="{{ route('store.contact') }}" class="py-3 text-xs uppercase tracking-widest md:py-0">Contact</a>
            </div>

            <a href="{{ route('store.home') }}"
               class="font-serif text-2xl tracking-[0.16em] sm:text-3xl">
                AURELLE
            </a>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('store.shop') }}" aria-label="Search">⌕</a>
                <a href="#" aria-label="Wishlist">♡</a>
                <a href="#" aria-label="Shopping bag">Bag</a>
            </div>

        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    @include('storefront.layouts.footer')

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        menuToggle?.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
            menuToggle.setAttribute(
                'aria-label',
                isOpen ? 'Open navigation' : 'Close navigation'
            );
            menuToggle.textContent = isOpen ? '☰' : '✕';
        });

        navLinks?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    navLinks.classList.add('hidden');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    menuToggle.textContent = '☰';
                }
            });
        });
    </script>

</body>
</html>
