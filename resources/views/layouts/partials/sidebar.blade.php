<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{route('home')}}">
                {{-- [DIUBAH] alt text --}}
                <img src="{{ $logoUrl }}" class="header-brand-img desktop-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img toggle-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo" alt="{{ __('admin.logo_alt') }}">
                <img src="{{ $logoUrl }}" class="header-brand-img light-logo1" alt="{{ __('admin.logo_alt') }}">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.main') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{request()->routeIs('home') ? 'active' : ''}}" data-bs-toggle="slide"
                        href="{{route('home')}}"><i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.dashboard') }}</span>
                    </a>
                </li>
                @role(['admin','guru'])
                <li class="slide">
                    <a class="side-menu__item {{request()->routeIs('user') ? 'active' : ''}}" data-bs-toggle="slide"
                        href="{{route('user')}}"><i class="side-menu__icon fe fe-user">
                        </i>
                        <span class="side-menu__label">{{ __('admin.sidebar.user') }}</span>
                    </a>
                </li>

                {{-- Modul & Kelas Dinamis --}}
                @if(isset($moduls) && $moduls->count() > 0)
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.modules_classes') }}</h3>
                </li>

                {{-- [PERBAIKAN DIMULAI DARI SINI] --}}
                @foreach($moduls as $modul)

                {{-- 1. Blok Logika Baru untuk Cek Aktivasi --}}
                @php
                // Cek 1: Apakah kita di halaman detail modul & ID-nya cocok?
                $isModulDetailPage = request()->routeIs('modul.show', $modul->id);

                // Cek 2: Apakah kita di halaman sub-modul (via var $activeModulId) & ID-nya cocok?
                $isSubModulPage = (($activeModulId ?? null) == $modul->id);

                // Cek 3: Apakah kita di halaman kelas & ID kelasnya milik modul ini?
                $isKelasPage = false;
                if(request()->routeIs('kelas.*')) {
                // Menggunakan logika asli Anda untuk mendapatkan ID kelas saat ini
                $currentKelasId = (int)(array_values(request()->route()->parameters())[0] ?? null);
                // Cek apakah modul ini memiliki kelas tersebut
                if ($currentKelasId > 0 && $modul->kelas->contains('id', $currentKelasId)) {
                $isKelasPage = true;
                }
                }

                // Gabungkan: Modul ini "aktif" (terbuka/highlight) jika salah satu dari 3 di atas true
                $isModulActiveAndExpanded = $isModulDetailPage || $isSubModulPage || $isKelasPage;
                @endphp

                {{-- 2. Terapkan Logika Baru ke <li> (untuk 'is-expanded') --}}
                <li class="slide {{ $isModulActiveAndExpanded ? 'is-expanded' : '' }}">

                    {{-- 3. Terapkan Logika Baru ke <a> utama (untuk 'active') --}}
                        <a class="side-menu__item {{ $isModulActiveAndExpanded ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="side-menu__icon fe fe-book"></i>
                            <span class="side-menu__label">{{ $modul->judul }}</span>
                            <i class="angle fa fa-angle-right"></i>
                        </a>

                        <ul class="slide-menu">
                            <li class="side-menu-label1">
                                <a href="javascript:void(0)">{{ $modul->judul }}</a>
                            </li>
                            <li>
                                {{-- 4. Terapkan Logika Spesifik ke 'Detail Modul' --}}
                                {{-- Item ini hanya aktif jika di halaman modul atau sub-modul, BUKAN kelas --}}
                                <a href="{{ route('modul.show', $modul->id) }}"
                                    class="slide-item {{ $isModulDetailPage || $isSubModulPage ? 'active' : '' }}">
                                    <i class="fa fa-info-circle me-2"></i> Detail Modul
                                </a>
                            </li>
                            <li class="sub-category">
                                <h3 style="padding-left:0px">Kelas</h3>
                            </li>

                            {{-- 5. Logika Kelas (Biarkan seperti asli, sudah benar) --}}
                            @forelse($modul->kelas as $kelas)
                            <li>
                                <a href="{{ route('kelas.peserta', $kelas->id) }}"
                                    class="slide-item {{ (request()->routeIs('kelas.*') && ((int)(array_values(request()->route()->parameters())[0] ?? null) === (int) $kelas->id)) ? 'active' : '' }}">{{
                                    $kelas->nama_kelas }}</a>
                            </li>
                            @empty
                            <li>
                                <span>{{ __('admin.sidebar.no_class') }}</span>
                            </li>
                            @endForelse
                        </ul>
                </li>
                @endforeach
                {{-- [PERBAIKAN SELESAI] --}}

                @endif
                @endrole

                @role(['siswa'])
    {{-- Modul & Kelas Dinamis --}}
    @if(isset($moduls) && $moduls->count() > 0)
    <li class="sub-category">
        <h3>{{ __('admin.sidebar.modules_classes') }}</h3>
    </li>

    @foreach($moduls as $modul)

        @php
        // --- 1. Ambil ID Aktif Lokal (Harus dilakukan di dalam loop) ---
        // CATATAN PENTING: Ganti 'submodul' dan 'kelas' di bawah dengan nama parameter RUTE AKTUAL Anda.
        // Ini adalah langkah kunci untuk mendapatkan ID parameter yang sedang dilihat.
        $activeSubmodulId = request()->route('siswa.submodul.show') ? request()->route('submodul') : null;
        $activeKelasId = request()->route('siswa.kelas');
        
        // --- 2. Tentukan Status Expanded (Menu Parent) ---
        $isSubmodulChildActive = false;
        $submoduls = $modul->submoduls ?? $modul->submodul ?? $modul->submodules ?? collect();
        
        if ($activeSubmodulId && request()->routeIs('siswa.submodul.show')) {
             // Cek apakah Submodul ID yang aktif ada di dalam relasi Modul ini.
             $isSubmodulChildActive = $submoduls->contains('id', (int) $activeSubmodulId);
        }

        $isKelasChildActive = false;
        if ($activeKelasId && request()->routeIs('siswa.kelas')) {
            // Cek apakah Kelas ID yang aktif ada di dalam relasi Modul ini.
            $isKelasChildActive = $modul->kelas->contains('id', (int) $activeKelasId);
        }

        // Modul ini expanded jika salah satu anaknya aktif
        $isModulExpanded = $isSubmodulChildActive || $isKelasChildActive;
        @endphp

        {{-- 3. Terapkan Logika ke <li> (untuk 'is-expanded') --}}
        <li class="slide {{ request()->routeIs('siswa.submodul.show') || 
                    request()->routeIs('siswa.kelas') 
                    ? 'is-expanded' : '' }}">

            {{-- 4. Terapkan Logika ke <a> utama (Hanya highlight jika expanded) --}}
            <a class="side-menu__item {{ request()->routeIs('siswa.submodul.show') || 
                    request()->routeIs('siswa.kelas') 
                    ? 'active' : '' }}"
                data-bs-toggle="slide" href="javascript:void(0);">
                <i class="side-menu__icon fe fe-book"></i>
                <span class="side-menu__label">{{ $modul->judul }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>

            <ul class="slide-menu">
                <li class="side-menu-label1">
                    <a href="javascript:void(0)">{{ $modul->judul }}</a>
                </li>
                
                {{-- Submodul --}}
                @forelse($submoduls as $submodul)
                    <li>
                        <a href="{{ route('siswa.submodul.show', $submodul->id) }}"
                           {{-- 5. Logika Active Submodul (Direct Check) --}}
                           class="slide-item {{ 
                                request()->routeIs('siswa.submodul.show') && ((int) request()->route('submodul') === (int) $submodul->id) 
                                ? 'active' : '' 
                           }}">
                            <i class="fa fa-file-text me-2"></i> {{ $submodul->judul ?? $submodul->title ?? 'Submodul' }}
                        </a>
                    </li>
                @empty
                    <li>
                        <span class="slide-item text-muted">{{ __('admin.sidebar.no_submodule') ?? 'Tidak ada submodul' }}</span>
                    </li>
                @endforelse

                <li class="sub-category">
                    <h3 style="padding-left:0px">Kelas</h3>
                </li>

                {{-- Logika Kelas --}}
                @forelse($modul->kelas as $kelas)
                <li>
                    <a href="{{ route('siswa.kelas', $kelas->id) }}"
                        {{-- 6. Logika Active Kelas (Direct Check) --}}
                        class="slide-item {{ 
                            request()->routeIs('siswa.kelas') && ((int) request()->route('kelas') === (int) $kelas->id) 
                            ? 'active' : '' 
                        }}">{{ $kelas->nama_kelas }}</a>
                </li>
                @empty
                <li>
                    <span>{{ __('admin.sidebar.no_class') }}</span>
                </li>
                @endForelse
            </ul>
        </li>
    @endforeach

    @endif
@endrole

                @role('admin')
                <li class="sub-category">
                    <h3>{{ __('admin.sidebar.settings') }}</h3>
                </li>
                <li>
                    <a class="side-menu__item {{request()->routeIs('admin.settings.*') ? 'active' : ''}}"
                        href="{{route('admin.settings.index')}}"><i class="side-menu__icon fa fa-gears"></i>
                        <span class="side-menu__label">{{ __('admin.sidebar.application') }}</span>
                    </a>
                </li>
                @endrole
            </ul> {{-- Penutup <ul> side-menu --}}

                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                    </svg></div>
        </div>
    </aside>
</div>
