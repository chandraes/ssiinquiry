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
            <a href="{{route('landing-page')}}" style="display: block; position: relative; width: 120px; padding: 20px 0px;">
                <img src="{{$logoUrl}}" alt="SSI Inquiry Logo"/>
            </a>

            <ul class="hidden md:flex space-x-6">
                <li><a href="#home" class="hover:text-blue-600">{{ __('landing.navbar_home') }}</a></li>
                <li><a href="#about" class="hover:text-blue-600">{{ __('landing.navbar_about') }}</a></li>
                <li><a href="#features" class="hover:text-blue-600">{{ __('landing.navbar_features') }}</a></li>
                <li><a href="#how" class="hover:text-blue-600">{{ __('landing.navbar_how') }}</a></li>
            </ul>

            <div class="hidden md:flex items-center space-x-3">
                <a href="/login" class="border border-blue-700 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition">{{ __('landing.button_login') }}</a>
                <a href="/register" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">{{ __('landing.button_register') }}</a>

                {{-- 👇 [BARU] Language Switcher --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full focus:outline-none">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 py-2 w-24 bg-white rounded-md shadow-xl z-20">
                        <a href="{{ route('language.switch', 'id') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">ID</a>
                        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">EN</a>
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
                <li><a href="{{route('login')}}" class="border border-blue-700 text-blue-700 px-4 py-1 rounded-lg hover:bg-blue-700 hover:text-white transition">{{ __('landing.button_login') }}</a></li>
                <li><a href="{{route('register')}}" class="bg-blue-700 text-white px-4 py-1 rounded-lg hover:bg-blue-800 transition">{{ __('landing.button_register') }}</a></li>
            </ul>
        </div>
    </header>

    <section id="home" class="h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-50 to-blue-200 text-center px-6 pt-20">
        <h1 class="text-5xl font-bold text-blue-800 mb-4">{!! __('landing.hero_title') !!}</h1>
        <p class="text-lg text-gray-600 mb-6 max-w-2xl">
            {!! __('landing.hero_subtitle') !!}
        </p>
        <div>
            <a href="#about" class="bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition">{{ __('landing.hero_button_learn') }}</a>
            <a href="#contact" class="ml-3 border border-blue-700 text-blue-700 px-6 py-3 rounded-lg hover:bg-blue-700 hover:text-white transition">{{ __('landing.hero_button_start') }}</a>
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

    <section id="contact" class="py-20 bg-blue-700 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">{{ __('landing.cta_title') }}</h2>
        <p class="mb-8">{{ __('landing.cta_subtitle') }}</p>
        <a href="{{route('register')}}" class="bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">{{ __('landing.cta_button') }}</a>
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
