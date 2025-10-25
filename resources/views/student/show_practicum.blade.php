@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@section('content')
<div class="container-fluid">
    @php
        // Ambil petunjuk (asumsi hanya ada 1 petunjuk per praktikum)
        // Kita gunakan relasi 'learningMaterials' yang sudah di-load di controller
        $instruction = $subModule->learningMaterials->first();
    @endphp

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Kurikulum
            </a>
            <h2 class="card-title"><i class="fa fa-flask text-success me-2"></i>{{ $subModule->title }}</h2>
            <p class="text-muted">{{ $subModule->description }}</p>
        </div>
    </div>

    @if($instruction)
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.practicum.instructions') }}</h5>
        </div>
        <div class="card-body">
            <div class="rich-text-content p-2">
                {{-- Tampilkan konten dari petunjuk (rich text) --}}
                {!! $instruction->content !!}
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.practicum.upload_slots') }}</h5>
        </div>
        <div class="card-body">
            <p class="lead">Unggahlah file data CSV Anda pada slot yang sesuai di bawah ini.</p>

            {{-- Tampilkan notifikasi sukses/error global --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Loop menggunakan @forelse (foreach + empty check) --}}
            @forelse($subModule->practicumUploadSlots as $slot)
                @php
                    // Cek apakah sudah ada file yang diunggah untuk slot ini
                    // Data $existingSubmissions ini dikirim dari StudentController
                    $submission = $existingSubmissions->get($slot->id);
                @endphp

                {{-- Setiap slot adalah card-nya sendiri --}}
                <div class="card mb-3 {{ $submission ? 'border-success' : 'border-light' }}">
                    <div class="card-body">

                        {{-- Setiap slot adalah form-nya sendiri --}}
                        <form action="{{ route('student.practicum.store', [$kelas->id, $subModule->id, $slot->id]) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $slot->label }}</h5>
                                    <small class="text-muted">{{ $slot->description }}</small>
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="practicum_file" class="form-control" required>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload me-2"></i> Unggah
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- [PENTING] Menampilkan error HANYA untuk slot yang gagal --}}
                        @if($errors->has('practicum_file') && session('failed_slot_id') == $slot->id)
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('practicum_file') }}
                            </div>
                        @endif

                        {{-- Tampilkan file yang sudah terunggah --}}
                        @if($submission)
                            <div class="alert alert-success mt-3 mb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fa fa-check-circle me-2"></i>
                                    Terunggah: <strong>{{ $submission->original_filename }}</strong>

                                    {{-- Cek ukuran file jika ada --}}
                                    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($submission->file_path))
                                        ({{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($submission->file_path) / 1024, 2) }} KB)
                                    @endif
                                </div>
                                {{-- TODO: Tambahkan tombol Hapus/Ganti --}}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center">
                    Belum ada slot unggahan yang dikonfigurasi oleh guru.
                </div>
            @endforelse
        </div>

        {{-- Footer Selesai Otomatis --}}
        <div class="card-footer text-center">
            {{-- $currentProgress dikirim dari StudentController@showSubModule --}}
            @if($currentProgress && $currentProgress->completed_at)
                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    Anda telah menyelesaikan praktikum ini pada {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>
            @else
                <p class="text-muted mb-0">
                    Sub-modul ini akan ditandai selesai secara otomatis setelah semua slot unggahan wajib terisi.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
