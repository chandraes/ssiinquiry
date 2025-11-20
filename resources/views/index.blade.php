{{-- resources/views/landing.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  @php
    $faviconPath = get_setting('app_favicon');
    $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
    $logoPath = get_setting('app_logo');

    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
  @endphp
    <title>SSI Inquiry — Eksperimen Sains</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style> 
    body { font-family: 'Poppins', sans-serif; }

    /* Default: header transparan → pakai logo putih */
    .logo-color { display: none; }
    .solid .logo-white { display: none; }
    .solid .logo-color { display: block; }

    #desktopBtn a[href*="register"] {
        color: black !important;
    }

    #mobileNav a[href*="register"] {
        color: black !important;
    }

  </style>
</head>
<body class="antialiased bg-white text-gray-800">

  <!-- NAVBAR (transparan di hero; berubah saat scroll) -->
  <header id="mainHeader" class="fixed w-full z-50 transition-all">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      
      <!-- Logo -->
      <a href="{{ route('landing-page') }}" class="flex items-center">
        {{-- Logo putih (saat navbar transparan) --}}
          <img 
              src="{{ asset('assets/images/ssi_white.png') }}"
              class="h-10 w-auto object-contain logo-white"
              alt="Logo SSI White"
          >

          {{-- Logo berwarna (saat navbar putih) --}}
          <img 
              src="{{ $logoUrl }}"
              class="h-10 w-auto object-contain logo-color"
              alt="Logo SSI Color"
          >
      </a>


      <!-- Desktop nav -->
      <nav id="desktopNav" class="hidden md:flex items-center gap-6 text-white">
        <a href="#home" class="hover:opacity-90">{{__('landing.navbar_home')}}</a>
        <a href="#how" class="hover:opacity-90">{{__('landing.navbar_how')}}</a>
        <a href="#examples" class="hover:opacity-90">{{__('landing.navbar_example')}}</a>
        <a href="#register" class="hover:opacity-90">{{__('landing.button_register')}}</a>
      </nav>

      <!-- Desktop buttons -->
      <div id="desktopBtn" class="hidden md:flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-white border-4 border-white-300 px-3 py-1 rounded-lg font-semibold hover:bg-white/20">
          {{__('landing.button_login')}}
        </a>
        <a href="{{ route('register') }}" class="text-black bg-yellow-300 px-4 py-2 rounded-lg font-semibold">
            {{__('landing.button_register')}}
        </a>

    
        <!-- Tombol Bahasa (Desktop) -->
        <div class="relative">
          <button id="langBtn" class="flex items-center gap-2 transition-colors">
            <img src="{{ app()->getLocale() == 'id' ? 'https://flagcdn.com/id.svg' : 'https://flagcdn.com/gb.svg' }}" class="w-5 h-3" alt="Flag">
            <span class="text-sm font-semibold uppercase">{{ app()->getLocale() }}</span>
          </button>

          <div id="langDropdown" class="hidden absolute right-0 mt-2 py-1 w-40 bg-white rounded-md shadow-xl z-50">
            <a href="{{ route('language.switch', 'id') }}"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
              <img src="https://flagcdn.com/id.svg" class="w-5 h-3 mr-3" alt="Flag Indonesia">
              <span class="text-gray-900">Indonesia</span>
            </a>
            <a href="{{ route('language.switch', 'en') }}"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
              <img src="https://flagcdn.com/gb.svg" class="w-5 h-3 mr-3" alt="Flag UK">
              <span class="text-gray-900">English</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Mobile toggle -->
      <button id="menuBtn" class="md:hidden text-white text-2xl">☰</button>
    </div>

    <!-- Mobile dropdown -->
    <nav id="mobileNav" class="hidden flex items-center flex-col bg-white text-gray-800 px-4 pb-4 shadow-md md:hidden">
      <a href="#home" class="py-2">{{__('landing.navbar_home')}}</a>
      <a href="#how" class="py-2">{{__('landing.navbar_how')}}</a>
      <a href="#examples" class="py-2">{{__('landing.navbar_example')}}</a>
      <a href="#register" class="py-2">{{__('landing.button_register')}}</a>

      <a href="{{ route('login') }}" class="py-2 font-semibold">{{__('landing.button_login')}}</a>
      <a href="{{ route('register') }}" class="py-2 font-semibold text-black">
          {{__('landing.button_register')}}
      </a>

      <!-- Tombol Bahasa (Mobile) -->
      <div class="relative mt-2">
          <button id="langBtnMobile" class="flex items-center gap-2 transition-colors">
            <img src="{{ app()->getLocale() == 'id' ? 'https://flagcdn.com/id.svg' : 'https://flagcdn.com/gb.svg' }}" class="w-5 h-3" alt="Flag">
            <span class="text-sm font-semibold uppercase">{{ app()->getLocale() }}</span>
          </button>

          <div id="langDropdownMobile" 
              class="hidden absolute text-center  mt-2 py-1 w-40 bg-white rounded-md shadow-xl z-50">

              <a href="{{ route('language.switch', 'id') }}"
                  class="flex items-center px-4 py-1 text-sm text-gray-700 hover:bg-blue-100">
                  <img src="https://flagcdn.com/id.svg" class="w-7 h-5 mr-3">
                  <span class="text-black">Indonesia</span>
                  <span class="ml-auto text-xs font-bold text-gray-400">ID</span>
              </a>

              <a href="{{ route('language.switch', 'en') }}"
                  class="flex items-center px-4 py-1 text-sm text-gray-700 hover:bg-blue-100">
                  <img src="https://flagcdn.com/gb.svg" class="w-7 h-5 mr-3">
                  <span class="text-black">English</span>
                  <span class="ml-auto text-xs font-bold text-gray-400">EN</span>
              </a>
          </div>
      </div>

    </nav>
  </header>


  <!-- HERO -->
  <section id="home" class="relative h-screen min-h-[400px] flex items-center overflow-hidden">
    <!-- background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-500"></div>
    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10 py-20">
      <!-- left: headline -->
      <div class="flex-1 text-white">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
            {!! __('landing.hero_title') !!}
        </h1>
        <p class="text-lg md:text-xl text-white/90 mb-6 max-w-xl">
            {{__('landing.hero_subtitle')}}
        </p>
        <div class="flex gap-4">
            <a href="{{route('register')}}" class="inline-flex items-center gap-3 bg-yellow-300 text-gray-900 px-6 py-3 rounded-xl font-bold shadow hover:scale-[1.02] transition">
                {{__('landing.hero_button_start')}}
            </a>
        </div>
      </div>
    </div>

    <div 
        class="absolute bottom-0 right-0 pointer-events-none select-none
            w-[150px] sm:w-[200px] md:w-[300px] lg:w-[600px]">
        <img 
            src="{{ asset('assets/images/scientist.png') }}" 
            alt="Ilustrasi Scientist"
            class="w-full h-auto object-contain"
        >
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section id="how" class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold text-blue-700 mb-8">{{__('landing.how_title')}}</h2>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        {{-- Card 1 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-yellow-50">
            {{-- icon phone --}}
            <img src="{{ asset('assets/images/record.png') }}">
          </div>
          <h3 class="font-semibold mb-1">{{__('landing.how_p1_title')}}</h3>
          <p class="text-sm text-gray-500">{{__('landing.how_p1_subtitle')}}</p>
        </div>

        {{-- Card 2 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-blue-50">
            <img src="{{ asset('assets/images/upload.png') }}">
          </div>
          <h3 class="font-semibold mb-1">{{__('landing.how_p2_title')}}</h3>
          <p class="text-sm text-gray-500">{{__('landing.how_p2_subtitle')}}</p>
        </div>

        {{-- Card 3 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-green-50">
            <img src="{{ asset('assets/images/compare.png') }}">
          </div>
          <h3 class="font-semibold mb-1">{{__('landing.how_p3_title')}}</h3>
          <p class="text-sm text-gray-500">{{__('landing.how_p3_subtitle')}}</p>
        </div>

        {{-- Card 4 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-purple-50">
            <img src="{{ asset('assets/images/discussion.png') }}">
          </div>
          <h3 class="font-semibold mb-1">{{__('landing.how_p4_title')}}</h3>
          <p class="text-sm text-gray-500">{{__('landing.how_p4_subtitle')}}</p>
        </div>
      </div>
    </div>
  </section>

    <!-- EXAMPLES -->
    <section id="examples" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-blue-700 mb-8 text-center">
                {{__('landing.examples_title')}}
            </h2>

            <div class="grid md:grid-cols-2 gap-8">

                <!-- 1. Amplitudo Suara -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/amplitude.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">{{__('landing.example_1_title')}}</h3>
                        <p class="text-gray-600 mb-6">
                          {{__('landing.example_1_desc')}}
                        </p>
                        <a href="{{route('login')}}" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                          {{__('landing.button_experiment')}}
                        </a>
                    </div>
                </div>

                <!-- 2. Spektrum Suara -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/spectrum.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">{{__('landing.example_2_title')}}</h3>
                        <p class="text-gray-600 mb-6">
                          {{__('landing.example_2_desc')}}
                        </p>
                        <a href="{{route('login')}}" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                          {{__('landing.button_experiment')}}
                        </a>
                    </div>
                </div>

                <!-- 3. Pegas & Getaran -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/spring.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">{{__('landing.example_3_title')}}</h3>
                        <p class="text-gray-600 mb-6">
                            {{__('landing.example_3_desc')}}
                        </p>
                        <!-- <a href="#" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                            Lihat Cara Kerja
                        </a> -->
                    </div>
                </div>

                <!-- 4. Ayunan Fisika -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/bandul.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">{{__('landing.example_4_title')}}</h3>
                        <p class="text-gray-600 mb-6">
                            {{__('landing.example_4_desc')}}
                        </p>
                        <!-- <a href="#" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                            Lihat Cara Kerja
                        </a> -->
                    </div>
                </div>

            </div>
        </div>
    </section>



  <!-- CTA -->
  <section id="register" class="py-20 bg-gradient-to-br from-blue-600 to-blue-500 text-white">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-4">{{__('landing.register_title')}}</h2>
      <p class="mb-6 text-white/90">{{__('landing.register_subtitle')}}</p>
      <a href="{{ route('register') }}" class="px-8 py-3 bg-yellow-300 text-gray-900 rounded-xl font-bold">{{__('landing.register_button')}}</a>
    </div>
  </section>

  <footer class="py-8 text-center text-sm text-gray-500">
    {!! __('landing.footer_copyright') !!}
  </footer>

  <script>

    // <script>
  document.getElementById('menuBtn').addEventListener('click', function () {
      const menu = document.getElementById('mobileNav');
      menu.classList.toggle('hidden');
  });

  // Navbar becomes white on scroll
  const header = document.getElementById('mainHeader');
  const desktopNav = document.getElementById('desktopNav');
  const desktopBtn = document.getElementById('desktopBtn');

    function onScrollHeader() {
      if (window.scrollY > 40) {
          header.classList.add('solid', 'bg-white', 'shadow-md');

          // Desktop nav → hitam
          desktopNav.classList.add('text-gray-900');
          desktopNav.classList.remove('text-white');

          // Buttons
          desktopBtn.querySelectorAll('a').forEach(a => {
              a.classList.remove('text-white', 'border-white');
              a.classList.add('text-gray-900');
          });

          // LANG BUTTON → hitam
          document.querySelectorAll('#langBtn span, #langBtnMobile span').forEach(el => {
              el.style.color = '#1f2937';
          });

      } else {
          header.classList.remove('solid', 'bg-white', 'shadow-md');

          // Desktop nav → putih
          desktopNav.classList.remove('text-gray-800');
          desktopNav.classList.add('text-white');

          // Buttons
          desktopBtn.querySelectorAll('a').forEach(a => {
              a.classList.add('text-white');
          });

          // LANG BUTTON → putih
          document.querySelectorAll('#langBtn span, #langBtnMobile span').forEach(el => {
              el.style.color = 'white';
          });

      }
  }


  onScrollHeader();
  window.addEventListener('scroll', onScrollHeader);

  document.addEventListener("click", function (event) {
      const btn = document.getElementById("langBtn");
      const dropdown = document.getElementById("langDropdown");

      const btnMobile = document.getElementById("langBtnMobile");
      const dropdownMobile = document.getElementById("langDropdownMobile");

      // Desktop toggle
      if (btn && btn.contains(event.target)) {
          dropdown.classList.toggle("hidden");
          return;
      }
      if (dropdown && !dropdown.contains(event.target)) {
          dropdown.classList.add("hidden");
      }

      // Mobile toggle
      if (btnMobile && btnMobile.contains(event.target)) {
          dropdownMobile.classList.toggle("hidden");
          return;
      }
      if (dropdownMobile && !dropdownMobile.contains(event.target)) {
          dropdownMobile.classList.add("hidden");
      }
  });

  document.getElementById('year').textContent = new Date().getFullYear();

  </script>
</body>
</html>
