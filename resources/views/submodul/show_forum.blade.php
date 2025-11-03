@extends('layouts.app')

@section('title')
    {{ __('admin.forum_settings.title') }}: {{ $subModul->title }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        {{-- ... (Info Header - tidak berubah) ... --}}
        <div class="card-body">
            <h2 class="card-title">{{ $subModul->title }}</h2>
            <p class="text-muted">{{ $subModul->description }}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.forum_settings.general_settings') }}</h5>
        </div>

        <form action="{{ route('submodul.forum.update_settings', $subModul->id) }}" method="POST" id="updateForumSettingsForm">
            @csrf
            @method('PUT')
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Error Validasi:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Topik Debat (Sudah Benar) --}}
                <label class="form-label fw-bold">{{ __('admin.forum_settings.debate_topic') }}</label>
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#topic-id-pane-e" type="button" role="tab">ID</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#topic-en-pane-e" type="button" role="tab">EN</button></li>
                </ul>
                <div class="tab-content mb-3">
                    <div class="tab-pane fade show active" id="topic-id-pane-e" role="tabpanel">
                        <input name="debate_topic[id]" class="form-control" value="{{ $subModul->getTranslation('debate_topic', 'id') }}">
                    </div>
                    <div class="tab-pane fade" id="topic-en-pane-e" role="tabpanel">
                        <input name="debate_topic[en]" class="form-control" value="{{ $subModul->getTranslation('debate_topic', 'en') }}">
                    </div>
                </div>

                {{-- [PERUBAHAN DI SINI] Aturan Debat sekarang Translatable --}}
                <label class="form-label fw-bold">{{ __('admin.forum_settings.debate_rules') }}</label>
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#rules-id-pane-e" type="button" role="tab">ID</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#rules-en-pane-e" type="button" role="tab">EN</button></li>
                </ul>
                <div class="tab-content mb-3">
                    <div class="tab-pane fade show active" id="rules-id-pane-e" role="tabpanel">
                        <textarea name="debate_rules[id]" id="debate_rules_editor_id" class="form-control rich-text-editor" rows="8">
                            {{ $subModul->getTranslation('debate_rules', 'id') }}
                        </textarea>
                    </div>
                    <div class="tab-pane fade" id="rules-en-pane-e" role="tabpanel">
                        <textarea name="debate_rules[en]" id="debate_rules_editor_en" class="form-control rich-text-editor" rows="8">
                            {{ $subModul->getTranslation('debate_rules', 'en') }}
                        </textarea>
                    </div>
                </div>

                {{-- Pengaturan Waktu (Tidak berubah) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.forum_settings.start_time') }}</label>
                        <input type="datetime-local" name="debate_start_time" class="form-control" value="{{ $subModul->debate_start_time?->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.forum_settings.end_time') }}</label>
                        <input type="datetime-local" name="debate_end_time" class="form-control" value="{{ $subModul->debate_end_time?->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.forum_settings.phase1_end') }}</label>
                        <input type="datetime-local" name="phase1_end_time" class="form-control" value="{{ $subModul->phase1_end_time?->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin.forum_settings.phase2_end') }}</label>
                        <input type="datetime-local" name="phase2_end_time" class="form-control" value="{{ $subModul->phase2_end_time?->format('Y-m-d\TH:i') }}">
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('modul.show', $subModul->modul_id) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back') }}
                    </a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin.forum_settings.update_settings') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    // [PERUBAHAN DI SINI] Inisialisasi TinyMCE
    tinymce.init({
        // Gunakan selector class untuk menargetkan SEMUA editor
        selector: 'textarea.rich-text-editor',
        plugins: 'lists link code fullscreen',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link code | fullscreen',
        menubar: false,
        height: 250,
        license_key: 'gpl',
    });

    // Skrip submit form (sudah benar, 'triggerSave' akan menyimpan SEMUA editor)
    $('#updateForumSettingsForm').on('submit', function(e) {
        e.preventDefault();
        var form = this;

        tinymce.triggerSave();

        Swal.fire({
            title: '{{ __("admin.swal.update_title") }}',
            text: "Yakin ingin menyimpan pengaturan forum ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
