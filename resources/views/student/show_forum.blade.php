@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection

{{-- Berikan style khusus untuk membedakan postingan --}}
@push('css')
<style>
    .post-card {
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .post-card.team-pro {
        border-left: 5px solid #198754; /* Hijau (Pro) */
        background: #f0fdf4;
    }
    .post-card.team-con {
        border-left: 5px solid #dc3545; /* Merah (Kontra) */
        background: #fff5f5;
    }
    .post-reply {
        margin-left: 40px;
        border-left: 3px solid #ddd;
        padding-left: 15px;
    }
    .evidence-list {
        font-size: 0.9em;
    }
    .rich-text-content img {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Kurikulum
            </a>
            <h2 class="card-title"><i class="fa fa-comments text-danger me-2"></i>{{ $subModule->title }}</h2>

            {{-- Info Tim --}}
            @if($teamInfo)
                <div class="alert alert-{{ $teamInfo->team == 'pro' ? 'success' : 'danger' }} fs-5">
                    <i class="fa fa-users me-2"></i>
                    Anda berada di <strong>Tim {{ $teamInfo->team == 'pro' ? 'Pro' : 'Kontra' }}</strong>.
                </div>
            @else
                <div class="alert alert-warning">
                    Anda belum ditugaskan ke tim manapun oleh guru. Anda tidak bisa mem-posting.
                </div>
            @endif

            <hr>
            <h4>Topik Debat:</h4>
            <p class="lead">{{ $subModule->getTranslation('debate_topic', 'id') ?? 'Topik belum diatur.' }}</p>
            <h4>Aturan Debat:</h4>
            <div class="rich-text-content p-2">{!! $subModule->getTranslation('debate_rules', 'id') ?? '<p>Aturan belum diatur.</p>' !!}</div>
        </div>
    </div>

    @if($teamInfo)
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Buat Postingan / Balasan</h5>
        </div>
        <form action="{{ route('student.forum.store', [$kelas->id, $subModule->id]) }}" method="POST" id="main-post-form">
            @csrf
            {{-- Input tersembunyi untuk ID balasan --}}
            <input type="hidden" name="parent_post_id" id="parent-post-id-input">

            <div class="card-body">
                {{-- Notifikasi Sukses/Error --}}
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Notifikasi jika sedang membalas --}}
                <div class="alert alert-info" id="reply-to-info" style="display:none;">
                    <span>Sedang membalas postingan dari <strong id="reply-to-user"></strong>...</span>
                    <button type="button" class="btn-close float-end" id="cancel-reply-btn"></button>
                </div>

                {{-- Editor Teks --}}
                <div class="mb-3">
                    <label for="forum-editor" class="form-label">Argumen Anda:</label>
                    <textarea name="content" id="forum-editor" rows="8">{{ old('content') }}</textarea>
                </div>

                {{-- Lampirkan Bukti (Evidence) --}}
                <div class="mb-3">
                    <label class="form-label">Lampirkan Bukti (dari Praktikum Anda):</label>
                    @forelse($mySubmissions as $submission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="evidence_ids[]"
                               value="{{ $submission->id }}" id="evidence-{{ $submission->id }}">
                        <label class="form-check-label" for="evidence-{{ $submission->id }}">
                            {{ $submission->original_filename }}
                            <small class="text-muted">(dari Slot: {{ $submission->slot->label }})</small>
                        </label>
                    </div>
                    @empty
                    <p class="text-muted small">Anda belum mengunggah file praktikum apapun.</p>
                    @endforelse
                </div>

            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary btn-lg">Kirim Postingan</button>
            </div>
        </form>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Arena Debat</h5>
        </div>
        <div class="card-body">
            @forelse($posts as $post)
                {{-- Kirim data postingan ke partial view (biar rapi) --}}
                @include('student.partials.forum_post', ['post' => $post, 'is_reply' => false])
            @empty
                <div class="alert alert-light text-center">
                    Belum ada postingan. Jadilah yang pertama!
                </div>
            @endforelse
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body text-center">
            @if($currentProgress && $currentProgress->completed_at)
                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-2"></i>
                    Anda telah menyelesaikan forum ini pada {{ $currentProgress->completed_at->format('d M Y, H:i') }}.
                </div>
            @else
                <p class="lead">Setelah Anda merasa cukup berpartisipasi dalam debat, tandai sebagai selesai.</p>
                <form action="{{ route('student.submodule.complete', [$kelas->id, $subModule->id]) }}" method="POST" id="complete-forum-form">
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
@push('js')

<script>
    // 1. Inisialisasi TinyMCE
    tinymce.init({
        selector: '#forum-editor',
        plugins: 'lists link image media autoresize',
        toolbar: 'undo redo | bold italic | bullist numlist | link image media',
        height: 250,
        menubar: false,
        license_key: 'gpl',
        autoresize_bottom_margin: 20,

    });

    // 2. Logika Tombol "Balas"
    $('.reply-btn').on('click', function() {
        var postId = $(this).data('post-id');
        var postUser = $(this).data('post-user');

        // Isi input hidden dengan ID post yang dibalas
        $('#parent-post-id-input').val(postId);

        // Tampilkan notifikasi
        $('#reply-to-user').text(postUser);
        $('#reply-to-info').slideDown();

        // Scroll ke atas ke form editor
        $('html, body').animate({
            scrollTop: $("#main-post-form").offset().top - 80 // -80px untuk offset navbar
        }, 500);

        // Fokuskan ke editor
        tinymce.get('forum-editor').focus();
    });

    // 3. Logika Tombol "Batal Balas"
    $('#cancel-reply-btn').on('click', function() {
        // Kosongkan input hidden
        $('#parent-post-id-input').val('');

        // Sembunyikan notifikasi
        $('#reply-to-info').slideUp();
    });

    // 4. Konfirmasi Swal untuk Form Postingan Utama
    $('#main-post-form').on('submit', function(e) {
        e.preventDefault(); // Hentikan submit
        var form = this;

        // (PENTING) Update textarea dari TinyMCE
        tinymce.triggerSave();

        Swal.fire({
            title: 'Kirim Postingan?',
            text: "Postingan Anda akan terlihat oleh seluruh kelas.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Lanjutkan submit
            }
        });
    });

    // 5. Konfirmasi Swal untuk Form "Tandai Selesai"
    $('#complete-forum-form').on('submit', function(e) {
        e.preventDefault(); // Hentikan submit
        var form = this;

        Swal.fire({
            title: 'Selesai Berdebat?',
            text: "Pastikan Anda sudah berpartisipasi aktif sebelum melanjutkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Lanjutkan submit
            }
        });
    });

</script>
@endpush
