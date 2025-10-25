@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Kurikulum
            </a>
            <h2 class="card-title"><i class="fa fa-book-open text-primary me-2"></i>{{ $subModule->title }}</h2>
            <p class="text-muted">{{ $subModule->description }}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @forelse($subModule->learningMaterials as $material)
                <h4 class="mt-3">{{ $material->title }}</h4>
                <hr>
                @if($material->type == 'rich_text')
                    {{-- Tampilkan Rich Text --}}
                    <div classs="rich-text-content p-2">{!! $material->content !!}</div>

                @elseif($material->type == 'video')
                    {{-- Tampilkan Video (Contoh embed YouTube) --}}
                    @php
                        // Coba ekstrak URL dari data
                        $url = is_array($material->content) ? ($material->content['url'] ?? null) : null;
                        if ($url && str_contains($url, 'youtu.be/')) {
                            $videoId = substr($url, strrpos($url, '/') + 1);
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } elseif ($url && str_contains($url, 'youtube.com/watch?v=')) {
                            parse_str(parse_url($url, PHP_URL_QUERY), $query);
                            $videoId = $query['v'] ?? null;
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } else {
                            $embedUrl = null;
                        }
                    @endphp
                    @if($embedUrl)
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $embedUrl }}" title="{{ $material->title }}" allowfullscreen></iframe>
                        </div>
                    @else
                        <p>Link video tidak valid: <a href="{{ $url }}" target="_blank">{{ $url }}</a></p>
                    @endif

                @else
                    {{-- Tampilkan Link (Artikel, Infografis, Regulasi) --}}
                    @php $url = is_array($material->content) ? ($material->content['url'] ?? null) : null; @endphp
                    <p>
                        Silakan akses materi di link berikut:
                        <a href="{{ $url }}" target="_blank" class="btn btn-info btn-sm">
                            Buka {{ $material->type }} <i class="fa fa-external-link"></i>
                        </a>
                    </p>
                @endif
            @empty
                <p class="text-muted">Konten untuk materi ini belum tersedia.</p>
            @endforelse
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body text-center">

            {{-- Cek apakah sub-modul ini SUDAH selesai --}}
            @if($currentProgress && $currentProgress->completed_at)

                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    Anda telah menyelesaikan materi ini pada {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>

            @else

                <p class="lead">Setelah Anda selesai mempelajari semua materi di atas, tandai sebagai selesai untuk melanjutkan.</p>

                {{-- Form untuk "Tandai Selesai" --}}
                <form action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        Tandai Selesai & Lanjutkan <i class="fa fa-arrow-right ms-2"></i>
                    </button>
                </form>

            @endif
        </div>
    </div>

</div>
@endsection
