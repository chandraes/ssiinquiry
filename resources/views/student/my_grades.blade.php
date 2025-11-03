@extends('layouts.app')

@section('title')
    {{__('admin.siswa.my_grades.title')}}: {{ $kelas->nama_kelas }}
@endsection

{{-- [BARU] CSS Khusus untuk Mencetak (Print) --}}
@push('css')
<style>
    /* CSS untuk halaman web biasa */
    .grade-card {
        border-left: 4px solid #0d6efd;
    }
    .grade-feedback {
        font-size: 0.9em;
        white-space: pre-wrap; /* Agar 'Enter' dari guru terlihat */
    }

    /* CSS yang hanya aktif saat di-print */
    @media print {
        /* Sembunyikan semua elemen yang tidak relevan */
        body {
            background-color: #fff;
        }
        .navbar, .sidebar, .breadcrumb, #print-button-wrapper {
            display: none !important;
        }

        /* Pastikan konten utama menggunakan lebar penuh */
        .main-content, .container-fluid {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Hapus bayangan dan border untuk tampilan cetak yang bersih */
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid; /* Hindari kartu terpotong di tengah halaman */
        }

        /* Beri judul pada dokumen cetak */
        @page {
            size: A4;
            margin: 20mm;
        }

        h1, h4, h5 {
            color: #000 !important;
        }
    }
</style>
@endpush


@section('content')
<div class="container-fluid">

    {{-- [BARU] Tombol Cetak --}}
    <div class="row mb-3" id="print-button-wrapper">
        <div class="col text-end">
            <button id="downloadPdfBtn" class="btn btn-primary">
                <i class="fa fa-print me-2"></i> {{__('admin.siswa.my_grades.download')}}
            </button>
        </div>
    </div>

    {{-- [BARU] Wrapper untuk konten yang akan dicetak --}}
    <div id="transcript-content">

        {{-- 1. Kartu Judul --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h1 class="card-title">{{__('admin.siswa.my_grades.card_title')}}</h1>
                <h4 class="card-subtitle mb-2 text-muted">{{ $kelas->modul->judul }}</h4>
                <p class="mb-0">{{__('admin.siswa.my_grades.class')}}: <strong>{{ $kelas->nama_kelas }}</strong></p>
                <p class="mb-0">{{__('admin.siswa.my_grades.student')}}: <strong>{{ Auth::user()->name }}</strong></p>
            </div>
        </div>

        {{-- 2. Kartu Total Skor --}}
        <div class="card shadow-sm mb-4 grade-card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">{{__('admin.siswa.my_grades.final_score')}}</h6>

                {{-- Hanya tampilkan jika ada nilai --}}
                @if($totalMaxPoints > 0)
                    <h1 class="display-3 fw-bold text-primary">
                        {{ $totalScore }}
                        <span class="fs-4 text-muted">/ {{ $totalMaxPoints }}</span>
                    </h1>

                    {{-- Opsi: Tampilkan nilai akhir (skala 100) --}}
                    <h4 class="fw-normal text-muted">
                        ({{__('admin.siswa.my_grades.score')}}: {{ round(($totalScore / $totalMaxPoints) * 100, 1) }} / 100)
                    </h4>
                @else
                    <h4 class="text-muted p-4">{{__('admin.siswa.my_grades.no_score')}}.</h4>
                @endif

                <small class="text-muted">
                    {{__('admin.siswa.my_grades.information')}}.
                </small>
            </div>
        </div>

        {{-- 3. Kartu Rincian Nilai --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">{{__('admin.siswa.my_grades.header_grade')}}</h5>
            </div>

            {{-- Tampilkan daftar hanya jika ada nilai --}}
            @if($totalMaxPoints > 0)
                <ul class="list-group list-group-flush">

                    {{-- Kita loop SEMUA sub-modul yang bisa dinilai --}}
                    @foreach($subModules as $subModule)

                        {{-- Ambil progress untuk sub-modul ini --}}
                        @php $progress = $allProgress->get($subModule->id); @endphp

                        {{-- Tampilkan HANYA jika sudah dinilai (score tidak null) --}}
                        @if ($progress && $progress->score !== null)

                            <li class="list-group-item py-3 px-3">
                                {{-- Baris Utama (Judul & Skor) --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-0">{{ $subModule->title }}</h6>

                                        {{-- Helper Badge Tipe --}}
                                        @php
                                            $typeLabel = ucfirst($subModule->type);
                                            $typeBadge = 'secondary';
                                            if ($subModule->type == 'reflection') $typeBadge = 'info';
                                            if ($subModule->type == 'practicum') $typeBadge = 'success';
                                            if ($subModule->type == 'forum') $typeBadge = 'warning';
                                        @endphp
                                        <span class="badge bg-{{ $typeBadge }}">{{ $typeLabel }}</span>
                                    </div>

                                    {{-- Skor --}}
                                    <span class="fs-4 fw-bold text-dark ms-3">
                                        {{ $progress->score }}
                                        <small class="text-muted">/ {{ $subModule->max_points }}</small>
                                    </span>
                                </div>

                                {{-- Umpan Balik (jika ada) --}}
                                @if (!empty($progress->feedback))
                                    <div class="text-muted border-start border-2 ps-2 grade-feedback">
                                        <strong>{{__('admin.siswa.my_grades.feedback')}}:</strong> {{ $progress->feedback }}
                                    </div>
                                @endif
                            </li>

                        @endif
                    @endforeach
                </ul>
            @else
                <div class="card-body text-center p-5">
                    <p class="text-muted">{{__('admin.siswa.my_grades.no_grade')}}.</p>
                </div>
            @endif
        </div>

    </div> {{-- Penutup #transcript-content --}}
    <div class="card">
        <div class="card-footer">
            <div class="col text-start">
                <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.my_grades.back')}}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- [BARU] JavaScript untuk tombol Print --}}
@push('js')
<script>
    $(document).ready(function() {
        $('#downloadPdfBtn').on('click', function() {
            // Panggil fungsi print bawaan browser
            window.print();
        });
    });
</script>
@endpush
