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
      <a href="#home" class="hover:opacity-90">Home</a>
      <a href="#how" class="hover:opacity-90">Cara Kerja</a>
      <a href="#examples" class="hover:opacity-90">Contoh</a>
      <a href="#contact" class="hover:opacity-90">Daftar</a>
    </nav>

    <!-- Desktop buttons -->
    <div id="desktopBtn" class="hidden md:flex items-center gap-3">
      <a href="{{ route('login') }}" class="text-white border border-white px-3 py-1 rounded hover:bg-white/10">Masuk</a>
      <a href="{{ route('register') }}" class="bg-yellow-300 text-gray-900 px-4 py-2 rounded-lg font-semibold">Daftar</a>
    </div>

    <!-- Mobile toggle -->
    <button id="menuBtn" class="md:hidden text-white text-2xl">☰</button>
  </div>

  <!-- Mobile dropdown -->
  <nav id="mobileNav" class="hidden flex flex-col bg-white text-gray-800 px-4 pb-4 shadow-md md:hidden">
    <a href="#home" class="py-2">Home</a>
    <a href="#how" class="py-2">Cara Kerja</a>
    <a href="#examples" class="py-2">Contoh</a>
    <a href="#contact" class="py-2">Daftar</a>

    <a href="{{ route('login') }}" class="py-2 font-semibold">Masuk</a>
    <a href="{{ route('register') }}" class="py-2 font-semibold text-blue-600">Daftar</a>
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
                    Eksperimen Sains.<br>
                    Analisis. Diskusi.
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-6 max-w-xl">
                    Belajar sains berbasis penyelidikan dengan data nyata — rekam, upload, analisis, dan diskusikan.
                </p>

                <div class="flex gap-4">
                    <a href="#register" class="inline-flex items-center gap-3 bg-yellow-300 text-gray-900 px-6 py-3 rounded-xl font-bold shadow hover:scale-[1.02] transition">
                        Mulai Eksperimen
                    </a>
                </div>
            </div>
        </div>

        <div 
            class="absolute bottom-0 right-0 pointer-events-none select-none
                w-[300px] sm:w-[400px] md:w-[600px] lg:w-[800px]">
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
      <h2 class="text-3xl font-bold text-blue-700 mb-8">Bagaimana Cara Kerjanya?</h2>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        {{-- Card 1 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-yellow-50">
            {{-- icon phone --}}
            <img src="{{ asset('assets/images/record.png') }}">
          </div>
          <h3 class="font-semibold mb-1">Rekam</h3>
          <p class="text-sm text-gray-500">Gunakan sensor HP untuk merekam data eksperimen.</p>
        </div>

        {{-- Card 2 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-blue-50">
            <img src="{{ asset('assets/images/upload.png') }}">
          </div>
          <h3 class="font-semibold mb-1">Upload & Lihat Data</h3>
          <p class="text-sm text-gray-500">Upload file, tampilkan grafik otomatis.</p>
        </div>

        {{-- Card 3 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-green-50">
            <img src="{{ asset('assets/images/compare.png') }}">
          </div>
          <h3 class="font-semibold mb-1">Bandingkan</h3>
          <p class="text-sm text-gray-500">Bandingkan banyak hasil percobaan secara cepat.</p>
        </div>

        {{-- Card 4 --}}
        <div class="p-6 rounded-xl border text-center">
          <div class="mx-auto w-50 h-50 mb-4 flex items-center justify-center rounded-lg bg-purple-50">
            <img src="{{ asset('assets/images/discussion.png') }}">
          </div>
          <h3 class="font-semibold mb-1">Diskusi</h3>
          <p class="text-sm text-gray-500">Diskusikan hasil dengan guru dan teman.</p>
        </div>
      </div>
    </div>
  </section>

    <!-- EXAMPLES -->
    <section id="examples" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-blue-700 mb-8 text-center">
                Contoh Eksperimen Menggunakan Phyphox
            </h2>

            <div class="grid md:grid-cols-2 gap-8">

                <!-- 1. Amplitudo Suara -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/amplitude.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">Mengukur Amplitudo Suara</h3>
                        <p class="text-gray-600 mb-6">
                            Gunakan mikrofon HP untuk mengukur kuat lemahnya suara di sekitar.
                            Siswa dapat membandingkan amplitudo berbagai sumber suara.
                        </p>
                        <a href="{{route('login')}}" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                            Coba Eksperimen
                        </a>
                    </div>
                </div>

                <!-- 2. Spektrum Suara -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/spectrum.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">Analisis Spektrum Suara</h3>
                        <p class="text-gray-600 mb-6">
                            Dengan fitur Audio Spectrum, siswa bisa melihat komponen frekuensi suara secara real-time.
                        </p>
                        <a href="{{route('login')}}" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                            Coba Eksperimen
                        </a>
                    </div>
                </div>

                <!-- 3. Pegas & Getaran -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/spring.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">Getaran & Pegas (Accelerometer)</h3>
                        <p class="text-gray-600 mb-6">
                            Tempelkan HP pada pegas atau ayunan kecil. Rekam grafik getaran dan analisis frekuensi serta periode.
                        </p>
                        <!-- <a href="#" class="inline-block px-5 py-3 bg-yellow-300 rounded-lg font-semibold">
                            Coba Eksperimen
                        </a> -->
                    </div>
                </div>

                <!-- 4. Ayunan Fisika -->
                <div class="bg-white p-8 rounded-2xl shadow flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('assets/images/examples/bandul.png') }}" 
                        class="w-full md:w-40 h-auto object-contain">
                    <div>
                        <h3 class="text-2xl font-semibold mb-3">Mengukur Periode Ayunan</h3>
                        <p class="text-gray-600 mb-6">
                            Phyphox dapat mengukur periode ayunan menggunakan fitur pendulum atau giroskop.
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
      <h2 class="text-3xl font-bold mb-4">Siap Membawa Sains Jadi Lebih Seru?</h2>
      <p class="mb-6 text-white/90">Daftar gratis dan mulai jelajahi eksperimen interaktif sekarang.</p>
      <a href="{{ route('register') }}" class="px-8 py-3 bg-yellow-300 text-gray-900 rounded-xl font-bold">Daftar Gratis</a>
    </div>
  </section>

  <footer class="py-8 text-center text-sm text-gray-500">
    © {{ date('Y') }} SSI Inquiry
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
          // Tambahkan class solid
          header.classList.add('solid', 'bg-white', 'shadow-md');

          desktopNav.classList.add('text-gray-800');
          desktopNav.classList.remove('text-white');

          desktopBtn.querySelectorAll('a').forEach(a => {
              a.classList.remove('text-white', 'border-white');
              a.classList.add('text-gray-900');
          });

      } else {
          // Kembalikan transparan
          header.classList.remove('solid', 'bg-white', 'shadow-md');

          desktopNav.classList.remove('text-gray-800');
          desktopNav.classList.add('text-white');

          desktopBtn.querySelectorAll('a').forEach(a => {
              a.classList.add('text-white');
          });
      }
  }

  onScrollHeader();
  window.addEventListener('scroll', onScrollHeader);


  </script>
</body>
</html>
