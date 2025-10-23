@extends('layouts.app')

@section('title')
    Pertanyaan Refleksi: {{ $subModul->title }}
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
            <h5 class="mb-0">Daftar Pertanyaan Refleksi</h5>

            {{-- [DIUBAH] Tombol ini sekarang memicu modal --}}
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                <i class="fa fa-plus me-2"></i>Tambah Pertanyaan
            </button>
        </div>

        <div class="card-body">
            @forelse($subModul->reflectionQuestions as $question)
                <div class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border rounded">
                    <div>
                        {{-- Spatie otomatis menerjemahkan question_text --}}
                        <strong>{{ $question->order }}. {{ $question->question_text }}</strong>
                    </div>

                    {{-- [BARU] Tombol Aksi Edit & Delete --}}
                    <div>
                        <button class="btn btn-warning btn-sm"
                                title="Edit Pertanyaan"
                                data-url="{{ route('reflection_question.edit.json', $question->id) }}"
                                data-update-url="{{ route('reflection_question.update', $question->id) }}"
                                onclick="editQuestion(this)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"
                                title="Hapus Pertanyaan"
                                onclick="deleteQuestion({{ $question->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>

                <form id="delete-question-form-{{ $question->id }}"
                      action="{{ route('reflection_question.destroy', $question->id) }}"
                      method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>

            @empty
                <div class="alert alert-info text-center">
                    Belum ada pertanyaan refleksi untuk sub modul ini.
                </div>
            @endforelse
        </div>
    </div>

    <div class="card shadow-sm">
         <div class="card-header"><h5 class="mb-0">Jawaban Siswa</h5></div>
         <div class="card-body">
             <p class="text-muted">Tampilan untuk jawaban siswa per kelas akan muncul di sini.</p>
         </div>
    </div>
</div>

{{-- [BARU] Memuat modal --}}
@include('reflection_question.create_modal')
@include('reflection_question.edit_modal')

@endsection

@push('js')
{{-- [BARU] JavaScript untuk Edit dan Delete Pertanyaan --}}
<script>
    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT
     */
    function editQuestion(button) {
        var modal = $('#editQuestionModal');
        var form = $('#editQuestionForm');

        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        // Set action form
        form.attr('action', updateUrl);

        // Ambil data JSON
        $.get(dataUrl, function(data) {

            // Isi input Teks Pertanyaan (ID dan EN)
            modal.find('#edit_question_text_id').val(data.question_text.id);
            modal.find('#edit_question_text_en').val(data.question_text.en);

            // Isi Nomor Urut
            modal.find('#edit_question_order').val(data.order);

            // Tampilkan modal
            modal.modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data pertanyaan.', 'error');
        });
    }

    /**
     * 2. FUNGSI KONFIRMASI DELETE
     */
    function deleteQuestion(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form delete yang sesuai
                document.getElementById('delete-question-form-' + id).submit();
            }
        })
    }

    /**
     * 3. FUNGSI KONFIRMASI UPDATE (SweetAlert)
     */
    $(document).ready(function() {
        $('#editQuestionForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: "{{ __("admin.swal.update_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
