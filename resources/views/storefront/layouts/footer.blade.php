
<footer class="overflow-hidden bg-[#35251f] px-5 pb-7 pt-14 text-[#f8f3ed] sm:px-8 lg:px-16 lg:pt-20">

    <div class="mx-auto flex max-w-[1500px] flex-col gap-4 border-b border-white/20 pb-7 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-[10px] uppercase tracking-[0.22em] text-[#d7b59e]">
            Clothing for your own kind of beautiful
        </p>

        <p class="text-xs text-[#c6b5a8]">
            Thoughtfully considered. Made to be lived in.
        </p>
    </div>

    <a href="{{ route('store.home') }}"
       class="mx-auto mt-8 block text-center font-serif text-[17vw] leading-none tracking-[-0.07em] text-[#f5e8dc] transition-colors duration-300 hover:text-[#d7b59e] sm:text-[16vw] lg:text-[17.5vw]">
        AURELLE
    </a>

    <nav aria-label="Footer navigation"
         class="mx-auto my-10 flex max-w-5xl flex-wrap justify-center gap-x-6 gap-y-5 sm:gap-x-10">

        <a href="{{ route('store.shop') }}" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Shop all</a>
        <a href="{{ route('store.shop') }}" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Unstitched</a>
        <a href="{{ route('store.shop') }}" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Ready to wear</a>
        <a href="{{ route('store.about') }}" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Our story</a>
        <a href="{{ route('store.contact') }}" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Contact</a>
        <a href="#" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Shipping & returns</a>
        <a href="#" class="text-[10px] uppercase tracking-widest text-[#d7c6b9] transition hover:text-white">Privacy</a>

    </nav>

    <div class="mx-auto flex max-w-[1500px] flex-col items-center justify-between gap-4 border-t border-white/20 pt-6 text-center text-[10px] leading-6 text-[#b7a59a] sm:flex-row">

        <p>© {{ date('Y') }} AURELLE. All rights reserved.</p>

        <div class="flex gap-5">
            <a href="#" class="transition hover:text-white">Instagram</a>
            <a href="#" class="transition hover:text-white">Pinterest</a>
            <a href="#" class="transition hover:text-white">Facebook</a>
        </div>

        <p>Made with intention · Pakistan</p>

    </div>

</footer>
