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
        </div>

        <div class="card-body">
            @forelse($subModul->learningMaterials as $material)
                <div class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border">
                    <div>
                        <strong>{{ $material->title }}</strong>
                        {{-- Logika Tampilan (dari jawaban sebelumnya) --}}
                        @if($material->type == 'rich_text')
                            <div class="rich-text-content border p-2 rounded-2 mt-2" style="max-height: 200px; overflow-y: auto;">
                                {!! $material->content !!}
                            </div>
                        @else
                            @php
                                $url = null;
                                if (is_array($material->content) && isset($material->content['url'])) {
                                    $url = $material->content['url'];
                                } else {
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
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    {{__('admin.siswa.no_materi')}}.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('js')
{{-- [BARU] JavaScript untuk Edit dan Delete Material --}}
<script>
    
    
</script>
@endpush
