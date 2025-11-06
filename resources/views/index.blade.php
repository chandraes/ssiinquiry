<!DOCTYPE html>
{{-- Mengatur atribut 'lang' secara dinamis sesuai bahasa aktif --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
    $faviconPath = get_setting('app_favicon');
    $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
    @endphp

    <link rel="icon" href="{{ $faviconUrl }}">

    <title>SSI Inquiry</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-poppins text-gray-800 scroll-smooth">
    <header class="fixed top-0 left-0 w-full bg-white shadow z-50">
        <nav class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">
            @php
            $logoPath = get_setting('app_logo');
            $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
            @endphp
            <a href="{{route('landing-page')}}"
                style="display: block; position: relative; width: 120px; padding: 20px 0px;">
                <img src="{{$logoUrl}}" alt="SSI Inquiry Logo" />
            </a>

            <ul class="hidden md:flex space-x-6">
                <li><a href="#home" class="hover:text-blue-600">{{ __('landing.navbar_home') }}</a></li>
                <li><a href="#about" class="hover:text-blue-600">{{ __('landing.navbar_about') }}</a></li>
                <li><a href="#features" class="hover:text-blue-600">{{ __('landing.navbar_features') }}</a></li>
                <li><a href="#how" class="hover:text-blue-600">{{ __('landing.navbar_how') }}</a></li>
            </ul>

            <div class="hidden md:flex items-center space-x-3">
                <a href="/login"
                    class="border border-blue-700 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition">{{
                    __('landing.button_login') }}</a>
                <a href="/register" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">{{
                    __('landing.button_register') }}</a>

                {{-- 👇 [BARU] Language Switcher --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    {{-- [DIUBAH] Tombol utama kini menampilkan bendera dan teks --}}
                    <button @click="open = !open"
                        class="flex items-center justify-center w-auto px-3 h-9 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-hidden bg-white">
                        @if(app()->getLocale() == 'id')
                        <img src="https://flagcdn.com/id.svg" alt="Bendera Indonesia" class="w-7 h-5 object-cover">
                        {{-- [BARU] Teks ID di sebelah bendera --}}
                        <span class="ml-2 font-semibold text-sm text-gray-700">ID</span>
                        @else
                        <img src="https://flagcdn.com/gb.svg" alt="Bendera Inggris" class="w-7 h-5 object-cover">
                        {{-- [BARU] Teks EN di sebelah bendera --}}
                        <span class="ml-2 font-semibold text-sm text-gray-700">EN</span>
                        @endif
                    </button>

                    {{-- Dropdown Pilihan Bahasa --}}
                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 py-1 w-40 bg-white rounded-md shadow-xl z-20"
                        style="display: none;">

                        {{-- [DIUBAH] Item dropdown kini lebih informatif --}}
                        <a href="{{ route('language.switch', 'id') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                            <img src="https://flagcdn.com/id.svg" class="w-7 h-5 mr-3" alt="Bendera Indonesia">
                            <span>Indonesia</span>
                            {{-- [BARU] Teks ID di pojok kanan --}}
                            <span class="ml-auto text-xs font-bold text-gray-400">ID</span>
                        </a>

                        <a href="{{ route('language.switch', 'en') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                            <img src="https://flagcdn.com/gb.svg" class="w-7 h-5 mr-3" alt="Bendera Inggris">
                            <span>English</span>
                            {{-- [BARU] Teks EN di pojok kanan --}}
                            <span class="ml-auto text-xs font-bold text-gray-400">EN</span>
                        </a>
                    </div>
                </div>
                {{-- 👆 [BARU] Akhir Language Switcher --}}

            </div>

            <div class="md:hidden">
                <button id="menu-btn" class="text-blue-700 focus:outline-none text-3xl">☰</button>
            </div>
        </nav>

        <div id="mobile-menu" class="hidden bg-white shadow-md md:hidden">
            <ul class="flex flex-col items-center space-y-3 py-4">
                <li><a href="#home" class="hover:text-blue-600">{{ __('landing.navbar_home') }}</a></li>
                <li><a href="#about" class="hover:text-blue-600">{{ __('landing.navbar_about') }}</a></li>
                <li><a href="#features" class="hover:text-blue-600">{{ __('landing.navbar_features') }}</a></li>
                <li><a href="#how" class="hover:text-blue-600">{{ __('landing.navbar_how') }}</a></li>
                <li><a href="{{route('login')}}"
                        class="border border-blue-700 text-blue-700 px-4 py-1 rounded-lg hover:bg-blue-700 hover:text-white transition">{{
                        __('landing.button_login') }}</a></li>
                <li><a href="{{route('register')}}"
                        class="bg-blue-700 text-white px-4 py-1 rounded-lg hover:bg-blue-800 transition">{{
                        __('landing.button_register') }}</a></li>
                <li>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        {{-- [DIUBAH] Tombol utama kini menampilkan bendera dan teks --}}
                        <button @click="open = !open"
                            class="flex items-center justify-center w-auto px-3 h-9 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-hidden bg-white">
                            @if(app()->getLocale() == 'id')
                            <img src="https://flagcdn.com/id.svg" alt="Bendera Indonesia" class="w-7 h-5 object-cover">
                            {{-- [BARU] Teks ID di sebelah bendera --}}
                            <span class="ml-2 font-semibold text-sm text-gray-700">ID</span>
                            @else
                            <img src="https://flagcdn.com/gb.svg" alt="Bendera Inggris" class="w-7 h-5 object-cover">
                            {{-- [BARU] Teks EN di sebelah bendera --}}
                            <span class="ml-2 font-semibold text-sm text-gray-700">EN</span>
                            @endif
                        </button>

                        {{-- Dropdown Pilihan Bahasa --}}
                        <div x-show="open" x-transition
                            class="absolute right-0 mt-2 py-1 w-40 bg-white rounded-md shadow-xl z-20"
                            style="display: none;">

                            {{-- [DIUBAH] Item dropdown kini lebih informatif --}}
                            <a href="{{ route('language.switch', 'id') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                                <img src="https://flagcdn.com/id.svg" class="w-7 h-5 mr-3" alt="Bendera Indonesia">
                                <span>Indonesia</span>
                                {{-- [BARU] Teks ID di pojok kanan --}}
                                <span class="ml-auto text-xs font-bold text-gray-400">ID</span>
                            </a>

                            <a href="{{ route('language.switch', 'en') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                                <img src="https://flagcdn.com/gb.svg" class="w-7 h-5 mr-3" alt="Bendera Inggris">
                                <span>English</span>
                                {{-- [BARU] Teks EN di pojok kanan --}}
                                <span class="ml-auto text-xs font-bold text-gray-400">EN</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>

    <section id="home"
        class="relative h-screen flex flex-col justify-center items-center text-center px-6 pt-20 bg-cover bg-center bg-no-repeat"
        style="background-image: url('https://images.unsplash.com/photo-1602052577122-f73b9710adba?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&fm=jpg&q=60&w=3000');">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 to-blue-600/60"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg leading-tight">
                {!! __('landing.hero_title') !!}
            </h1>
            <p class="text-lg md:text-xl mb-8 text-gray-200 drop-shadow-md">
                {!! __('landing.hero_subtitle') !!}
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#about"
                    class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    {{ __('landing.hero_button_learn') }}
                </a>
                <a href="#contact"
                    class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-700 transition">
                    {{ __('landing.hero_button_start') }}
                </a>
            </div>
        </div>
    </section>


    <section id="about" class="py-20 max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
        <img src="{{$logoUrl}}" alt="Phypox Illustration" class="w-1/2 max-w-md mx-auto">
        <div>
            <h2 class="text-3xl font-bold text-blue-700 mb-4">{!! __('landing.about_title') !!}</h2>
            <p class="text-gray-700 mb-4 leading-relaxed">
                {!! __('landing.about_p1') !!}
            </p>
            <p class="text-gray-700 leading-relaxed">
                {!! __('landing.about_p2') !!}
            </p>
        </div>
    </section>

    <section id="features" class="py-20 bg-gray-50 text-center">
        <h2 class="text-3xl font-bold text-blue-700 mb-10">{{ __('landing.features_title') }}</h2>
        <div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto px-6">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-4xl mb-3">📱</div>
                <h3 class="font-semibold text-lg mb-2">{{ __('landing.feature_1_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.feature_1_desc') }}</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-4xl mb-3">📊</div>
                <h3 class="font-semibold text-lg mb-2">{{ __('landing.feature_2_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.feature_2_desc') }}</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-4xl mb-3">🎥</div>
                <h3 class="font-semibold text-lg mb-2">{{ __('landing.feature_3_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.feature_3_desc') }}</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-4xl mb-3">💬</div>
                <h3 class="font-semibold text-lg mb-2">{{ __('landing.feature_4_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.feature_4_desc') }}</p>
            </div>
        </div>
    </section>

    <section id="how" class="py-20 text-center">
        <h2 class="text-3xl font-bold text-blue-700 mb-10">{{ __('landing.how_title') }}</h2>

        <div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto px-6">
            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="text-4xl mb-3">🧪</div>
                <h3 class="font-semibold mb-2">{{ __('landing.step_1_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.step_1_desc') }}</p>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="text-4xl mb-3">⬆️</div>
                <h3 class="font-semibold mb-2">{{ __('landing.step_2_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.step_2_desc') }}</p>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="text-4xl mb-3">▶️</div>
                <h3 class="font-semibold mb-2">{{ __('landing.step_3_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.step_3_desc') }}</p>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="text-4xl mb-3">💡</div>
                <h3 class="font-semibold mb-2">{{ __('landing.step_4_title') }}</h3>
                <p class="text-gray-600">{{ __('landing.step_4_desc') }}</p>
            </div>
        </div>
    </section>

    <section id="contact"
        class="relative py-20 text-white text-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('https://images.unsplash.com/photo-1602052577122-f73b9710adba?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&fm=jpg&q=60&w=3000');">
        <!-- Overlay agar teks tetap terbaca -->
        <div class="absolute inset-0 bg-blue-900/80"></div>

        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold mb-4">{{ __('landing.cta_title') }}</h2>
            <p class="mb-8 text-gray-200">{{ __('landing.cta_subtitle') }}</p>
            <a href="{{ route('register') }}"
                class="bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                {{ __('landing.cta_button') }}
            </a>
        </div>
    </section>


    <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm">
        {!! __('landing.footer_copyright') !!}
    </footer>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        // set current year
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>
