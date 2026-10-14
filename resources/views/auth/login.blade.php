<x-guest-layout>
    <div
        class="min-h-screen bg-[#F8F3E9] text-[#292526] font-sans antialiased selection:bg-[#651E32] selection:text-white">
        <main class="min-h-screen flex flex-col lg:flex-row relative">

            {{-- LEFT COLUMN: BRAND EXPERIENCE --}}
            <section
                class="relative lg:w-[54%] w-full min-h-[300px] lg:min-h-screen flex flex-col justify-between overflow-hidden bg-[#3F0F1D] text-white p-6 sm:p-10 lg:p-14">

                {{-- Background image and overlays --}}
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/loginimage.jpg') }}"
                        alt="Punjabi clothing featuring traditional embroidery"
                        class="w-full h-full object-cover object-top opacity-85 transition-transform duration-1000">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-[#280812] via-[#4A1424]/60 to-[#280812]/75 mix-blend-multiply">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#280812]/90 via-[#4A1424]/30 to-transparent">
                    </div>

                    <div class="absolute inset-4 lg:inset-8 border border-[#C6A15B]/25 pointer-events-none rounded-sm">
                    </div>
                    <div
                        class="absolute top-4 left-4 w-4 h-4 border-t-2 border-l-2 border-[#C6A15B] pointer-events-none">
                    </div>
                    <div
                        class="absolute top-4 right-4 w-4 h-4 border-t-2 border-r-2 border-[#C6A15B] pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-4 left-4 w-4 h-4 border-b-2 border-l-2 border-[#C6A15B] pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-4 right-4 w-4 h-4 border-b-2 border-r-2 border-[#C6A15B] pointer-events-none">
                    </div>
                </div>

                {{-- Brand header --}}
                <header class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div
                            class="w-10 h-10 rounded-full p-0.5 bg-white/10 backdrop-blur-md border border-[#C6A15B]/40 flex items-center justify-center shadow-lg">
                            <span class="font-serif text-xl text-[#DFBF7F]">C</span>
                        </div>

                        <div>
                            <span
                                class="block text-xl lg:text-2xl font-serif tracking-[0.2em] uppercase font-semibold text-[#F8F3E9]">
                                Benazir Clothing
                            </span>
                            <span class="block text-[10px] tracking-[0.3em] uppercase text-[#DFBF7F] font-medium">
                                Haute Couture &amp; Heritage Atelier
                            </span>
                        </div>
                    </div>

                    <div
                        class="hidden sm:inline-flex items-center px-3 py-1 rounded-r-full animate-pulse bg-white/10 backdrop-blur-md border border-[#C6A15B]/30 text-[11px] font-medium tracking-wider text-[#F8F3E9] uppercase">
                        <span class="w-1.5 h-1.5 rounded-r-full bg-[#C6A15B] mr-2"></span>
                        Admin Portal
                    </div>
                </header>

                {{-- Editorial text --}}
                <div class="relative z-10 mt-12 lg:mt-auto pt-8">
                    <div
                        class="inline-flex items-center space-x-2 text-[#DFBF7F] text-xs tracking-[0.25em] uppercase font-medium mb-3">
                        <span class="w-8 h-px bg-[#DFBF7F]"></span>
                        <span>Artisanal Heritage Management</span>
                    </div>

                    <h1
                        class="font-serif text-3xl sm:text-4xl lg:text-5xl font-medium tracking-tight text-[#F8F3E9] leading-[1.15] max-w-lg">
                        Tradition, Woven Into
                        <br class="hidden sm:inline">
                        <span class="italic font-normal text-[#DFBF7F]">Every Detail.</span>
                    </h1>

                    <p
                        class="mt-4 text-sm sm:text-base text-[#F8F3E9]/80 font-light tracking-wide max-w-md leading-relaxed">
                        Your brand. Your collections. Your business. Preserving Punjab’s master weaving traditions
                        across fashion retail.
                    </p>

                    <div
                        class="mt-8 pt-6 border-t border-white/15 flex flex-wrap gap-6 items-center text-xs text-[#F8F3E9]/70">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-[#C6A15B]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Secure Admin Access</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-[#C6A15B]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Protected Login</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- RIGHT COLUMN: LOGIN --}}
            <section
                class="relative lg:w-[46%] w-full min-h-screen flex flex-col justify-between items-center p-6 sm:p-10 lg:p-16 bg-[#F8F3E9]">

                {{-- Decorative background --}}
                <div class="absolute inset-0 pointer-events-none opacity-[0.18]"
                    style="background-image: radial-gradient(#C6A15B 0.75px, transparent 0.75px), radial-gradient(#651E32 0.75px, #F8F3E9 0.75px); background-size: 24px 24px; background-position: 0 0, 12px 12px;">
                </div>

                {{-- Top area --}}
                <div class="w-full flex justify-between items-center relative z-10 mb-6 lg:mb-0">
                    <div class="lg:hidden flex items-center space-x-2">
                        <span class="font-serif text-xl text-[#651E32] font-bold">C</span>
                        <span class="font-serif tracking-widest text-[#651E32] font-bold text-sm uppercase">
                            ClothesShopping
                        </span>
                    </div>

                    <div
                        class="ml-auto inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#EFE8DC]/70 border border-[#E8E0D7] text-[11px] font-medium text-[#5B5556]">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span>Admin Portal</span>
                    </div>
                </div>

                {{-- Login card --}}
                <div class="w-full max-w-[420px] my-auto relative z-10">
                    <div
                        class="bg-white rounded-xl shadow-[0_10px_35px_-8px_rgba(41,37,38,0.06),0_2px_10px_-2px_rgba(41,37,38,0.03)] border border-[#E8E0D7] p-8 sm:p-10">

                        {{-- Heading --}}
                        <div class="flex flex-col items-center text-center mb-7">
                            <div
                                class="w-14 h-14 rounded-full bg-[#F8F3E9] border border-[#C6A15B]/40 flex items-center justify-center shadow-inner mb-3.5">
                                <span class="font-serif text-3xl text-[#651E32]">C</span>
                            </div>

                            <h2 class="font-serif text-3xl font-medium tracking-tight text-[#292526]">
                                Welcome Back
                            </h2>

                            <p class="text-[#5B5556] text-sm mt-1.5 font-light">
                                Sign in to manage your store.
                            </p>
                        </div>

                        {{-- Laravel session status --}}
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        {{-- Authentication errors --}}
                        @if ($errors->any())
                            <div class="mb-6 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm"
                                role="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- REAL LARAVEL LOGIN FORM --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            {{-- Username --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="username"
                                        class="block text-xs font-semibold uppercase tracking-wider text-[#292526]">
                                        Username <span class="text-[#651E32]">*</span>
                                    </label>
                                </div>

                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#867F81]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>

                                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                                        placeholder="Enter your username" autocomplete="username" autofocus required
                                        class="block w-full pl-10 pr-4 py-2.5 bg-[#FDFBF7] border border-[#E8E0D7] rounded-lg text-sm text-[#292526] placeholder-[#867F81]/70 focus:outline-none focus:border-[#651E32] focus:ring-2 focus:ring-[#651E32]/10 focus:bg-white transition-colors font-medium">
                                </div>

                                @error('username')
                                    <p class="text-xs text-red-600 mt-1 font-medium" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="password"
                                        class="block text-xs font-semibold uppercase tracking-wider text-[#292526]">
                                        Password <span class="text-[#651E32]">*</span>
                                    </label>
                                </div>

                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#867F81]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>

                                    <input type="password" id="password" name="password"
                                        placeholder="Enter your password" autocomplete="current-password" required
                                        class="block w-full pl-10 pr-11 py-2.5 bg-[#FDFBF7] border border-[#E8E0D7] rounded-lg text-sm text-[#292526] placeholder-[#867F81]/70 focus:outline-none focus:border-[#651E32] focus:ring-2 focus:ring-[#651E32]/10 focus:bg-white transition-colors font-medium">

                                    <button type="button" id="toggle-password" aria-label="Show password"
                                        aria-pressed="false"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#867F81] hover:text-[#651E32] focus:outline-none">
                                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                        <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="text-xs text-red-600 mt-1 font-medium" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Remember me and forgot password --}}
                            <div class="flex items-center justify-between pt-1">
                                <label for="remember"
                                    class="flex items-center space-x-2.5 cursor-pointer select-none">
                                    <input type="checkbox" id="remember" name="remember" value="1"
                                        @checked(old('remember'))
                                        class="w-4 h-4 rounded text-[#651E32] border-[#E8E0D7] focus:ring-[#651E32]/30 focus:ring-2 focus:ring-offset-0 cursor-pointer accent-[#651E32]">

                                    <span class="text-xs text-[#292526] font-medium">
                                        Remember me
                                    </span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-[#651E32] hover:text-[#521727] hover:underline underline-offset-2 transition-colors">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <div class="pt-2">
                                <button type="submit" id="submit-btn"
                                    class="w-full py-3 px-4 bg-[#651E32] hover:bg-[#521727] text-white rounded-lg font-medium text-sm tracking-wide shadow-[0_4px_14px_rgba(101,30,50,0.28)] hover:shadow-[0_6px_20px_rgba(101,30,50,0.36)] focus:outline-none focus:ring-2 focus:ring-[#651E32] focus:ring-offset-2 transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer">
                                    <span id="btn-text">Sign In to Admin</span>

                                    <svg id="btn-arrow" class="w-4 h-4 text-[#DFBF7F]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>

                                    <svg id="btn-spinner" class="w-4 h-4 text-white animate-spin hidden"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </form>

                        {{-- Security note --}}
                        <div
                            class="mt-6 pt-5 border-t border-[#E8E0D7]/70 flex items-center justify-center space-x-2 text-[#5B5556] text-xs">
                            <svg class="w-3.5 h-3.5 text-[#C6A15B] flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>

                            <span class="tracking-wide">
                                Authorized personnel only.
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <footer class="w-full text-center relative z-10 pt-6 lg:pt-0">
                    <p class="text-xs text-[#867F81] font-light">
                        © {{ date('Y') }} ClothesShopping. All rights reserved.
                    </p>
                </footer>
            </section>
        </main>
    </div>

    {{-- Password visibility and submit loading state --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            const loginForm = document.querySelector('form[action="{{ route('login') }}"]');
            const submitButton = document.getElementById('submit-btn');
            const buttonText = document.getElementById('btn-text');
            const buttonArrow = document.getElementById('btn-arrow');
            const buttonSpinner = document.getElementById('btn-spinner');

            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';

                eyeIcon.classList.toggle('hidden', isPassword);
                eyeOffIcon.classList.toggle('hidden', !isPassword);

                togglePassword.setAttribute('aria-pressed', String(isPassword));
                togglePassword.setAttribute(
                    'aria-label',
                    isPassword ? 'Hide password' : 'Show password'
                );
            });

            loginForm.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-90', 'cursor-not-allowed');
                buttonText.textContent = 'Signing In...';
                buttonArrow.classList.add('hidden');
                buttonSpinner.classList.remove('hidden');
            });
        });
    </script>
</x-guest-layout>
