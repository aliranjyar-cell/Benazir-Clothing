<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('storefront.partials.head')

<body class="bg-surface font-body-md text-body-md text-on-surface antialiased">
    @include('storefront.partials.header')

    <main class="w-full pt-36 bg-surface min-h-[calc(100vh-28rem)]">
        @include('storefront.partials.flash')
        @yield('content')
    </main>

    @hasSection('footer')
        @yield('footer')
    @else
        @include('storefront.partials.footer')
    @endif
    <script src="{{ asset('js/storefront-shop.js') }}" defer></script>
</body>

</html>
