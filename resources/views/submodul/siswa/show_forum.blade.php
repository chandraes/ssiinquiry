@extends('layouts.app')

@section('title')
    {{ __('admin.forum_settings.title') }}: {{ $subModul->title }}
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
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.forum_settings.general_settings') }}</h5>
        </div>
        {{-- TODO: Buat route dan controller untuk update ini --}}
        <form action="{{-- route('submodul.forum.update_settings', $subModul->id) --}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
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

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('admin.forum_settings.debate_rules') }}</label>
                    {{-- Beri ID unik dan kelas untuk TinyMCE --}}
                    <textarea name="debate_rules" id="debate_rules_editor" class="form-control rich-text-editor" rows="8">{{ $subModul->debate_rules }}</textarea>
                </div>

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
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">{{ __('admin.forum_settings.update_settings') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
{{-- Muat skrip editor WYSIWYG Anda --}}
<script src="{{ asset('assets/path/ke/editor.js') }}"></script> {{-- <-- Ganti path ini --}}
<script>
    // Inisialisasi TinyMCE untuk Aturan Debat
    tinymce.init({
        selector: '#debate_rules_editor', // Targetkan ID unik
        plugins: 'lists link code fullscreen', // Sesuaikan
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link code | fullscreen', // Sesuaikan
        menubar: false,
        height: 250,
        license_key: 'gpl',
    });

    $('#updateForumSettingsForm').on('submit', function(e) {
        e.preventDefault();
        var form = this;

        // Simpan data dari editor WYSIWYG
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

    // TODO: Tambahkan JavaScript untuk:
    // 1. Mengisi daftar 'Siswa Belum Ditugaskan' (mungkin via AJAX).
    // 2. Menangani klik tombol +/-/x untuk memindahkan siswa antar list (via AJAX).
    // 3. Menangani submit form 'Pengaturan Umum Forum' (pastikan memanggil tinymce.triggerSave()).
</script>
@endpush
