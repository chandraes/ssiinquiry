@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@section('content')
@include('student.partials.grade_feedback_box')
<div class="container-fluid">
    <div class="col-md-12 mb-5">
        <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title"><i class="fa fa-pencil-square text-info me-2"></i>{{ $subModule->title }}</h2>
            <p class="text-muted">{{ $subModule->description }}</p>
            <p class="lead">{{__('admin.siswa.show_reflection.lead')}}.</p>
        </div>
    </div>

    <div class="card shadow-sm">

        {{-- [DIUBAH] Tambahkan ID pada form --}}
        <form action="{{ route('student.reflection.store', [$kelas->id, $subModule->id]) }}" method="POST" id="reflectionForm">
            @csrf
            <div class="card-body">

                {{-- Blok Notifikasi (Sudah benar dari sebelumnya) --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{__('admin.siswa.show_reflection.answer_instruction')}}.
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif


                @forelse($subModule->reflectionQuestions as $index => $question)
                    @php
                        $existingAnswer = $existingAnswers->get($question->id);
                        $errorKey = 'answers.' . $question->id;
                        $value = old($errorKey, $existingAnswer->answer_text ?? '');
                    @endphp

                    <div class="mb-4">
                        <label for="answer-{{ $question->id }}" class="form-label fw-bold h5">
                            {{ $index + 1 }}. {{ $question->question_text }}
                        </label>

                        <textarea name="answers[{{ $question->id }}]"
                                  id="answer-{{ $question->id }}"
                                  class="form-control @error($errorKey) is-invalid @enderror"
                                  rows="4"
                                  placeholder='{{__("admin.siswa.show_reflection.answer_placeholder")}}'>{{ $value }}</textarea>
                    </div>
                @empty
                    <div class="alert alert-light text-center">
                        {{__('admin.siswa.show_reflection.no_reflection')}}.
                    </div>
                @endforelse
            </div>

            {{-- Card Footer (Dua Tombol) --}}
            @if($subModule->reflectionQuestions->isNotEmpty())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" name="action" value="save_draft"
                                    class="btn btn-secondary">
                                <i class="fa fa-save me-2"></i> 
                                {{__('admin.siswa.show_reflection.save_draft')}}
                            </button>
                        </div>
                        <div>
                            <button type="submit" name="action" value="complete"
                                    class="btn btn-primary btn-lg">
                                {{__('admin.siswa.show_reflection.save_next')}}
                                <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </form>
    </div>

</div>
@endsection

{{-- [BARU] Tambahkan push('js') untuk SweetAlert --}}
@push('js')
<script>
$(document).ready(function() {

    // Intersep klik pada KEDUA tombol submit di dalam form
    $('#reflectionForm button[type="submit"]').on('click', function(e) {
        e.preventDefault(); // Hentikan pengiriman form otomatis

        var form = $(this).closest('form');
        var actionName = $(this).attr('name'); // 'action'
        var actionValue = $(this).attr('value'); // 'save_draft' atau 'complete'

        var swalTitle = '';
        var swalText = '';
        var swalConfirmText = '';

        if (actionValue === 'save_draft') {
            swalTitle = '{{ __("admin.swal.save_title") }}'; // "Simpan Perubahan?"
            swalText = '{{__("admin.siswa.show_reflection.save_next")}}.';
            swalConfirmText = '{{ __("admin.swal.save_confirm") }}'; // "Ya, Simpan"
        } else {
            // Ini untuk 'complete'
            swalTitle = '{{__("admin.siswa.show_reflection.save_next")}}?';
            swalText = '{{__("admin.siswa.show_reflection.save_text")}}.';
            swalConfirmText = '{{__("admin.siswa.show_reflection.forward")}}';
        }

        Swal.fire({
            title: swalTitle,
            text: swalText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: swalConfirmText,
            cancelButtonText: '{{ __("admin.swal.cancel") }}',
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kita perlu mengirimkan form
                // DENGAN menyertakan tombol mana yang diklik.

                // Hapus input 'action' tersembunyi sebelumnya (jika ada)
                form.find('input[type="hidden"][name="action"]').remove();

                // Tambahkan input tersembunyi baru dengan nilai 'action'
                form.append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', actionName)
                        .attr('value', actionValue)
                );

                // Kirim form
                form.submit();
            }
        });
    });
});
</script>
@endpush
