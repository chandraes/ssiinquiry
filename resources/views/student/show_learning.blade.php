@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@push('css')
<style>
    :root {
        --primary-color: #4a4ff2;
        --accent-color-1: #fff3cd;
        --accent-color-2: #8be8ff;
        --success-light: #d1e7dd;
        --shadow-strong: 0 0.5rem 1.5rem rgba(0,0,0,0.15);
        --shadow-soft: 0 4px 12px rgba(0,0,0,0.08);
        --radius-xl: 20px;
    }

    /* ============ DESKRIPSI SUBMODULE ============ */
    .card-description {
        background: linear-gradient(135deg, var(--primary-color) 0%, #7b68ee 100%);
        color: white !important;
        border-radius: var(--radius-xl);
        padding: 30px !important;
        box-shadow: var(--shadow-strong);
        border: none;
    }

    .card-description h2 {
        font-weight: 700;
        font-size: 2rem;
    }

    /* ============ STYLE KATEGORI MATERI ============ */
    .material-box {
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 25px;
        box-shadow: var(--shadow-soft);
        border: none;
    }

    .material-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    /* Rich Text */
    .material-richtext {
        background-color: var(--accent-color-1);
        border-left: 8px solid #ffb700;
    }

    /* Video */
    .material-video {
        background-color: var(--accent-color-2);
        border-left: 8px solid #0dcaf0;
    }

    /* Other / Link */
    .material-other {
        background-color: var(--success-light);
        border-left: 8px solid #198754;
    }

    /* Modern Badge Ikon */
    .material-icon {
        font-size: 1.6rem;
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(255,255,255,0.5);
        margin-right: 10px;
    }

    /* Video Wrapper */
    .video-wrapper {
        border-radius: 16px;
        overflow: hidden;
    }

    /* ============ CARD WARNA BERDASARKAN TYPE ============ */
    .card-richtext {
        background-color: var(--accent-color-1) !important;
        border-left: 10px solid #ffb700 !important;
        border-radius: var(--radius-xl);
    }

    .card-video {
        background-color: var(--accent-color-2) !important;
        border-left: 10px solid #0dcaf0 !important;
        border-radius: var(--radius-xl);
    }

    .card-other {
        background-color: var(--success-light) !important;
        border-left: 10px solid #198754 !important;
        border-radius: var(--radius-xl);
    }

    /* Card body padding tetap rapi */
    .card-richtext .card-body,
    .card-video .card-body,
    .card-other .card-body {
        padding: 25px;
    }

    /* Ikon tetap modern */
    .material-icon {
        background: rgba(255,255,255,0.6);
    }

</style>
@endpush


@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 card-description">
        <div class="card-body">
            <h2 class="card-title"><i class="fa fa-book-open text-primary me-2"></i>{{ $subModule->title }}</h2>

            <p class="text-muted">{!! $subModule->description !!}</p>
        </div>
    </div>
     @forelse($subModule->learningMaterials as $material)
    <div class="card shadow-sm mb-4 
        @if($material->type === 'rich_text') card-richtext 
        @elseif($material->type === 'video') card-video 
        @else card-other 
        @endif">

        <div class="card-body">

            {{-- Icon & Judul --}}
            <div class="d-flex align-items-center mb-2">
                @switch($material->type)
                    @case('rich_text')
                        <i class="fa fa-align-left material-icon"></i>
                        @break
                    @case('video')
                        <i class="fa fa-play-circle material-icon"></i>
                        @break
                    @default
                        <i class="fa fa-link material-icon"></i>
                @endswitch

                <span class="material-title">{{ $material->title }}</span>
            </div>


            {{-- === RICH TEXT === --}}
            @if($material->type === 'rich_text')
                {!! is_string($material->content) ? $material->content : '' !!}


            {{-- === VIDEO === --}}
            @elseif($material->type === 'video')

                @php
                    $content = is_array($material->content) ? $material->content : [];
                    $url = $content['url'] ?? null;
                    $embedUrl = null;

                    if ($url) {
                        if (str_contains($url, 'youtu.be/')) {
                            $videoId = substr($url, strrpos($url, '/') + 1);
                            $embedUrl = "https://www.youtube.com/embed/$videoId";
                        } elseif (str_contains($url, 'youtube.com/watch?v=')) {
                            parse_str(parse_url($url, PHP_URL_QUERY), $query);
                            $videoId = $query['v'] ?? null;
                            $embedUrl = "https://www.youtube.com/embed/$videoId";
                        }
                    }
                @endphp

                @if($embedUrl)
                    <div class="video-wrapper ratio ratio-16x9">
                        <iframe src="{{ $embedUrl }}" allowfullscreen></iframe>
                    </div>
                @endif


            {{-- === OTHER MATERIAL === --}}
            @else
                @php
                    $url = is_array($material->content)
                        ? ($material->content['url'] ?? '#')
                        : (is_string($material->content) ? $material->content : '#');
                @endphp

                <div class="mt-3">
                    <a href="{{ $url }}" target="_blank" class="btn btn-success btn-sm">
                        <i class="fa fa-external-link-alt"></i>
                        {{ __('admin.siswa.show_learning.open') }} {{ $material->type }}
                    </a>
                </div>
            @endif

        </div>
    </div>
@empty

    <p class="text-muted">{{ __('admin.siswa.show_learning.no_materi') }}.</p>
@endforelse


    <div class="card shadow-sm">
        <div class="card-body pb-0 text-center">

            {{-- Cek apakah sub-modul ini SUDAH selesai --}}
            @if($currentProgress && $currentProgress->completed_at)

                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    {{__('admin.siswa.show_learning.finish')}} {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>
                <div class="card-footer text-center mt-5">
                    <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
                    </a>
                </div>

            @else

                <p class="lead">{{__('admin.siswa.show_learning.finish_instruction')}}.</p>

                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3 mt-5">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-secondary btn-lg flex-fill text-nowrap">
                        <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
                    </a>

                    {{-- Tombol Tandai Selesai --}}
                    <form action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}" method="POST" class="flex-fill text-end">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 text-nowrap">
                            {{__('admin.siswa.show_learning.button_finish')}} <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
@push('js')
<script>

    function hideGlobalLoader() {
        // Tambahkan sedikit penundaan (misalnya 500ms) untuk mengakomodir Safari.
        // Ini memastikan browser memiliki waktu untuk merender iframe.
        setTimeout(function() {
            const globalLoader = document.getElementById('global-loader');
            if (globalLoader) {
                globalLoader.style.display = 'none';
            }
        }, 500); // Penundaan 500 milidetik
    }
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}"]');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // cegah submit langsung

            Swal.fire({
                title: '{{__("admin.siswa.show_learning.swal.title")}}',
                text: '{{__("admin.siswa.show_learning.swal.text")}}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{__("admin.siswa.show_learning.swal.confirm")}}',
                cancelButtonText: '{{__("admin.button.cancel")}}',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // lanjut submit jika dikonfirmasi
                }
            });
        });
    }
});
</script>
@endpush
