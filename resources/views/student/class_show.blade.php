@extends('layouts.app')

@section('title')
{{__('admin.siswa.class_show.title')}}: {{ $kelas->nama_kelas }}
@endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* VARIAN WARNA CERAH KHUSUS UNTUK SISWA SMP */
    :root {
        --primary-color: #4a4ff2;
        /* Biru Ungu Utama */
        --secondary-color: #6c757d;
        --accent-color-1: #ffda6a;
        /* Kuning Emas Cerah */
        --accent-color-2: #8be8ff;
        /* Biru Langit Cerah */
        --success-light: #d1e7dd;
        /* Hijau Muda untuk Selesai */
        --warning-light: #fff3cd;
        /* Kuning Muda untuk Tersedia */
        --locked-light: #e9ecef;
        /* Abu-abu Muda untuk Terkunci */
        --text-dark: #212529;
        --shadow-strong: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
        /* Shadow lebih kuat */
        --border-radius-xl: 1.25rem;
    }

    body {
        font-family: 'Poppins', sans-serif;
        /* Pastikan font Poppins tersedia atau fallback */
    }

    /* CARD HERO SECTION (Header Kelas) */
    .class-hero-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, #7b68ee 100%);
        color: white;
        border-radius: var(--border-radius-xl);
        padding: 20px;
        /* Padding lebih kecil untuk mobile */
        box-shadow: var(--shadow-strong);
        position: relative;
        overflow: hidden;
        min-height: 150px;
        /* Tinggi minimum untuk mobile */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .class-hero-card h2 {
        font-size: 1.8rem;
        /* Ukuran font lebih kecil lagi untuk mobile agar tidak memotong */
        font-weight: 800;
        margin-bottom: 0.5rem;
        z-index: 1;
    }

    .class-hero-card p {
        font-size: 0.9rem;
        /* Ukuran font lebih kecil lagi */
        opacity: 0.9;
        z-index: 1;
    }

    /* Ilustrasi di Header Kelas */
    .hero-illustration {
        position: absolute;
        bottom: -20px;
        /* Tarik sedikit ke bawah */
        right: -10px;
        /* Tarik sedikit ke kanan */
        width: 120px;
        /* Ukuran ilustrasi sangat kecil untuk mobile */
        height: auto;
        opacity: 0.6;
        /* Kurangi opasitas agar teks lebih menonjol */
        pointer-events: none;
        z-index: 0;
    }

    /* RPS Button Style */
    .rps-button {
        background-color: white;
        color: var(--primary-color) !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s;
        border-radius: 50px;
        padding: 0.5rem 1rem;
        /* Padding lebih kecil untuk mobile */
        font-size: 0.9rem;
    }

    .rps-button:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }

    /* === Modul List (Kartu Misi) === */
    .module-list-card {
        padding: 15px;
        /* Padding lebih kecil untuk mobile */
        border-radius: var(--border-radius-xl);
        transition: all 0.3s ease;
        text-decoration: none;
        margin-bottom: 1rem;
        /* Margin lebih kecil untuk mobile */
        display: flex;
        /* Gunakan flexbox untuk tata letak konten */
        align-items: center;
        border: 1px solid #dee2e6;
        color: var(--text-dark);
        position: relative;
    }

    .module-list-card:not(.disabled):hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(74, 79, 242, 0.15);
        /* Shadow lebih menonjol */
        text-decoration: none;
    }

    /* Status Visual Styling */
    .status-completed {
        background-color: var(--success-light);
        border-left: 8px solid #198754;
    }

    .status-available {
        background-color: var(--warning-light);
        border-left: 8px solid #ffc107;
    }

    .status-locked {
        background-color: var(--locked-light) !important;
        border-left: 8px solid var(--secondary-color);
        cursor: not-allowed;
    }

    /* Ikon Kategori Besar */
    .module-icon-large {
        font-size: 2rem;
        /* Ukuran ikon lebih kecil untuk mobile */
        margin-right: 10px;
        width: 40px;
        flex-shrink: 0;
        /* Agar ikon tidak mengecil */
        text-align: center;
    }

    .module-list-card h5 {
        font-size: 1.1rem;
        /* Ukuran judul lebih kecil untuk mobile */
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .module-list-card p {
        font-size: 0.85rem;
        /* Ukuran deskripsi lebih kecil untuk mobile */
    }

    /* Badge Status */
    .status-badge {
        font-size: 0.75rem;
        /* Ukuran badge lebih kecil untuk mobile */
        font-weight: 700;
        padding: 0.3rem 0.7rem;
        border-radius: 50px;
        white-space: nowrap;
        /* Agar badge tidak pecah baris */
    }

    /* === Grade Summary Card (Piala) === */
    .grade-summary-card {
        background: linear-gradient(45deg, var(--primary-color) 0%, #7b68ee 100%);
        /* Gradien juga */
        color: white;
        border-radius: var(--border-radius-xl);
        margin-top: 30px;
        padding: 25px;
        /* Padding lebih kecil untuk mobile */
        box-shadow: var(--shadow-strong);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .grade-summary-card h5 {
        font-size: 1.3rem;
        /* Ukuran judul lebih kecil untuk mobile */
        font-weight: 700;
        z-index: 1;
    }

    .grade-summary-card .card-text {
        font-size: 0.95rem;
        /* Ukuran teks lebih kecil untuk mobile */
        z-index: 1;
    }

    .grade-summary-card .btn-lg {
        background-color: var(--accent-color-1);
        /* Warna Emas */
        border: none;
        color: var(--text-dark);
        font-weight: 700;
        transition: all 0.3s;
        padding: 0.7rem 1.5rem;
        /* Padding lebih kecil untuk mobile */
        font-size: 1rem;
        z-index: 1;
    }

    .trophy-icon-large {
        font-size: 3.5rem;
        /* Ukuran ikon piala lebih kecil untuk mobile */
        color: var(--accent-color-1);
        margin-bottom: 15px;
        filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.2));
        /* Efek shadow untuk ikon */
        z-index: 1;
    }

    /* Ilustrasi Background di Grade Card */
    .grade-illustration {
        position: absolute;
        top: -20px;
        left: -30px;
        width: 100px;
        height: auto;
        opacity: 0.3;
        pointer-events: none;
        z-index: 0;
    }

    /* Tombol Kembali Dashboard */
    .btn-secondary.btn-lg {
        padding: 0.7rem 1.5rem;
        /* Padding lebih kecil untuk mobile */
        font-size: 1rem;
    }

    .full-description-toggle {
        display: none;
        /* Sembunyikan konten lengkap secara default */
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
    }

    .full-description-toggle.show-full {
        display: block;
        max-height: 500px;
        /* Nilai besar agar konten terlihat */
        opacity: 1;
        margin-top: 1rem;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.3);
    }

    .toggle-button-hero {
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.3s;
        margin-top: 0.5rem;
        display: inline-block;
        /* Penting untuk penataan */
    }

    /* Media Queries untuk Layar Desktop/Tablet (ukuran > 768px) */
    @media (min-width: 768px) {
        .class-hero-card {
            padding: 40px;
            min-height: 250px;
        }

        .class-hero-card h2 {
            font-size: 3rem;
        }

        .class-hero-card p {
            font-size: 1.2rem;
        }

        .hero-illustration {
            bottom: 0;
            right: 0;
            width: 250px;
            opacity: 1;
            /* Pastikan ilustrasi desktop tampil */
            display: block !important;
        }

        /* Pastikan ilustrasi mobile disembunyikan di desktop (jika menggunakan dua tag img) */
        .d-block.d-md-none {
            display: none !important;
        }

        .rps-button {
            padding: 0.7rem 1.5rem;
            font-size: 1rem;
        }

        .module-list-card {
            padding: 20px;
            margin-bottom: 1.5rem;
        }

        .module-icon-large {
            font-size: 2.5rem;
            margin-right: 15px;
            width: 50px;
        }

        .module-list-card h5 {
            font-size: 1.4rem;
        }

        .module-list-card p {
            font-size: 0.95rem;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .grade-summary-card {
            padding: 40px;
        }

        .grade-summary-card h5 {
            font-size: 1.5rem;
        }

        .grade-summary-card .card-text {
            font-size: 1rem;
        }

        .trophy-icon-large {
            font-size: 5rem;
            /* Ukuran ikon piala untuk desktop */
        }

        .grade-illustration {
            width: 150px;
            opacity: 0.5;
        }

        .btn-secondary.btn-lg {
            padding: 0.8rem 1.8rem;
            font-size: 1.1rem;
        }

        .short-description-mobile,
        .toggle-button-hero {
            display: none !important;
        }

        .full-description-toggle {
            display: block !important;
            opacity: 1 !important;
            max-height: none !important;
            padding: 0;
            border-top: none;
            margin-top: 0.5rem;
        }

        
    }

    /* CEK POINT */

    .progress-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 25px 0 40px 0;
        padding: 0 10px;
    }

    .progress-track::before {
        content: "";
        position: absolute;
        top: 35px;
        left: 0;
        height: 6px;
        width: 100%;
        background: #e2e2e2;
        border-radius: 4px;
    }

    .progress-bar-fill {
        content: "";
        position: absolute;
        top: 35px;
        left: 0;
        height: 6px;
        background: #28a745;
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .checkpoint {
        text-align: center;
        position: relative;
        z-index: 2;
        width: 100px;
        cursor: pointer;
    }

    .checkpoint.locked {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .checkpoint .dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        transition: transform 0.3s;
    }

    .checkpoint.completed .dot {
        background: #28a745;
        color: white;
        transform: scale(1.1);
    }

    .checkpoint.available .dot {
        background: #ffc107;
        color: #000;
    }

    .checkpoint.locked .dot {
        background: #6c757d;
        color: white;
    }

    .checkpoint-title {
        margin-top: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .checkpoint:hover .dot {
        transform: scale(1.25);
    }

    .checkpoint.locked:hover .dot {
        transform: scale(1); /* disabled */
    }

    /* Progress percentage text */
    .progress-percent {
        text-align: right;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #28a745;
    }

    /* Sembunyikan progress track di mobile */
    @media (max-width: 1000px) {
        .progress-percent,
        .progress-track,
        .progress-bar-fill,
        .checkpoint {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $total = count($subModules);
    $completedCount = 0;
    $previousCompleted = true;
@endphp

{{-- Hitung completed --}}
@foreach($subModules as $sm)
    @php 
        $progress = $progressData->get($sm->id);
        if ($progress && $progress->completed_at) $completedCount++;
    @endphp
@endforeach

{{-- Hitung persentase --}}
@php 
    $percentage = $total > 0 ? round(($completedCount / $total) * 100) : 0;
@endphp

<div class="card shadow-sm" style="border-radius: var(--border-radius-xl); padding-inline:15px">
    {{-- Percentage text --}}
    <div class="progress-percent mt-5">
        Progress: {{ $percentage }}%
    </div>

    {{-- Progress bar track --}}
    <div class="progress-track px-10">
        <div class="progress-bar-fill" style="width: {{ $percentage }}%;"></div>

        @foreach($subModules as $subModule)
            @php
                $progress = $progressData->get($subModule->id);
                $isCompleted = $progress && $progress->completed_at;
                $isAvailable = $previousCompleted;
                $isLocked = !$isAvailable;

                if ($isCompleted) {
                    $statusClass = 'completed';
                    $icon = 'fa-check';
                    $link = route('student.submodule.show', [$kelas->id, $subModule->id]);
                } elseif ($isAvailable) {
                    $statusClass = 'available';
                    $icon = 'fa-arrow-right';
                    $link = route('student.submodule.show', [$kelas->id, $subModule->id]);
                } else {
                    $statusClass = 'locked';
                    $icon = 'fa-lock';
                    $link = '#';
                }
            @endphp

            <a 
                href="{{ $isLocked ? '#' : $link }}" 
                class="checkpoint {{ $statusClass }}" 
                @if($isLocked) onclick="event.preventDefault();" @endif
            >
                <div class="dot">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="checkpoint-title">
                    {{ Str::limit($subModule->title, 50) }}
                </div>
            </a>

            @php 
                $previousCompleted = $isCompleted;
            @endphp
        @endforeach
    </div>
</div>

<div class="container-fluid py-4"> {{-- Tambahkan padding vertikal --}}
    {{-- [REVISI BAGIAN 1: HEADER/HERO SECTION DENGAN ILUSTRASI] --}}
    <div class="class-hero-card mb-4">
        {{-- <img src="{{asset('assets/images/hero_class.png')}}" alt="Ilustrasi Kelas" class="hero-illustration"> --}}

        <div class="hero-text-content">
            <h2>{{ $modul->judul }}</h2>
            <p class="h5 mt-1">{{__('admin.siswa.class_show.welcome')}}: <strong>{{ $kelas->nama_kelas }}</strong></p>

            {{-- [BAGIAN 1: DESKRIPSI RINGKAS (HANYA MOBILE)] --}}
            <p class="card-text mt-3 d-block d-md-none small short-description-mobile">
                {!! Str::limit($modul->deskripsi, 80) !!}
            </p>

            {{-- [BAGIAN 2: DESKRIPSI LENGKAP & TOMBOL TOGGLE (MOBILE & DESKTOP)] --}}
            <div class="full-description-toggle" id="fullDescription">
                <p class="card-text small m-0">{!! $modul->deskripsi !!}</p>
            </div>

            {{-- Tombol Toggle (Hanya di Mobile) --}}
            <a href="#" id="toggleDescriptionBtn" class="toggle-button-hero d-block d-md-none"
                onclick="toggleFullDescription(event)">
                <i class="fa fa-chevron-down me-1"></i> Baca Deskripsi Lengkap
            </a>
        </div>
    </div>

    {{-- [REVISI BAGIAN 2: RPS DAN DAFTAR KURIKULUM] --}}
    <div class="card shadow-sm" style="border-radius: var(--border-radius-xl);">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-3 p-md-4"
            style="border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;">
            <h5 class="mb-2 mb-md-0 text-primary fw-bold">{{ __('admin.siswa.class_show.header') }}</h5>

            <div>
                @if($modul->rps_file)
                <a href="{{ asset('storage/' . $modul->rps_file) }}" target="_blank" class="btn btn-sm rps-button"
                    title='{{ __("admin.siswa.class_show.learning_plan_title") }}'>
                    <i class="fa fa-file me-2"></i>
                    {{ __('admin.siswa.class_show.learning_plan') }}
                </a>
                @else
                <span class="text-danger small">
                    <i class="fa fa-info-circle me-1"></i>
                    {{ __('admin.siswa.class_show.no_learning_plan_file') }}
                </span>
                @endif
            </div>
        </div>

        <div class="card-body p-3 p-md-4"> {{-- Padding disesuaikan untuk mobile --}}
            {{-- [REVISI BAGIAN 3: DAFTAR SUB-MODUL (KARTU MISI)] --}}
            <div class="list-group">
                @php $previousCompleted = true; @endphp
                @forelse($subModules as $subModule)
                @php
                $progress = $progressData->get($subModule->id);
                $isCompleted = $progress && $progress->completed_at;
                $isAvailable = $previousCompleted;
                $isLocked = !$isAvailable;

                // Tentukan kelas status visual
                $statusClass = '';
                $statusIconColor = '';
                $statusBadgeClass = '';

                if ($isCompleted) {
                $statusClass = 'status-completed';
                $statusIconColor = 'text-success';
                $statusBadgeClass = 'bg-success';
                $statusTitle = __('admin.siswa.class_show.finish');
                } elseif ($isAvailable) {
                $statusClass = 'status-available';
                $statusIconColor = 'text-warning';
                $statusBadgeClass = 'bg-warning text-dark';
                $statusTitle = __('admin.siswa.class_show.available');
                } else {
                $statusClass = 'status-locked disabled';
                $statusIconColor = 'text-secondary';
                $statusBadgeClass = 'bg-secondary';
                $statusTitle = __('admin.siswa.class_show.locked');
                }

                // Tentukan ikon kategori
                $typeIcon = '';
                $useSvgIcon = false;

                if ($subModule->type == 'learning') { $typeIcon = 'fa-book-reader';
                } elseif ($subModule->type == 'reflection') { $typeIcon = 'fa-chart-line';
                } elseif ($subModule->type == 'practicum') { 
                // Gunakan SVG khusus
                $useSvgIcon = true; $typeIcon = asset('assets/iconfonts/phyphox.svg');
                } elseif ($subModule->type == 'forum') { $typeIcon = 'fa-comments';
                } else { $typeIcon = 'fa-puzzle-piece';}

                $linkHref = $isLocked ? '#' : route('student.submodule.show', [$kelas->id, $subModule->id]);
                @endphp

                <a href="{{ $linkHref }}" class="module-list-card {{ $statusClass }}"
                    aria-disabled="{{ $isLocked ? 'true' : 'false' }}" title="{{ $statusTitle }}" @if($isLocked)
                    onclick="event.preventDefault();" @endif>

                    
                    <div class="d-flex w-100 align-items-center">
                        {{-- Ikon Besar Kategori Modul --}}
                        <div class="module-icon-large {{ $statusIconColor }}">
                            @if($useSvgIcon)
                                {{-- Inline SVG agar warna mengikuti class statusIconColor --}}
                                @php
                                    $svgPath = public_path('assets/iconfonts/phyphox.svg');
                                    $svgContent = file_exists($svgPath) ? file_get_contents($svgPath) : null;

                                    if ($svgContent) {
                                        // Tambahkan class untuk stroke warna mengikuti parent
                                        // dan ubah fill menjadi none agar tidak menimpa warna
                                        $svgContent = preg_replace(
                                            '/<svg([^>]*)>/',
                                            '<svg$1 class="w-100 h-100 stroke-current fill-none" stroke-width="2.2">',
                                            $svgContent,
                                            1
                                        );

                                        // Pastikan semua stroke berwarna currentColor
                                        $svgContent = str_replace(['stroke="#000"', 'stroke="black"'], 'stroke="currentColor"', $svgContent);

                                        // Jika SVG punya fill hitam, ganti agar mengikuti currentColor
                                        $svgContent = str_replace(['fill="#000"', 'fill="black"'], 'fill="currentColor"', $svgContent);
                                    }
                                @endphp

                                {!! $svgContent !!}

                            @else
                                <i class="fas {{ $typeIcon }}"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1 me-2">
                            {{-- [FIX JUDUL] Hapus class text-truncate dan style max-width --}}
                            <h5 class="mb-1 fw-bold">
                                {{ $subModule->title }}
                            </h5>
                            <p class="mb-1 small {{ $isLocked ? '' : 'text-muted' }}">
                                {{-- Deskripsi dibatasi pendek untuk mobile --}}
                                {!! Str::limit($subModule->description, 70) !!}
                            </p>
                        </div>

                        {{-- Badge Status Cepat --}}
                        <div class="ms-auto flex-shrink-0">
                            <span class="status-badge {{ $statusBadgeClass }}">
                                @if($isCompleted)
                                <i class="fas fa-check-circle"></i>
                                @elseif($isAvailable)
                                <i class="fas fa-arrow-circle-right"></i>
                                @else
                                <i class="fas fa-lock"></i>
                                @endif

                                {{-- [FIX STATUS] Teks status hanya tampil di Desktop (d-none di mobile) --}}
                                <span class="d-none d-md-inline ms-1">{{ $statusTitle }}</span>
                            </span>
                        </div>
                    </div>
                </a>

                @php $previousCompleted = $isCompleted; @endphp
                @empty
                <div class="alert alert-light text-center p-4">
                    {{-- <img src="https://via.placeholder.com/100x100/e9ecef/6c757d?text=No+Modules"
                        alt="Tidak ada modul" class="img-fluid mb-3" style="max-width: 100px;"> --}}
                    <h5 class="text-secondary">{{ __('admin.siswa.class_show.no_curriculum') }}.</h5>
                    <p class="small text-muted">Akan segera ditambahkan oleh guru Anda.</p>
                </div>
                @endforelse

                {{-- [REVISI BAGIAN 4: GRADE SUMMARY CARD (Piala) DENGAN ILUSTRASI] --}}
                <div class="grade-summary-card shadow-sm mb-3">
                    {{-- Ilustrasi untuk background grade card --}}
                    {{-- <img src="{{asset('assets/images/grade_ilustration.png')}}" alt="Ilustrasi nilai"
                        class="grade-illustration d-none d-md-block">
                    <img src="{{asset('assets/images/grade_ilustration.png')}}" alt="Ilustrasi nilai"
                        class="grade-illustration d-block d-md-none"> --}}

                    <div class="card-body">
                        <i class="fa fa-trophy trophy-icon-large mb-3"></i>
                        <h5 class="card-title text-white">{{ __('admin.siswa.class_show.summary_grade') }}</h5>
                        <p class="card-text text-white-50">{{ __('admin.siswa.class_show.card_text') }}.</p>
                        <a href="{{ route('student.class.grades', $kelas->id) }}" class="btn btn-lg mt-3">
                            <i class="fa fa-star me-2"></i> {{ __('admin.siswa.class_show.transcript') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center bg-white p-3 p-md-4"
            style="border-radius: 0 0 var(--border-radius-xl) var(--border-radius-xl);">
            <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left me-2"></i> {{__('admin.button.back_to')}} {{__('admin.dashboard.title')}}
            </a>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
function toggleFullDescription(event) {
    event.preventDefault();

    const fullDesc = document.getElementById('fullDescription');
    const shortDesc = document.querySelector('.short-description-mobile');
    const toggleBtn = document.getElementById('toggleDescriptionBtn');

    if (fullDesc.classList.contains('show-full')) {
        // Mode Sembunyikan
        fullDesc.classList.remove('show-full');
        shortDesc.style.display = 'block'; // Tampilkan lagi ringkasan
        toggleBtn.innerHTML = '<i class="fa fa-chevron-down me-1"></i> Baca Deskripsi Lengkap';
    } else {
        // Mode Tampilkan
        fullDesc.classList.add('show-full');
        shortDesc.style.display = 'none'; // Sembunyikan ringkasan
        toggleBtn.innerHTML = '<i class="fa fa-chevron-up me-1"></i> Sembunyikan Deskripsi';
    }
}
</script>
@endpush
