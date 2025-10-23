@extends('layouts.app')

@section('title')
    {{ $subModul->title }}
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $subModul->title }}</h2>
            <p class="text-muted">{{ $subModul->description }}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Learning Materials</h5>

            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addMaterialMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-plus me-2"></i>Tambah Materi
                </button>
                <ul class="dropdown-menu" aria-labelledby="addMaterialMenu">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addVideoModal">Video</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addArticleModal">Artikel (URL)</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addInfographicModal">Infografis (URL)</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addRegulationModal">Regulasi (URL)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addRichTextModal">Teks / Artikel (Rich Text)</a></li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            @forelse($subModul->learningMaterials as $material)
                <div class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border rounded">
                    <div>
                        <strong>{{ $material->title }}</strong>
                        <small class="badge
                            @if($material->type == 'video') bg-danger
                            @elseif($material->type == 'article') bg-info
                            @elseif($material->type == 'infographic') bg-success
                            @elseif($material->type == 'rich_text') bg-dark
                            @else bg-warning @endif
                        ">{{ $material->type }}</small>

                        {{-- [PERBAIKAN LOGIKA TAMPILAN] --}}
                        @if($material->type == 'rich_text')
                            {{--
                                Kasus 1: Tipe Rich Text.
                                $material->content adalah string HTML (karena Spatie).
                            --}}
                            <div class="rich-text-content border p-2 rounded-2 mt-2" style="max-height: 200px; overflow-y: auto;">
                                {!! $material->content !!}
                            </div>
                        @else
                            {{--
                                Kasus 2: Semua tipe URL (video, article, dll.)
                                Kita harus mengecek format BARU dan LAMA.
                            --}}
                            @php
                                $url = null;
                                if (is_array($material->content) && isset($material->content['url'])) {
                                    // Format BARU: Spatie mengembalikan ['url' => '...']
                                    $url = $material->content['url'];
                                } else {
                                    // Format LAMA: Spatie mengembalikan null. Kita ambil data mentah.
                                    $rawContent = json_decode($material->getRawOriginal('content'), true);
                                    if (is_array($rawContent) && isset($rawContent['url'])) {
                                        $url = $rawContent['url'];
                                    }
                                }
                            @endphp

                            @if($url)
                                <p class="mb-0 text-muted">
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $url }}
                                    </a>
                                </p>
                            @endif
                        @endif
                        {{-- [AKHIR PERBAIKAN] --}}

                    </div>
                    <div>
                        <button class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    Belum ada materi pembelajaran untuk sub modul ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Memuat SEMUA Modal yang Mungkin Dibutuhkan --}}
@include('learning_material.modals.video_modal')
@include('learning_material.modals.article_modal')
@include('learning_material.modals.infographic_modal')
@include('learning_material.modals.regulation_modal')
@include('learning_material.modals.rich_text_modal') {{-- Pastikan ini ada --}}

@endsection
