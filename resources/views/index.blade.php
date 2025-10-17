<!DOCTYPE html>
<html lang="id">
<head>
  	<meta charset="UTF-8" />
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	@php
		$faviconPath = get_setting('app_favicon');
		$faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
	@endphp
	
	<link rel="icon" href="{{ $faviconUrl }}">

	<!-- TIITLE -->
    <title>SSI Inquiry</title>

	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-poppins text-gray-800 scroll-smooth">
  <!-- 🔹 NAVBAR -->
	<header class="fixed top-0 left-0 w-full bg-white shadow z-50">
		<nav class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">
			<!-- Logo -->
			@php
				$logoPath = get_setting('app_logo');
				$logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
			@endphp
			{{-- <div class="font-bold text-2xl text-blue-700">SSI Inquiry</div> --}}
			<a href="{{route('home')}}" style="display: block; position: relative; width: 120px; padding: 20px 0px;">
				<img src="{{$logoUrl}}" alt=""/>
			</a>

			<!-- Menu utama -->
			<ul class="hidden md:flex space-x-6">
				<li><a href="#home" class="hover:text-blue-600">Beranda</a></li>
				<li><a href="#about" class="hover:text-blue-600">Tentang</a></li>
				<li><a href="#features" class="hover:text-blue-600">Fitur</a></li>
				<li><a href="#how" class="hover:text-blue-600">Cara Kerja</a></li>
				{{-- <li><a href="#gallery" class="hover:text-blue-600">Galeri</a></li> --}}
				{{-- <li><a href="#contact" class="hover:text-blue-600">Kontak</a></li> --}}
			</ul>

			<!-- Tombol Login & Register -->
			<div class="hidden md:flex items-center space-x-3">
				<a href="/login" class="border border-blue-700 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition">Login</a>
				<a href="/register" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">Daftar</a>
			</div>

			<!-- Tombol menu untuk mobile -->
			<div class="md:hidden">
				<button id="menu-btn" class="text-blue-700 focus:outline-none text-3xl">☰</button>
			</div>
		</nav>

		<!-- Menu mobile -->
		<div id="mobile-menu" class="hidden bg-white shadow-md md:hidden">
		<ul class="flex flex-col items-center space-y-3 py-4">
			<li><a href="#home" class="hover:text-blue-600">Beranda</a></li>
			<li><a href="#about" class="hover:text-blue-600">Tentang</a></li>
			<li><a href="#features" class="hover:text-blue-600">Fitur</a></li>
			<li><a href="#how" class="hover:text-blue-600">Cara Kerja</a></li>
			{{-- <li><a href="#gallery" class="hover:text-blue-600">Galeri</a></li> --}}
			{{-- <li><a href="#contact" class="hover:text-blue-600">Kontak</a></li> --}}
			<li><a href="/login" class="border border-blue-700 text-blue-700 px-4 py-1 rounded-lg hover:bg-blue-700 hover:text-white transition">Login</a></li>
			<li><a href="/register" class="bg-blue-700 text-white px-4 py-1 rounded-lg hover:bg-blue-800 transition">Daftar</a></li>
		</ul>
		</div>
	</header>

	<!-- 🔹 HERO SECTION -->
	<section id="home" class="h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-50 to-blue-200 text-center px-6 pt-20">
		<h1 class="text-5xl font-bold text-blue-800 mb-4">Eksperimen, Analisis, dan Diskusi Sains Interaktif</h1>
		<p class="text-lg text-gray-600 mb-6 max-w-2xl">
		<strong>SSI Inquiry</strong> membantu siswa memahami fenomena ilmiah melalui data eksperimen dari <strong>Phypox</strong>, 
		forum diskusi, dan video studi kasus pembelajaran yang interaktif.
		</p>
		<div>
			<a href="#about" class="bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition">Pelajari Lebih Lanjut</a>
			<a href="#contact" class="ml-3 border border-blue-700 text-blue-700 px-6 py-3 rounded-lg hover:bg-blue-700 hover:text-white transition">Mulai Sekarang</a>
		</div>
	</section>

	<!-- 🔹 TENTANG -->
	<section id="about" class="py-20 max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
		<img src="{{$logoUrl}}" alt="Phypox Illustration" class="w-1/2 max-w-md mx-auto">
		<div>
			<h2 class="text-3xl font-bold text-blue-700 mb-4">Apa itu SSI Inquiry?</h2>
			<p class="text-gray-700 mb-4 leading-relaxed">
				<strong>SSI Inquiry</strong> adalah platform pembelajaran berbasis eksperimen ilmiah. 
				Siswa dapat melakukan perekaman data melalui aplikasi <strong>Phypox</strong>, kemudian membandingkan hasilnya dengan data referensi di SSI Inquiry.
			</p>
			<p class="text-gray-700 leading-relaxed">
				Selain itu, tersedia forum diskusi untuk berkolaborasi dan berbagi ide antar siswa, 
				serta video studi kasus dari YouTube yang bisa dijadikan bahan analisis ilmiah.
			</p>
		</div>
	</section>

	<!-- 🔹 FITUR -->
	<section id="features" class="py-20 bg-gray-50 text-center">
		<h2 class="text-3xl font-bold text-blue-700 mb-10">Fitur Unggulan</h2>
		<div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto px-6">
			<div class="bg-white shadow rounded-xl p-6">
				<div class="text-4xl mb-3">📱</div>
				<h3 class="font-semibold text-lg mb-2">Eksperimen Phypox</h3>
				<p class="text-gray-600">Gunakan Phypox untuk merekam data ilmiah dari aktivitas eksperimen nyata.</p>
			</div>
			<div class="bg-white shadow rounded-xl p-6">
				<div class="text-4xl mb-3">📊</div>
				<h3 class="font-semibold text-lg mb-2">Analisis & Visualisasi</h3>
				<p class="text-gray-600">Bandingkan hasil eksperimen Anda dengan data acuan yang tersedia.</p>
			</div>
			<div class="bg-white shadow rounded-xl p-6">
				<div class="text-4xl mb-3">🎥</div>
				<h3 class="font-semibold text-lg mb-2">Video Studi Kasus</h3>
				<p class="text-gray-600">Pelajari eksperimen melalui video yang terintegrasi dalam platform.</p>
			</div>
			<div class="bg-white shadow rounded-xl p-6">
				<div class="text-4xl mb-3">💬</div>
				<h3 class="font-semibold text-lg mb-2">Forum Diskusi</h3>
				<p class="text-gray-600">Diskusikan hasil dan temuan Anda bersama siswa lain secara interaktif.</p>
			</div>
		</div>
	</section>
									
  	<!-- 🔹 CARA KERJA -->
	<section id="how" class="py-20 text-center">
		<h2 class="text-3xl font-bold text-blue-700 mb-10">Cara Kerja SSI Inquiry</h2>

		<div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto px-6">
			<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
			<div class="text-4xl mb-3">🧪</div>
				<h3 class="font-semibold mb-2">1. Rekam Eksperimen</h3>
				<p class="text-gray-600">Gunakan Phypox untuk merekam data eksperimen dari sensor ponsel Anda.</p>
				<p class="text-gray-600 mt-2">Mulai dengan menyiapkan alat dan bahan yang diperlukan.</p>
			</div>

			<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
			<div class="text-4xl mb-3">⬆️</div>
				<h3 class="font-semibold mb-2">2. Bandingkan Data</h3>
				<p class="text-gray-600">Bandingkan hasil eksperimen Anda dengan data acuan di SSI Inquiry.</p>
				<p class="text-gray-600 mt-2">Lihat bagaimana data Anda cocok dengan standar ilmiah.</p>
			</div>

			{{-- <div class="grid md:grid-cols-2 gap-6"> --}}
			<!-- Card 3: Tonton & Amati -->
			<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
				<div class="text-4xl mb-3">▶️</div>
				<h3 class="font-semibold mb-2">3. Tonton & Amati</h3>
				<p class="text-gray-600">Pelajari konsep melalui video studi kasus yang tersedia.</p>
				<p class="text-gray-600 mt-2">Amati hasil percobaan dan kaitkan dengan data yang Anda peroleh.</p>
			</div>

			<!-- Card 4: Diskusikan & Pelajari -->
			<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
				<div class="text-4xl mb-3">💡</div>
					<h3 class="font-semibold mb-2">4. Diskusikan & Pelajari</h3>
					<p class="text-gray-600">Gunakan forum SSI Inquiry untuk berdiskusi dan memperdalam pemahaman.</p>
					<p class="text-gray-600 mt-2">Tukar ide, analisis hasil, dan belajar bersama teman-teman.</p>
			</div>
			{{-- </div> --}}
		</div>
	</section>


  <!-- 🔹 GALERI -->
  {{-- <section id="gallery" class="py-20 bg-gray-50 text-center">
    <h2 class="text-3xl font-bold text-blue-700 mb-10">Galeri Pembelajaran</h2>
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
      <img src="https://via.placeholder.com/400x250?text=Dashboard+SSI+Inquiry" class="rounded-xl shadow">
      <img src="https://via.placeholder.com/400x250?text=Forum+Diskusi" class="rounded-xl shadow">
      <img src="https://via.placeholder.com/400x250?text=Video+Studi+Kasus" class="rounded-xl shadow">
    </div>
  </section> --}}

  <!-- 🔹 CTA -->
  <section id="contact" class="py-20 bg-blue-700 text-white text-center">
    <h2 class="text-3xl font-bold mb-4">Mulai Eksperimenmu Hari Ini!</h2>
    <p class="mb-8">Gabung bersama ribuan siswa lain untuk belajar sains dengan cara yang lebih interaktif dan menyenangkan.</p>
    <a href="{{route('register')}}" class="bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Daftar Sekarang</a>
  </section>

  <!-- 🔹 FOOTER -->
  <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm">
    © 2025 SSI Inquiry
  </footer>

  <!-- 🔹 Script menu mobile -->
  <script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
  </script>
</body>
</html>
