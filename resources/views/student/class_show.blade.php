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
        padding: 20px; /* Padding lebih kecil untuk mobile */
        box-shadow: var(--shadow-strong);
        position: relative;
        overflow: hidden;
        min-height: 150px; /* Tinggi minimum untuk mobile */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .class-hero-card h2 {
        font-size: 1.8rem; /* Ukuran font lebih kecil lagi untuk mobile agar tidak memotong */
        font-weight: 800;
        margin-bottom: 0.5rem;
        z-index: 1;
    }

    .class-hero-card p {
        font-size: 0.9rem; /* Ukuran font lebih kecil lagi */
        opacity: 0.9;
        z-index: 1;
    }

    /* Ilustrasi di Header Kelas */
  .hero-illustration {
        position: absolute;
        bottom: -20px; /* Tarik sedikit ke bawah */
        right: -10px; /* Tarik sedikit ke kanan */
        width: 120px; /* Ukuran ilustrasi sangat kecil untuk mobile */
        height: auto;
        opacity: 0.6; /* Kurangi opasitas agar teks lebih menonjol */
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4"> {{-- Tambahkan padding vertikal --}}

    {{-- [REVISI BAGIAN 1: HEADER/HERO SECTION DENGAN ILUSTRASI] --}}
    <div class="class-hero-card mb-4">
        {{-- Ilustrasi untuk background header (Mobile-First) --}}
        {{-- Kita gunakan satu tag img saja dan kontrol dengan CSS untuk ukuran responsif --}}
        {{-- <img src="{{asset('assets/images/hero_class.png')}}" alt="Ilustrasi Kelas" class="hero-illustration"> --}}

        {{-- Konten Teks --}}
        <div class="hero-text-content">
            <h2>{{ $modul->judul }}</h2>
            <p class="h5 mt-1">{{__('admin.siswa.class_show.welcome')}}: <strong>{{ $kelas->nama_kelas }}</strong></p>

            {{-- Deskripsi panjang (Desktop) --}}
            <p class="card-text mt-3 d-none d-md-block">{{ $modul->deskripsi }}</p>

            {{-- Deskripsi ringkas (Mobile) --}}
            <p class="card-text mt-3 d-block d-md-none small">
                {{ Str::limit($modul->deskripsi, 60) }} {{-- Dibatasi lebih pendek --}}
            </p>
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
                if($subModule->type == 'learning') $typeIcon = 'fa-book-reader'; // Ikon yang lebih menarik
                elseif($subModule->type == 'reflection') $typeIcon = 'fa-lightbulb';
                elseif($subModule->type == 'practicum') $typeIcon = 'fa-flask';
                elseif($subModule->type == 'forum') $typeIcon = 'fa-comments';
                else $typeIcon = 'fa-puzzle-piece';

                $linkHref = $isLocked ? '#' : route('student.submodule.show', [$kelas->id, $subModule->id]);
                @endphp

                <a href="{{ $linkHref }}" class="module-list-card {{ $statusClass }}"
                    aria-disabled="{{ $isLocked ? 'true' : 'false' }}" title="{{ $statusTitle }}" @if($isLocked)
                    onclick="event.preventDefault();" @endif>

                    <div class="d-flex w-100 align-items-center">
                        {{-- Ikon Besar Kategori Modul --}}
                        <div class="module-icon-large {{ $statusIconColor }}">
                            <i class="fas {{ $typeIcon }}"></i>
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
                    {{-- <img src="https://via.placeholder.com/100x100/e9ecef/6c757d?text=No+Modules" alt="Tidak ada modul"
                        class="img-fluid mb-3" style="max-width: 100px;"> --}}
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
