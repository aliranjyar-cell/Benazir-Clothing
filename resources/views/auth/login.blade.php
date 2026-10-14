<x-guest-layout>
    <div
        class="min-h-screen bg-[#F8F3E9] text-[#292526] font-sans antialiased selection:bg-[#651E32] selection:text-white">
        <main class="min-h-screen flex flex-col lg:flex-row relative">

            {{-- LEFT COLUMN: BRAND EXPERIENCE --}}


            {{-- LEFT COLUMN: MINIMAL ADMIN BRANDING --}}

            {{-- LEFT COLUMN: BENAZIR CLOTHING BRAND PANEL --}}
            <section
                class="relative hidden lg:flex lg:w-[54%] lg:min-h-screen flex-col justify-between overflow-hidden bg-[#28152F] px-10 py-10 xl:px-14 xl:py-12 text-white"
                style="background-image: linear-gradient(rgba(40, 21, 47, 0.30), rgba(40, 21, 47, 0.30)), url('{{ asset('images/Burgundy Silk with Botanical Embroidery.png') }}'); background-size: cover; background-position: center;">
                <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.13]"
                    style="background:
            radial-gradient(ellipse at 15% 15%, rgba(198,161,91,.20), transparent 45%),
            radial-gradient(ellipse at 85% 85%, rgba(198,161,91,.12), transparent 40%),
            repeating-linear-gradient(125deg, transparent 0px, transparent 3px, rgba(255,255,255,.025) 4px, transparent 5px);">
                </div>

                {{-- Subtle gold botanical accent --}}
                {{-- <svg aria-hidden="true"
                    class="pointer-events-none absolute -right-12 top-[20%] h-[440px] w-[280px] opacity-[0.20]"
                    viewBox="0 0 280 440" fill="none">
                    <path d="M250 5C205 95 240 170 180 245S145 365 80 435" stroke="#C6A15B" stroke-width="1.2" />
                    <path d="M218 82C165 85 150 55 160 24C196 31 222 51 218 82Z" stroke="#C6A15B" stroke-width="1.2" />
                    <path d="M207 130C247 100 260 75 252 52C222 69 207 97 207 130Z" stroke="#C6A15B"
                        stroke-width="1.2" />
                    <path d="M190 195C140 187 123 157 136 130C173 144 193 165 190 195Z" stroke="#C6A15B"
                        stroke-width="1.2" />
                    <path d="M174 247C222 220 240 194 230 170C198 187 177 215 174 247Z" stroke="#C6A15B"
                        stroke-width="1.2" />
                    <path d="M145 315C98 306 83 277 98 251C132 265 151 287 145 315Z" stroke="#C6A15B"
                        stroke-width="1.2" />
                    <path d="M119 365C164 340 181 314 170 291C140 308 122 335 119 365Z" stroke="#C6A15B"
                        stroke-width="1.2" />
                    <path d="M80 435C83 360 135 270 190 195" stroke="#C6A15B" stroke-width="1.2" />
                </svg> --}}

                {{-- Brand identity --}}
                <div class="relative z-10 flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center border border-[#C6A15B]/70">
                        <span class="font-serif text-3xl text-[#D6B875]">B</span>
                    </div>

                    <div>
                        <h1 class="text-sm font-semibold tracking-[0.22em] text-[#F8F3E9]">
                            BENAZIR CLOTHING
                        </h1>
                        <p class="mt-1 text-[10px] tracking-[0.25em] text-[#D6B875]">
                            ADMINISTRATION
                        </p>
                    </div>
                </div>

                {{-- Main message --}}
                <div class="relative z-10 max-w-xl py-12">
                    <div class="mb-7 h-[2px] w-12 bg-[#C6A15B]"></div>

                    <h2 class="font-serif text-5xl leading-[1.15] text-[#F8F3E9] xl:text-6xl">
                        Everything your store
                        <span class="block">needs.</span>
                    </h2>

                    <p class="mt-6 max-w-md text-sm leading-7 text-white/70">
                        Track orders, manage products, customers and inventory —
                        all in one place.
                    </p>
                </div>

                {{-- Secure access footer --}}
                <div class="relative z-10">
                    <div class="mb-5 h-px w-full bg-white/15"></div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-2.5 text-xs text-white/80">
                            <svg class="h-5 w-5 text-[#D6B875]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3 4.5 6v5.5c0 4.4 3.2 7.5 7.5 9.5 4.3-2 7.5-5.1 7.5-9.5V6L12 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                            </svg>
                            Secure Admin Access
                        </div>

                        <span class="text-xs text-white/50">
                            &copy; {{ date('Y') }} Benazir Clothing
                        </span>
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
                            BENAZIR CLOTHING
                        </span>
                    </div>

                    <div
                        class="ml-auto inline-flex items-center space-x-1.5 px-3 py-1  rounded-full  bg-[#EFE8DC]/70 border border-[#E8E0D7] text-[11px] font-medium text-[#5B5556]">
                        <span class="w-2 h-2  animate-pulse bg-emerald-600"></span>
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
                                <span class="font-serif text-3xl text-[#651E32]">B</span>
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                        © {{ date('Y') }} BENAZIRCLOTHING. All rights reserved.
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
